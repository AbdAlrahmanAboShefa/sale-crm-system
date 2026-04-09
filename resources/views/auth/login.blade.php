<!DOCTYPE html>
<html class="light" lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>{{ __('messages.auth.login') }} | ExecutiveCRM</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    @if(app()->getLocale() === 'ar')
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet"/>
    <style>
        body, h1, h2, h3 { font-family: 'Cairo', 'Inter', sans-serif; }
    </style>
    @endif
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-secondary": "#ffffff",
                        "on-tertiary": "#ffffff",
                        "tertiary-fixed": "#ffdbc8",
                        "on-tertiary-fixed-variant": "#753400",
                        "surface-container-high": "#e7e8e9",
                        "primary-fixed-dim": "#a3c9ff",
                        "surface-container-highest": "#e1e3e4",
                        "on-primary-container": "#8abcff",
                        "secondary-fixed-dim": "#b9c8d8",
                        "secondary-container": "#d2e1f2",
                        "on-primary-fixed": "#001c38",
                        "on-background": "#191c1d",
                        "on-secondary-fixed": "#0e1d29",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f3f4f5",
                        "tertiary": "#572500",
                        "on-surface": "#191c1d",
                        "surface-tint": "#27609d",
                        "inverse-on-surface": "#f0f1f2",
                        "inverse-primary": "#a3c9ff",
                        "tertiary-fixed-dim": "#ffb68b",
                        "error": "#ba1a1a",
                        "primary-container": "#004b87",
                        "on-primary": "#ffffff",
                        "outline": "#727781",
                        "on-tertiary-container": "#ffa46a",
                        "on-primary-fixed-variant": "#004882",
                        "secondary": "#51606e",
                        "primary": "#003461",
                        "surface-container": "#edeeef",
                        "on-secondary-fixed-variant": "#3a4856",
                        "secondary-fixed": "#d5e4f5",
                        "surface-bright": "#f8f9fa",
                        "error-container": "#ffdad6",
                        "on-tertiary-fixed": "#321300",
                        "tertiary-container": "#793701",
                        "on-error-container": "#93000a",
                        "surface": "#f8f9fa",
                        "inverse-surface": "#2e3132",
                        "primary-fixed": "#d3e4ff",
                        "on-secondary-container": "#556472",
                        "outline-variant": "#c2c6d1",
                        "on-surface-variant": "#424750",
                        "background": "#f8f9fa",
                        "surface-dim": "#d9dadb",
                        "on-error": "#ffffff",
                        "surface-variant": "#e1e3e4"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "fontFamily": {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .font-headline { font-family: 'Manrope', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .bg-login-texture {
            background-image: radial-gradient(circle at 2px 2px, rgba(0, 52, 97, 0.05) 1px, transparent 0);
            background-size: 24px 24px;
        }
        .sunk-in-input {
            background-color: #f3f4f5;
            border: none;
            border-bottom: 2px solid transparent;
            transition: border-color 0.3s ease;
        }
        .sunk-in-input:focus {
            outline: none;
            border-bottom-color: #003461;
            box-shadow: none;
        }
        .error-input {
            border-bottom-color: #ba1a1a !important;
        }
    </style>
</head>
<body class="bg-background text-on-surface min-h-screen flex flex-col">
    <div class="absolute top-4 right-4 z-50">
        <a href="{{ route('language.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" 
           class="flex items-center gap-2 px-3 py-2 bg-white rounded-lg shadow-md hover:bg-slate-50 transition-colors text-sm">
            <i class="fas fa-globe"></i>
            <span class="uppercase">{{ app()->getLocale() === 'ar' ? 'EN' : 'عربي' }}</span>
        </a>
    </div>
    <main class="flex-grow flex items-center justify-center pt-24 pb-12 px-6 bg-login-texture">
        <div class="w-full max-w-md">
            <div class="bg-surface-container-lowest rounded-xl p-10 shadow-[0_32px_64px_-12px_rgba(25,28,29,0.04)] border border-outline-variant/10">
                <div class="mb-10 text-center">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-primary mb-2 opacity-80 font-label">Secure Access</p>
                    <h1 class="text-3xl font-extrabold text-on-surface tracking-tight">{{ __('messages.dashboard.welcome') }}</h1>
                    <p class="text-slate-500 mt-2 text-sm">Please enter your credentials to manage your pipeline.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 bg-error-container text-on-error-container rounded-lg text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-on-surface-variant font-label tracking-wide" for="email">{{ __('messages.auth.email') }}</label>
                        <div class="relative">
                            <input class="w-full sunk-in-input px-4 py-3 text-on-surface placeholder:text-slate-400 font-body rounded-t-sm @error('email') error-input @enderror" 
                                id="email" name="email" placeholder="name@company.com" type="email" value="{{ old('email') }}" required autofocus/>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <label class="block text-xs font-bold text-on-surface-variant font-label tracking-wide" for="password">{{ __('messages.auth.password') }}</label>
                            @if (Route::has('password.request'))
                                <a class="text-xs font-semibold text-primary hover:underline transition-all" href="{{ route('password.request') }}">{{ __('messages.auth.forgot_password') }}</a>
                            @endif
                        </div>
                        <div class="relative">
                            <input class="w-full sunk-in-input px-4 py-3 text-on-surface placeholder:text-slate-400 font-body rounded-t-sm @error('password') error-input @enderror" 
                                id="password" name="password" placeholder="••••••••" type="password" required/>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <input class="w-5 h-5 rounded border-none bg-surface-variant text-primary focus:ring-primary/20 transition-all cursor-pointer" 
                            id="remember" name="remember" type="checkbox"/>
                        <label class="text-sm text-on-surface-variant font-medium select-none cursor-pointer" for="remember">{{ __('messages.auth.remember_me') }}</label>
                    </div>
                    <button class="w-full py-4 px-6 bg-gradient-to-br from-primary to-primary-container text-white font-bold rounded-lg tracking-tight hover:opacity-95 transition-all shadow-lg shadow-primary/10 flex justify-center items-center gap-2" type="submit">
                        <span>{{ __('messages.auth.login') }}</span>
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </button>
                </form>

                <div class="mt-10 pt-8 border-t border-outline-variant/10 text-center">
                    <p class="text-sm text-slate-500">
                        Don't have an account? 
                        <a class="text-primary font-bold hover:underline" href="#">Contact your Administrator</a>
                    </p>
                </div>
            </div>
            <div class="mt-8 px-4 opacity-60">
                <p class="text-[10px] uppercase tracking-[0.3em] font-bold text-on-surface-variant mb-1">Executive Directive</p>
                <p class="text-xs italic text-slate-500 font-serif">"Excellence is not an act, but a habit. Your data is the blueprint of your success."</p>
            </div>
        </div>
    </main>
    <footer class="w-full py-12 border-t border-slate-200/20 bg-slate-50 text-sm antialiased">
        <div class="flex flex-col md:flex-row justify-between items-center px-8 max-w-7xl mx-auto gap-6 md:gap-0">
            <div class="text-slate-500">
                © {{ date('Y') }} ExecutiveCRM Architectural Suite. All rights reserved.
            </div>
            <div class="flex items-center gap-8">
                <a class="text-slate-500 hover:text-sky-600 transition-colors" href="#">Privacy Policy</a>
                <a class="text-slate-500 hover:text-sky-600 transition-colors" href="#">Terms of Service</a>
                <a class="text-slate-500 hover:text-sky-600 transition-colors" href="#">Security</a>
                <a class="text-slate-500 hover:text-sky-600 transition-colors" href="#">Status</a>
            </div>
        </div>
    </footer>
    <div class="fixed top-0 right-0 -z-10 w-[500px] h-[500px] bg-primary-container/5 rounded-full blur-[120px] translate-x-1/2 -translate-y-1/2"></div>
    <div class="fixed bottom-0 left-0 -z-10 w-[400px] h-[400px] bg-secondary-container/10 rounded-full blur-[100px] -translate-x-1/3 translate-y-1/3"></div>
</body>
</html>
