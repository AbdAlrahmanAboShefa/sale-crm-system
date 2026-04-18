@props(['clientName', 'clientEmail'])

{{-- Debug: {{ $clientName }} - {{ $clientEmail }} --}}

<div x-data="aiEmailGenerator()" x-init="init()" class="inline-block">
    <button
        type="button"
        @click="openPanel()"
        class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-violet-600 to-indigo-600 text-white rounded-xl hover:from-violet-700 hover:to-indigo-700 font-medium text-sm shadow-lg shadow-violet-500/25 transition-all duration-200 hover:shadow-violet-500/40 hover:-translate-y-0.5"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        <span>{{ __('messages.ai_email.generate_email') }}</span>
    </button>

    <div
        x-show="isOpen"
        x-cloak
        class="fixed inset-0 z-[9999] overflow-hidden"
        style="display: none;"
    >
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closePanel()"></div>

        <div
            class="absolute inset-y-0 right-0 w-full max-w-2xl bg-slate-800 shadow-2xl border-l border-slate-700 flex flex-col"
            x-transition:enter="transform transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            @click.stop
        >
            <div class="flex flex-col h-full">
                <div class="px-6 py-4 border-b border-slate-700 bg-slate-800">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-violet-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-violet-500/30">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-slate-100">{{ __('messages.ai_email.title') }}</h2>
                                <p class="text-xs text-slate-400">{{ __('messages.ai_email.subtitle') }}</p>
                            </div>
                        </div>
                        <button
                            type="button"
                            @click="closePanel()"
                            class="w-8 h-8 rounded-lg hover:bg-slate-700 flex items-center justify-center text-slate-400 hover:text-slate-200 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="bg-slate-700/50 rounded-xl p-4 border border-slate-600">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-violet-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white text-xl font-bold shadow-lg">
                                {{ strtoupper(substr($clientName, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-slate-100 truncate">{{ $clientName }}</h3>
                                <p class="text-sm text-slate-400 truncate">{{ $clientEmail }}</p>
                            </div>
                            <span class="px-3 py-1.5 bg-violet-500/20 text-violet-400 rounded-full text-xs font-semibold flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                                </svg>
                                AI
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-6 space-y-5">
                    <template x-if="!generatedEmail">
                        <div class="space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-300 mb-2">{{ __('messages.ai_email.email_type') }}</label>
                                    <select
                                        x-model="formData.email_type"
                                        class="dark-select w-full"
                                    >
                                        <option value="follow_up">{{ __('messages.ai_email.types.follow_up') }}</option>
                                        <option value="proposal">{{ __('messages.ai_email.types.proposal') }}</option>
                                        <option value="welcome">{{ __('messages.ai_email.types.welcome') }}</option>
                                        <option value="meeting">{{ __('messages.ai_email.types.meeting') }}</option>
                                        <option value="thank_you">{{ __('messages.ai_email.types.thank_you') }}</option>
                                        <option value="custom">{{ __('messages.ai_email.types.custom') }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-300 mb-2">{{ __('messages.ai_email.tone') }}</label>
                                    <select
                                        x-model="formData.tone"
                                        class="dark-select w-full"
                                    >
                                        <option value="formal">{{ __('messages.ai_email.tones.formal') }}</option>
                                        <option value="friendly">{{ __('messages.ai_email.tones.friendly') }}</option>
                                        <option value="salesy">{{ __('messages.ai_email.tones.salesy') }}</option>
                                        <option value="casual">{{ __('messages.ai_email.tones.casual') }}</option>
                                    </select>
                                </div>
                            </div>

                             <div>
                                 <label class="block text-sm font-semibold text-slate-300 mb-2">
                                     {{ __('messages.ai_email.context') }}
                                     <span class="text-slate-500 font-normal">({{ __('messages.common.optional') }})</span>
                                 </label>
                                 <textarea
                                     x-model="formData.notes"
                                     rows="4"
                                     placeholder="{{ __('messages.ai_email.context_placeholder') }}"
                                     class="dark-input w-full resize-none"
                                 ></textarea>
                                 <p class="mt-1.5 text-xs text-slate-500">{{ __('messages.ai_email.context_hint') }}</p>
                             </div>
                             


                            <button
                                type="button"
                                @click="generateEmail()"
                                :disabled="isLoading"
                                class="w-full py-3.5 bg-gradient-to-r from-violet-600 to-indigo-600 text-white rounded-xl font-semibold text-sm flex items-center justify-center gap-2 transition-all duration-200 shadow-lg shadow-violet-500/25 disabled:opacity-70 cursor-not-allowed"
                                :class="!isLoading && 'hover:shadow-xl hover:shadow-violet-500/30 hover:-translate-y-0.5'"
                            >
                                <template x-if="isLoading">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>{{ __('messages.ai_email.generating') }}</span>
                                    </div>
                                </template>
                                <template x-if="!isLoading">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        <span>{{ __('messages.ai_email.generate') }}</span>
                                    </div>
                                </template>
                            </button>
                        </div>
                    </template>

                    <template x-if="generatedEmail">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500/20 text-amber-400 rounded-full text-xs font-bold">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                                    </svg>
                                    AI Suggestion
                                </span>
                                <button
                                    type="button"
                                    @click="resetForm()"
                                    class="text-sm text-violet-400 hover:text-violet-300 font-medium flex items-center gap-1"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    {{ __('messages.ai_email.new_email') }}
                                </button>
                            </div>

                            <div class="bg-slate-700/50 rounded-2xl border border-slate-600 overflow-hidden">
                                <div class="px-5 py-4 border-b border-slate-600 bg-slate-700/30">
                                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">{{ __('messages.ai_email.subject') }}</label>
                                    <input
                                        type="text"
                                        x-model="editableSubject"
                                        class="w-full px-0 py-0 bg-transparent text-slate-100 text-sm font-medium border-0 focus:ring-0 focus:outline-none"
                                        placeholder="Email subject..."
                                    >
                                </div>
                                <div class="p-5">
                                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">{{ __('messages.ai_email.body') }}</label>
                                    <textarea
                                        x-model="editableBody"
                                        rows="10"
                                        class="w-full px-0 py-0 bg-transparent text-slate-300 text-sm leading-relaxed border-0 focus:ring-0 focus:outline-none resize-none"
                                        placeholder="Email body..."
                                    ></textarea>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <button
                                    type="button"
                                    @click="copyToClipboard()"
                                    :disabled="isCopied"
                                    class="flex-1 py-3 rounded-xl font-semibold text-sm flex items-center justify-center gap-2 transition-all duration-200"
                                    :class="isCopied ? 'bg-emerald-500 text-white' : 'bg-slate-700 text-slate-200 hover:bg-slate-600'"
                                >
                                    <template x-if="!isCopied">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                        </svg>
                                    </template>
                                    <template x-if="isCopied">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </template>
                                    <span x-text="isCopied ? '{{ __('messages.ai_email.copied') }}' : '{{ __('messages.ai_email.copy') }}'"></span>
                                </button>
                                <button
                                    type="button"
                                    @click="regenerateEmail()"
                                    :disabled="isLoading"
                                    class="flex-1 py-3 bg-gradient-to-r from-violet-600 to-indigo-600 text-white rounded-xl font-semibold text-sm flex items-center justify-center gap-2 hover:from-violet-700 hover:to-indigo-700 transition-all duration-200 shadow-lg shadow-violet-500/25 disabled:opacity-70"
                                >
                                    <template x-if="isLoading">
                                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </template>
                                    <template x-if="!isLoading">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    </template>
                                    <span>{{ __('messages.ai_email.regenerate') }}</span>
                                </button>
                            </div>
                        </div>
                    </template>

                    <template x-if="errorMessage">
                        <div class="bg-rose-500/20 border border-rose-500/30 rounded-xl p-4 flex items-start gap-3">
                            <svg class="w-5 h-5 text-rose-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-rose-300" x-text="errorMessage"></p>
                        </div>
                    </template>
                </div>

                <div class="px-6 py-4 border-t border-slate-700 bg-slate-800">
                    <button
                        type="button"
                        @click="closePanel()"
                        class="w-full py-2.5 text-slate-400 hover:text-slate-200 font-medium text-sm flex items-center justify-center gap-2 transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        {{ __('messages.ai_email.close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function aiEmailGenerator() {
    return {
        isOpen: false,
        isLoading: false,
        isCopied: false,
        generatedEmail: null,
        editableSubject: '',
        editableBody: '',
        errorMessage: '',

        formData: {
            client_name: '{{ $clientName }}',
            client_email: '{{ $clientEmail }}',
            email_type: 'follow_up',
            tone: 'friendly',
            notes: '',
        },

        init() {
            this.resetForm();
        },

        openPanel() {
            this.isOpen = true;
            document.body.style.overflow = 'hidden';
            this.resetForm();
        },

        closePanel() {
            this.isOpen = false;
            document.body.style.overflow = '';
        },

        resetForm() {
            this.generatedEmail = null;
            this.editableSubject = '';
            this.editableBody = '';
            this.errorMessage = '';
            this.formData.notes = '';
            this.isCopied = false;
        },

        async generateEmail() {
            this.isLoading = true;
            this.errorMessage = '';

            try {
                const response = await fetch('{{ route('ai.email.generate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(this.formData),
                });

                const data = await response.json();

                if (data.success) {
                    this.generatedEmail = data;
                    this.editableSubject = data.subject;
                    this.editableBody = data.body;
                } else {
                    this.errorMessage = data.error || '{{ __('messages.ai_email.error') }}';
                }
            } catch (error) {
                console.error('Email generation error:', error);
                this.errorMessage = '{{ __('messages.ai_email.error') }}';
            } finally {
                this.isLoading = false;
            }
        },

        async regenerateEmail() {
            await this.generateEmail();
        },

        async copyToClipboard() {
            const emailContent = `Subject: ${this.editableSubject}\n\n${this.editableBody}`;

            try {
                await navigator.clipboard.writeText(emailContent);
                this.isCopied = true;
                setTimeout(() => {
                    this.isCopied = false;
                }, 2000);
            } catch (error) {
                console.error('Copy failed:', error);
                const textarea = document.createElement('textarea');
                textarea.value = emailContent;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                this.isCopied = true;
                setTimeout(() => {
                    this.isCopied = false;
                }, 2000);
            }
        },
    };
}
</script>
@endpush
