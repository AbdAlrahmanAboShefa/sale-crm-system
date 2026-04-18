<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIEmailService
{
    private string $apiKey;

    private string $baseUrl = 'https://openrouter.ai/api/v1';

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key', '');
    }

    public function generateEmail(array $data): array
    {
        $clientName = $data['client_name'];
        $clientEmail = $data['client_email'];
        $emailType = $data['email_type'];
        $tone = $data['tone'];
        $notes = $data['notes'] ?? '';
        $locale = app()->getLocale();
        $name = $data['name'] ?? 'Sales Team';
        $role = $data['role'] ?? 'sales_rep';
        $tanent = $data['tanent'] ?? 'default';
        $phone = $data['phone'] ?? 'N/A';
        $email = $data['email'] ?? 'N/A';

        $prompt = $this->buildPrompt($clientName, $emailType, $tone, $notes, $locale, $name, $role, $tanent, $phone,$email);

        try {
            $response = Http::withHeaders([
    'Authorization' => 'Bearer ' . $this->apiKey,
    'HTTP-Referer' => 'http://localhost',
    'X-Title' => 'CRM App',
])
->timeout(60)
->post("{$this->baseUrl}/chat/completions", [
    'model' => 'openai/gpt-4o-mini',
    'messages' => [
        [
            'role' => 'system',
            'content' => 'You are a professional email assistant. Always return JSON with subject and body.',
        ],
        [
            'role' => 'user',
            'content' => $prompt,
        ],
    ],
]); 
Log::info('STATUS', ['status' => $response->status()]);
Log::info('BODY', ['body' => $response->body()]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content');
                $result = json_decode(trim($content), true);

                if (json_last_error() === JSON_ERROR_NONE && isset($result['subject'], $result['body'])) {
                    return [
                        'success' => true,
                        'subject' => $result['subject'],
                        'body' => $result['body'],
                    ];
                }

                return $this->parseFallbackEmail($content);
            }

            Log::error('OpenAI API Error', ['status' => $response->status(), 'body' => $response->body()]);

            return [
                'success' => false,
                'error' => 'Failed to generate email. Please try again.',
            ];
        } catch (\Exception $e) {
            Log::error('AI Email Generation Error', ['message' => $e->getMessage()]);

            return [
                'success' => false,
                'error' => 'An error occurred while generating the email.',
            ];
        }
    }

    private function buildPrompt(string $clientName, string $emailType, string $tone, string $notes, string $locale, string $name, string $role, string $tanent, string $phone, string $email): string
    {
        $typeInstructions = match ($emailType) {
            'follow_up' => 'This is a follow-up email. Reference a previous interaction and remind them of next steps.',
            'proposal' => 'This is a proposal email. Present an offer with clear value proposition and pricing.',
            'welcome' => 'This is a welcome email. Introduce yourself/company and set expectations for the relationship.',
            'meeting' => 'This is a meeting request email. Propose a meeting time and agenda.',
            'thank_you' => 'This is a thank you email. Express gratitude for their time, purchase, or consideration.',
            default => 'This is a custom email based on the provided context.',
        };

        $toneInstructions = match ($tone) {
            'formal' => 'Use formal, professional language. Avoid contractions and casual phrases.',
            'friendly' => 'Use warm, friendly language. Be personable but professional.',
            'salesy' => 'Use persuasive, enthusiastic language. Highlight benefits and create urgency.',
            'casual' => 'Use casual, conversational language. Keep it light and approachable.',
            default => 'Use a balanced professional tone.',
        };

        $senderInfo = "Your name: {$name}\nYour role: {$role}\nYour company: {$tanent}\nYour phone: {$phone}\nYour email: {$email}";
        $context = $notes ? "\n\nAdditional context: {$notes}" : '';
        $lang = $locale === 'ar' ? 'in Arabic' : 'in English';

        return "Write a professional sales email {$lang}.\n\nClient name: {$clientName}\n\nSender information:\n{$senderInfo}\n\nEmail type: {$typeInstructions}\nTone: {$toneInstructions}{$context}\n\nPlease include the sender's full contact information in the email signature.\n\nReturn JSON format:\n{\n  \"subject\": \"Email subject line\",\n  \"body\": \"Email body with line breaks\"\n}";
    }

    private function parseFallbackEmail(string $content): array
    {
        $lines = explode("\n", trim($content));
        $subject = '';
        $body = [];
        $inBody = false;

        foreach ($lines as $line) {
            if (preg_match('/subject[:\s]*(.+)/i', $line, $matches)) {
                $subject = trim($matches[1]);
                $inBody = true;
            } elseif ($inBody || (empty($subject) && ! preg_match('/^[{["]/', trim($line)))) {
                $body[] = trim($line);
            }
        }

        if (empty($subject)) {
            $subject = 'Quick Update';
        }

        return [
            'success' => true,
            'subject' => $subject,
            'body' => implode("\n", array_filter($body)),
        ];
    }
}
