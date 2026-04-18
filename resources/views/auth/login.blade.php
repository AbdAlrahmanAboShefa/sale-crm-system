<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="utf-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>{{ __('messages.auth.login') }} | SalesFlow</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
<style>
/* ═══════════════════════════════════════
   TOKENS (shared with landing)
   ═══════════════════════════════════════ */
:root {
  --bg:         #05070A;
  --bg-raised:  #0A0E14;
  --surface:    #0F1419;
  --surface-hi: #161D25;
  --border:     rgba(255,255,255,0.06);
  --border-hi:  rgba(255,255,255,0.10);
  --text:       #C8CDD5;
  --muted:      #525B68;
  --muted-hi:   #7A8494;
  --accent:     #00E89D;
  --accent-dim: rgba(0,232,157,0.08);
  --accent-mid: rgba(0,232,157,0.15);
  --accent-glow:rgba(0,232,157,0.25);
  --gold:       #FFB84D;
  --gold-dim:   rgba(255,184,77,0.10);
  --error:      #FF6B8A;
  --error-dim:  rgba(255,107,138,0.08);
  --white:      #FFFFFF;
  --radius:     12px;
  --radius-lg:  18px;
  --font-display: {{ app()->getLocale() === 'ar' ? "'Tajawal'" : "'Bricolage Grotesque'" }}, sans-serif;
  --font-body:    {{ app()->getLocale() === 'ar' ? "'Tajawal'" : "'Plus Jakarta Sans'" }}, sans-serif;
}
@if(app()->getLocale() === 'ar')
:root { --font-display: 'Tajawal', sans-serif; --font-body: 'Tajawal', sans-serif; }
@endif

*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
html{height:100%}

body {
  background:var(--bg);color:var(--text);
  font-family:var(--font-body);font-size:16px;
  min-height:100vh;display:grid;grid-template-columns:1fr 1fr;
  -webkit-font-smoothing:antialiased;
}
@if(app()->getLocale() === 'ar')
body{font-size:17px;line-height:1.85}
@endif

@media(max-width:900px) {
  body{grid-template-columns:1fr}
  .left-panel{display:none}
}

/* ═══════════════════════════════════════
   PARTICLES CANVAS
   ═══════════════════════════════════════ */
#particles-canvas {
  position:fixed;inset:0;z-index:0;pointer-events:none;opacity:.25;
}

/* ═══════════════════════════════════════
   LEFT PANEL
   ═══════════════════════════════════════ */
.left-panel {
  background:var(--bg-raised);
  border-inline-end:1px solid var(--border);
  display:flex;flex-direction:column;justify-content:space-between;
  padding:48px;position:relative;overflow:hidden;
}
.left-panel::before {
  content:'';position:absolute;inset:0;
  background-image:
    linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
    linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
  background-size:56px 56px;
  mask-image:radial-gradient(ellipse 80% 80% at 50% 50%, black 40%, transparent 100%);
  pointer-events:none;
}

/* Gradient orbs */
.left-panel::after {
  content:'';position:absolute;
  width:500px;height:500px;
  background:radial-gradient(circle, rgba(0,232,157,0.06), transparent 65%);
  bottom:-100px;right:-100px;pointer-events:none;
  filter:blur(60px);
  animation:orbFloat 20s ease-in-out infinite;
}
[dir="rtl"] .left-panel::after{right:auto;left:-100px}

@keyframes orbFloat {
  0%,100%{transform:translate(0,0) scale(1)}
  33%{transform:translate(20px,-30px) scale(1.05)}
  66%{transform:translate(-15px,20px) scale(.95)}
}

.panel-top,.panel-bottom{position:relative;z-index:1}

/* Brand */
.brand{display:flex;align-items:center;gap:10px;text-decoration:none;color:var(--white);margin-bottom:64px}
.brand-mark {
  width:36px;height:36px;
  background:linear-gradient(135deg, var(--accent), #00C483);
  border-radius:9px;display:grid;place-items:center;flex-shrink:0;
  box-shadow:0 4px 16px var(--accent-glow);
}
.brand-mark svg{width:18px;height:18px}
.brand-name{font-family:var(--font-display);font-weight:700;font-size:18px;letter-spacing:-.3px}

.panel-headline {
  font-family:var(--font-display);
  font-size:clamp(28px,2.8vw,42px);
  font-weight:800;color:var(--white);line-height:1.1;letter-spacing:-1.2px;
  margin-bottom:16px;
}
[dir="rtl"] .panel-headline{letter-spacing:0;line-height:1.3}
.panel-headline em {
  font-style:normal;
  background:linear-gradient(135deg, var(--accent), var(--gold));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
  background-clip:text;
}

.panel-sub {
  font-size:15px;color:var(--muted-hi);line-height:1.7;font-weight:300;
  max-width:340px;margin-bottom:48px;
}
[dir="rtl"] .panel-sub{font-weight:400}

.feature-list{display:flex;flex-direction:column;gap:14px}
.feature-item{display:flex;align-items:center;gap:12px;font-size:14px;color:var(--text)}
.feature-dot {
  width:28px;height:28px;flex-shrink:0;
  background:var(--accent-dim);border:1px solid rgba(0,232,157,.12);
  border-radius:8px;display:grid;place-items:center;
  transition:all .3s;
}
.feature-item:hover .feature-dot {
  background:var(--accent-mid);border-color:rgba(0,232,157,.25);
  box-shadow:0 4px 12px var(--accent-glow);
}
.feature-dot svg{width:13px;height:13px;stroke:var(--accent);fill:none;stroke-width:2.5;stroke-linecap:round;stroke-linejoin:round}

.stats-row {
  display:flex;gap:32px;padding-top:40px;border-top:1px solid var(--border);
}
.stat-n{font-family:var(--font-display);font-size:22px;font-weight:700;color:var(--white);letter-spacing:-1px}
.stat-l{font-size:11px;color:var(--muted);margin-top:2px}

/* ═══════════════════════════════════════
   RIGHT PANEL (FORM)
   ═══════════════════════════════════════ */
.right-panel {
  display:flex;flex-direction:column;justify-content:center;align-items:center;
  padding:48px 40px;background:var(--bg);position:relative;z-index:1;
}

.form-wrap {
  width:100%;max-width:400px;
  animation:fadeUp .7s cubic-bezier(.22,1,.36,1) both;
}
@keyframes fadeUp {
  from{opacity:0;transform:translateY(24px)}
  to{opacity:1;transform:translateY(0)}
}

/* Mobile brand */
.mobile-brand {
  display:none;align-items:center;gap:10px;text-decoration:none;color:var(--white);margin-bottom:36px;
}
@media(max-width:900px){.mobile-brand{display:flex}}
.mobile-brand-mark {
  width:32px;height:32px;background:linear-gradient(135deg,var(--accent),#00C483);
  border-radius:8px;display:grid;place-items:center;
  box-shadow:0 4px 12px var(--accent-glow);
}
.mobile-brand-mark svg{width:15px;height:15px}
.mobile-brand-name{font-family:var(--font-display);font-weight:700;font-size:16px}

.form-title {
  font-family:var(--font-display);
  font-size:28px;font-weight:800;color:var(--white);letter-spacing:-.8px;margin-bottom:6px;
}
[dir="rtl"] .form-title{letter-spacing:0}
.form-sub{font-size:14px;color:var(--muted-hi);margin-bottom:32px;font-weight:300}
[dir="rtl"] .form-sub{font-weight:400}

/* Error alert */
.alert-error {
  background:var(--error-dim);border:1px solid rgba(255,107,138,.15);
  border-radius:var(--radius);padding:14px 16px;margin-bottom:24px;
  font-size:13px;color:var(--error);
  animation:fadeUp .5s ease both;
}
.alert-error ul{padding-inline-start:16px;margin-top:6px}
.alert-error li{margin-top:3px}

/* Fields */
.field{margin-bottom:18px}
.field label {
  display:block;font-size:13px;font-weight:500;color:var(--muted-hi);
  margin-bottom:8px;letter-spacing:.1px;
}
.input-wrap{position:relative}
.input-icon {
  position:absolute;top:50%;transform:translateY(-50%);inset-inline-start:14px;
  display:grid;place-items:center;pointer-events:none;
}
.input-icon svg{width:16px;height:16px;stroke:var(--muted);fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}

.field input {
  width:100%;padding:14px 14px 14px 44px;
  background:var(--surface);border:1px solid var(--border-hi);border-radius:var(--radius);
  color:var(--text);font-family:var(--font-body);font-size:14px;
  transition:all .25s;outline:none;
}
[dir="rtl"] .field input{padding:14px 44px 14px 14px}
.field input::placeholder{color:var(--muted)}
.field input:focus {
  border-color:rgba(0,232,157,.4);
  box-shadow:0 0 0 3px var(--accent-dim), 0 4px 16px rgba(0,0,0,.2);
}
.field input.is-error{border-color:rgba(255,107,138,.4)}

/* Password toggle */
.pwd-toggle {
  position:absolute;top:50%;transform:translateY(-50%);inset-inline-end:14px;
  background:none;border:none;cursor:pointer;display:grid;place-items:center;padding:2px;
}
.pwd-toggle svg {
  width:16px;height:16px;stroke:var(--muted);fill:none;
  stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round;transition:stroke .2s;
}
.pwd-toggle:hover svg{stroke:var(--muted-hi)}

/* Remember row */
.remember-row {
  display:flex;align-items:center;gap:8px;margin-bottom:24px;
}
.remember-row input[type="checkbox"] {
  width:15px;height:15px;accent-color:var(--accent);cursor:pointer;
}
.remember-row label{font-size:13px;color:var(--muted-hi);cursor:pointer}

/* Submit */
.btn-submit {
  width:100%;padding:15px;
  background:linear-gradient(135deg, var(--accent), #00C483);
  color:var(--bg);border:none;border-radius:var(--radius);
  font-family:var(--font-display);font-weight:700;font-size:15px;
  cursor:pointer;display:flex;align-items:center;justify-content:center;gap:9px;
  transition:all .3s cubic-bezier(.22,1,.36,1);
  position:relative;overflow:hidden;
}
.btn-submit::after {
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg, rgba(255,255,255,.15), transparent);
  opacity:0;transition:opacity .3s;
}
.btn-submit:hover {
  transform:translateY(-2px);
  box-shadow:0 10px 32px var(--accent-glow);
}
.btn-submit:hover::after{opacity:1}
.btn-submit svg{width:15px;height:15px;transition:transform .25s}
.btn-submit:hover svg{transform:translateX(4px)}
[dir="rtl"] .btn-submit:hover svg{transform:translateX(-4px)}
[dir="rtl"] .btn-submit svg{transform:scaleX(-1)}

/* Form footer */
.form-footer {
  margin-top:28px;padding-top:24px;border-top:1px solid var(--border);text-align:center;
}
.form-footer p{font-size:13px;color:var(--muted-hi)}
.form-footer a{color:var(--accent);font-weight:500;text-decoration:none;transition:opacity .2s}
.form-footer a:hover{opacity:.7}

.form-links {
  margin-top:16px;display:flex;align-items:center;justify-content:center;gap:16px;
  font-size:13px;color:var(--muted);
}
.form-links a {
  color:var(--muted);text-decoration:none;display:flex;align-items:center;gap:5px;
  transition:color .2s;
}
.form-links a:hover{color:var(--muted-hi)}
.form-links a svg{width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}
.form-links-sep{color:var(--border-hi)}

.lang-switch {
  display:inline-flex;align-items:center;gap:6px;
  background:var(--surface-hi);border:1px solid var(--border-hi);
  color:var(--muted-hi);font-family:var(--font-body);font-size:12px;font-weight:500;
  padding:5px 12px;border-radius:7px;cursor:pointer;text-decoration:none;
  transition:all .2s;
}
.lang-switch:hover{color:var(--white);border-color:rgba(255,255,255,.2)}
.lang-switch svg{width:12px;height:12px;stroke:currentColor;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round}

/* ═══════════════════════════════════════
   REDUCED MOTION
   ═══════════════════════════════════════ */
@media(prefers-reduced-motion:reduce) {
  *,*::before,*::after{animation-duration:.01ms!important;transition-duration:.01ms!important}
}
</style>
</head>
<body>

<canvas id="particles-canvas"></canvas>

{{-- ─── LEFT PANEL ─── --}}
<div class="left-panel">
  <div class="panel-top">
    <a href="{{ route('landing') }}" class="brand">
      <div class="brand-mark">
        <svg viewBox="0 0 24 24" fill="none" stroke="#05070A" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>
        </svg>
      </div>
      <span class="brand-name">SalesFlow</span>
    </a>

    <h2 class="panel-headline">
      @if(app()->getLocale() === 'ar')
        أدر مبيعاتك<br>بكفاءة <em>أعلى</em>
      @else
        Manage your sales<br>pipeline <em>smarter</em>
      @endif
    </h2>
    <p class="panel-sub">
      @if(app()->getLocale() === 'ar')
        منصة CRM متعددة المستأجرين لفرق B2B — تتبع الصفقات، المتابعات، والأداء في مكان واحد.
      @else
        A multi-tenant CRM built for B2B teams — track deals, follow-ups, and performance in one place.
      @endif
    </p>

    <div class="feature-list">
      @php
        $features = app()->getLocale() === 'ar' ? [
          'إدارة الصفقات بلوحة Kanban',
          'تحليلات الأداء الفورية',
          'دعم كامل للغة العربية RTL',
        ] : [
          'Visual Kanban deal pipeline',
          'Real-time performance analytics',
          'Full Arabic RTL support',
        ];
      @endphp
      @foreach($features as $f)
      <div class="feature-item">
        <div class="feature-dot">
          <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        {{ $f }}
      </div>
      @endforeach
    </div>
  </div>

  <div class="panel-bottom">
    <div class="stats-row">
      <div><div class="stat-n">10K+</div><div class="stat-l">{{ app()->getLocale() === 'ar' ? 'مستخدم' : 'Users' }}</div></div>
      <div><div class="stat-n">99.9%</div><div class="stat-l">{{ app()->getLocale() === 'ar' ? 'وقت التشغيل' : 'Uptime' }}</div></div>
      <div><div class="stat-n">50+</div><div class="stat-l">{{ app()->getLocale() === 'ar' ? 'تكاملاً' : 'Integrations' }}</div></div>
    </div>
  </div>
</div>

{{-- ─── RIGHT PANEL ─── --}}
<div class="right-panel">
  <div class="form-wrap">

    <a href="{{ route('landing') }}" class="mobile-brand">
      <div class="mobile-brand-mark">
        <svg viewBox="0 0 24 24" fill="none" stroke="#05070A" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>
        </svg>
      </div>
      <span class="mobile-brand-name">SalesFlow</span>
    </a>

    <h1 class="form-title">{{ __('messages.auth.login') }}</h1>
    <p class="form-sub">
      {{ app()->getLocale() === 'ar' ? 'أهلاً بعودتك! أدخل بياناتك للمتابعة.' : 'Welcome back! Enter your credentials to continue.' }}
    </p>

    {{-- Errors --}}
    @if ($errors->any())
    <div class="alert-error">
      <strong>{{ app()->getLocale() === 'ar' ? 'فشل تسجيل الدخول' : 'Login failed' }}</strong>
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    @if(session('error'))
    <div class="alert-error">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf

      {{-- Email --}}
      <div class="field">
        <label for="email">{{ __('messages.auth.email') }}</label>
        <div class="input-wrap">
          <span class="input-icon">
            <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          </span>
          <input
            type="email" id="email" name="email"
            placeholder="{{ app()->getLocale() === 'ar' ? 'اسم@شركة.com' : 'name@company.com' }}"
            value="{{ old('email') }}"
            class="{{ $errors->has('email') ? 'is-error' : '' }}"
            required autofocus
          />
        </div>
      </div>

      {{-- Password --}}
      <div class="field">
        <label for="password">{{ __('messages.auth.password') }}</label>
        <div class="input-wrap">
          <span class="input-icon">
            <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          </span>
          <input
            type="password" id="password" name="password"
            placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل كلمة المرور' : 'Enter your password' }}"
            class="{{ $errors->has('password') ? 'is-error' : '' }}"
            required
          />
          <button type="button" class="pwd-toggle" onclick="togglePwd('password','eye1')" id="eye1-btn" aria-label="Toggle password">
            <svg id="eye1" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
      </div>

      {{-- Remember --}}
      <div class="remember-row">
        <input type="checkbox" id="remember" name="remember"/>
        <label for="remember">{{ __('messages.auth.remember_me') }}</label>
      </div>

      <button type="submit" class="btn-submit">
        {{ __('messages.auth.login') }}
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
      </button>
    </form>

    <div class="form-footer">
      <p>
        {{ app()->getLocale() === 'ar' ? 'ليس لديك حساب؟' : "Don't have an account?" }}
        <a href="{{ route('register') }}">{{ app()->getLocale() === 'ar' ? 'ابدأ تجربتك المجانية' : 'Start free trial' }}</a>
      </p>
      <div class="form-links">
        <a href="{{ route('landing') }}">
          <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          {{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}
        </a>
        <span class="form-links-sep">|</span>
        <a href="{{ route('language.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" class="lang-switch">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
          {{ app()->getLocale() === 'ar' ? 'EN' : 'عربي' }}
        </a>
      </div>
    </div>

  </div>
</div>

<script>
// ── Password toggle ──
function togglePwd(inputId, iconId) {
  const input = document.getElementById(inputId);
  const icon  = document.getElementById(iconId);
  const isHidden = input.type === 'password';
  input.type = isHidden ? 'text' : 'password';
  icon.innerHTML = isHidden
    ? '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>'
    : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
}

// ── Particles ──
(function() {
  const canvas = document.getElementById('particles-canvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  let w, h, particles = [];
  const PARTICLE_COUNT = 40;
  const MAX_DIST = 120;

  function resize() {
    w = canvas.width = window.innerWidth;
    h = canvas.height = window.innerHeight;
  }
  function create() {
    particles = [];
    for (let i = 0; i < PARTICLE_COUNT; i++) {
      particles.push({
        x: Math.random() * w, y: Math.random() * h,
        vx: (Math.random() - 0.5) * 0.25, vy: (Math.random() - 0.5) * 0.25,
        r: Math.random() * 1.2 + 0.5,
      });
    }
  }
  function draw() {
    ctx.clearRect(0, 0, w, h);
    for (let i = 0; i < particles.length; i++) {
      for (let j = i + 1; j < particles.length; j++) {
        const dx = particles[i].x - particles[j].x;
        const dy = particles[i].y - particles[j].y;
        const dist = Math.sqrt(dx*dx + dy*dy);
        if (dist < MAX_DIST) {
          ctx.beginPath();
          ctx.strokeStyle = `rgba(0,232,157,${0.1 * (1 - dist/MAX_DIST)})`;
          ctx.lineWidth = 0.5;
          ctx.moveTo(particles[i].x, particles[i].y);
          ctx.lineTo(particles[j].x, particles[j].y);
          ctx.stroke();
        }
      }
    }
    particles.forEach(p => {
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI*2);
      ctx.fillStyle = 'rgba(0,232,157,0.2)';
      ctx.fill();
      p.x += p.vx; p.y += p.vy;
      if (p.x < 0 || p.x > w) p.vx *= -1;
      if (p.y < 0 || p.y > h) p.vy *= -1;
    });
    requestAnimationFrame(draw);
  }
  resize(); create(); draw();
  window.addEventListener('resize', () => { resize(); create(); });
})();
</script>
</body>
</html>