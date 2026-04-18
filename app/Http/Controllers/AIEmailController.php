<?php

namespace App\Http\Controllers;

use App\Services\AIEmailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\Mime\Email;

class AIEmailController extends Controller
{
    public function __construct(
        private AIEmailService $aiEmailService
    ) {}

    public function show(string $type, int $id): View
    {
        return view('components.ai-email-generator', [
            'type' => $type,
            'id' => $id,
        ]);
    }

    public function generate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'email_type' => 'required|in:follow_up,proposal,welcome,meeting,thank_you,custom',
            'tone' => 'required|in:formal,friendly,salesy,casual',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Get sender information from authenticated user
        $user = $request->user();
        $senderData = [
            'name' => $user->name,
            'role' => $user->hasRole('Admin') ? __('messages.roles.admin') : 
                     ($user->hasRole('Manager') ? __('messages.roles.manager') : 
                     ($user->hasRole('Agent') ? __('messages.roles.agent') : 
                     __('messages.roles.super_admin'))),
            'tanent' => optional($user->tenant)->name ?? 'Unknown Company',
            'phone' => $user->phone ?? 'N/A',
            'email' => $user->email,
        ];

        // Merge sender data with validated data
        $data = array_merge($validated, $senderData);

        $result = $this->aiEmailService->generateEmail($data);

        return response()->json($result);
    }
}
