<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SalesFlow — B2B CRM</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400;12..96,500;12..96,600;12..96,700;12..96,800&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
<style>
/* ═══════════════════════════════════════
   DESIGN TOKENS
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
  --rose:       #FF6B8A;
  --rose-dim:   rgba(255,107,138,0.10);
  --white:      #FFFFFF;
  --radius:     12px;
  --radius-lg:  18px;
  --font-display: {{ app()->getLocale() === 'ar' ? "'Tajawal'" : "'Bricolage Grotesque'" }}, sans-serif;
  --font-body:    {{ app()->getLocale() === 'ar' ? "'Tajawal'" : "'Plus Jakarta Sans'" }}, sans-serif;
}

@if(app()->getLocale() === 'ar')
:root { --font-display: 'Tajawal', sans-serif; --font-body: 'Tajawal', sans-serif; }
@endif

/* ═══════════════════════════════════════
   RESET & BASE
   ═══════════════════════════════════════ */
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth;overflow-x:hidden}

body {
  background:var(--bg);
  color:var(--text);
  font-family:var(--font-body);
  font-size:16px;line-height:1.65;
  overflow-x:hidden;
  -webkit-font-smoothing:antialiased;
}
@if(app()->getLocale() === 'ar')
body{font-size:17px;line-height:1.85}
@endif

a{text-decoration:none;color:inherit}
button{font-family:inherit;cursor:pointer}

/* Scrollbar */
::-webkit-scrollbar{width:5px}
::-webkit-scrollbar-track{background:var(--bg)}
::-webkit-scrollbar-thumb{background:var(--surface-hi);border-radius:3px}
::-webkit-scrollbar-thumb:hover{background:var(--muted)}

/* Selection */
::selection{background:var(--accent-mid);color:var(--white)}

/* ═══════════════════════════════════════
   ANIMATED BACKGROUND
   ═══════════════════════════════════════ */
#particles-canvas {
  position:fixed;inset:0;z-index:0;pointer-events:none;opacity:.35;
}

/* ═══════════════════════════════════════
   GLASS-MORPHISM UTILITIES
   ═══════════════════════════════════════ */
.glass {
  background:rgba(15,20,25,0.6);
  backdrop-filter:blur(20px) saturate(1.4);
  -webkit-backdrop-filter:blur(20px) saturate(1.4);
}
.glass-border {
  border:1px solid var(--border);
  box-shadow:inset 0 1px 0 rgba(255,255,255,0.04);
}

/* ═══════════════════════════════════════
   SCROLL REVEAL
   ═══════════════════════════════════════ */
.reveal {
  opacity:0;transform:translateY(32px);
  transition:opacity .7s cubic-bezier(.22,1,.36,1), transform .7s cubic-bezier(.22,1,.36,1);
}
.reveal.visible { opacity:1;transform:translateY(0) }
.reveal-delay-1{transition-delay:.08s}
.reveal-delay-2{transition-delay:.16s}
.reveal-delay-3{transition-delay:.24s}
.reveal-delay-4{transition-delay:.32s}
.reveal-delay-5{transition-delay:.40s}

/* ═══════════════════════════════════════
   NAV
   ═══════════════════════════════════════ */
nav {
  position:fixed;top:0;left:0;right:0;z-index:200;
  display:flex;align-items:center;justify-content:space-between;
  padding:16px 56px;
  transition:all .35s ease;
}
nav.scrolled {
  background:rgba(5,7,10,0.82);
  backdrop-filter:blur(24px) saturate(1.5);
  border-bottom:1px solid var(--border);
}

.logo{display:flex;align-items:center;gap:10px}
.logo-mark {
  width:34px;height:34px;
  background:linear-gradient(135deg, var(--accent), #00C483);
  border-radius:9px;display:grid;place-items:center;flex-shrink:0;
  box-shadow:0 4px 16px var(--accent-glow);
}
.logo-mark svg{width:17px;height:17px}
.logo-name{font-family:var(--font-display);font-weight:700;font-size:17px;color:var(--white);letter-spacing:-0.2px}

.nav-links{display:flex;gap:32px;list-style:none}
.nav-links a{
  color:var(--muted-hi);font-size:14px;font-weight:500;
  transition:color .2s;position:relative;
}
.nav-links a::after {
  content:'';position:absolute;bottom:-4px;left:0;right:0;height:2px;
  background:var(--accent);border-radius:1px;
  transform:scaleX(0);transform-origin:center;transition:transform .25s ease;
}
.nav-links a:hover{color:var(--white)}
.nav-links a:hover::after{transform:scaleX(1)}

.nav-cta{display:flex;align-items:center;gap:12px}

.lang-btn {
  display:flex;align-items:center;gap:6px;
  background:rgba(255,255,255,0.04);border:1px solid var(--border-hi);
  color:var(--muted-hi);font-family:var(--font-body);font-size:13px;font-weight:500;
  padding:7px 13px;border-radius:8px;
  transition:all .2s;
}
.lang-btn:hover{color:var(--white);border-color:rgba(255,255,255,0.22);background:rgba(255,255,255,0.07)}
.lang-btn svg{width:14px;height:14px;opacity:.5}

.btn-link{background:none;border:none;color:var(--muted-hi);font-family:var(--font-body);font-size:14px;font-weight:500;transition:color .2s}
.btn-link:hover{color:var(--white)}

.btn-nav {
  background:var(--accent);color:var(--bg);
  border:none;padding:9px 20px;border-radius:9px;
  font-family:var(--font-display);font-weight:700;font-size:14px;
  transition:all .25s;position:relative;overflow:hidden;
}
.btn-nav::before {
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg, rgba(255,255,255,0.2), transparent);
  opacity:0;transition:opacity .25s;
}
.btn-nav:hover{transform:translateY(-1px);box-shadow:0 8px 24px var(--accent-glow)}
.btn-nav:hover::before{opacity:1}

/* ═══════════════════════════════════════
   HERO
   ═══════════════════════════════════════ */
.hero {
  position:relative;min-height:100vh;
  display:grid;grid-template-columns:1fr 1fr;
  align-items:center;gap:48px;
  padding:140px 56px 80px;overflow:hidden;
}

/* Ambient orbs */
.hero-orb {
  position:absolute;border-radius:50%;filter:blur(80px);pointer-events:none;
  animation:orbFloat 20s ease-in-out infinite;
}
.hero-orb-1 {
  width:500px;height:500px;
  background:radial-gradient(circle, rgba(0,232,157,0.08), transparent 70%);
  top:-100px;right:-80px;
}
.hero-orb-2 {
  width:400px;height:400px;
  background:radial-gradient(circle, rgba(255,184,77,0.06), transparent 70%);
  bottom:-50px;left:-60px;
  animation-delay:-8s;
}
[dir="rtl"] .hero-orb-1{right:auto;left:-80px}
[dir="rtl"] .hero-orb-2{left:auto;right:-60px}

@keyframes orbFloat {
  0%,100%{transform:translate(0,0) scale(1)}
  33%{transform:translate(20px,-30px) scale(1.05)}
  66%{transform:translate(-15px,20px) scale(0.95)}
}

.hero-left{position:relative;z-index:1}

/* Eyebrow */
.hero-eyebrow {
  display:inline-flex;align-items:center;gap:8px;
  background:var(--accent-dim);border:1px solid rgba(0,232,157,0.15);
  color:var(--accent);font-size:11px;font-weight:600;
  letter-spacing:.8px;text-transform:uppercase;
  padding:5px 14px;border-radius:100px;margin-bottom:28px;
}
[dir="rtl"] .hero-eyebrow{letter-spacing:0;font-size:12px}
.hero-eyebrow-dot {
  width:5px;height:5px;border-radius:50%;background:var(--accent);
  animation:pulse 2s ease-in-out infinite;
}
@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.3;transform:scale(.8)}}

/* H1 */
.hero-h1 {
  font-family:var(--font-display);
  font-size:clamp(42px,5vw,72px);
  font-weight:800;line-height:1.05;
  letter-spacing:-2px;color:var(--white);
  margin-bottom:22px;
}
[dir="rtl"] .hero-h1{letter-spacing:0;line-height:1.25}
.hero-h1 em {
  font-style:normal;
  background:linear-gradient(135deg, var(--accent), var(--gold));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;
  background-clip:text;
}

.hero-p {
  font-size:16px;color:var(--muted-hi);line-height:1.75;
  max-width:440px;font-weight:300;margin-bottom:36px;
}
[dir="rtl"] .hero-p{font-weight:400}

/* Buttons */
.hero-btns{display:flex;gap:12px;align-items:center;flex-wrap:wrap}

.btn-hero {
  background:linear-gradient(135deg, var(--accent), #00C483);
  color:var(--bg);border:none;padding:15px 32px;border-radius:var(--radius);
  font-family:var(--font-display);font-weight:700;font-size:15px;
  display:flex;align-items:center;gap:9px;
  transition:all .3s cubic-bezier(.22,1,.36,1);
  position:relative;overflow:hidden;
}
.btn-hero::after {
  content:'';position:absolute;inset:0;
  background:linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
  opacity:0;transition:opacity .3s;
}
.btn-hero:hover{transform:translateY(-2px);box-shadow:0 12px 36px var(--accent-glow)}
.btn-hero:hover::after{opacity:1}
.btn-hero svg{width:15px;height:15px;transition:transform .25s}
.btn-hero:hover svg{transform:translateX(4px)}
[dir="rtl"] .btn-hero:hover svg{transform:translateX(-4px)}
[dir="rtl"] .btn-hero svg{transform:scaleX(-1)}

.btn-ghost {
  background:transparent;color:var(--text);
  border:1px solid var(--border-hi);padding:15px 28px;border-radius:var(--radius);
  font-family:var(--font-body);font-size:15px;font-weight:500;
  transition:all .25s;
}
.btn-ghost:hover{border-color:rgba(255,255,255,0.2);background:rgba(255,255,255,0.04);color:var(--white)}

/* Stats */
.hero-stats {
  display:flex;gap:40px;margin-top:48px;
  padding-top:36px;border-top:1px solid var(--border);
}
.stat-n{font-family:var(--font-display);font-size:28px;font-weight:700;color:var(--white);letter-spacing:-1px}
.stat-l{font-size:12px;color:var(--muted);margin-top:3px}

/* ═══════════════════════════════════════
   MOCKUP
   ═══════════════════════════════════════ */
.hero-right{position:relative;z-index:1}
.mockup-wrap{position:relative}

/* Floating notification */
.notif {
  position:absolute;top:-16px;right:-16px;z-index:5;
  background:rgba(255,255,255,0.95);backdrop-filter:blur(12px);
  border-radius:14px;padding:12px 16px;
  display:flex;align-items:center;gap:10px;
  box-shadow:0 20px 50px rgba(0,0,0,.45);
  opacity:0;
}
[dir="rtl"] .notif{right:auto;left:-16px}
.notif-icon {
  width:28px;height:28px;background:linear-gradient(135deg,var(--accent),#00C483);
  border-radius:8px;display:grid;place-items:center;flex-shrink:0;
}
.notif-icon svg{width:14px;height:14px}
.notif-t{font-weight:700;font-size:12px;color:#111}
.notif-s{font-size:11px;color:#888}

/* Mockup card */
.mockup {
  background:var(--surface);border:1px solid var(--border-hi);
  border-radius:var(--radius-lg);overflow:hidden;
  box-shadow:
    0 40px 80px rgba(0,0,0,.5),
    0 0 0 1px rgba(255,255,255,.03),
    inset 0 1px 0 rgba(255,255,255,.04);
}
.mockup-bar {
  background:var(--surface-hi);padding:13px 18px;
  display:flex;align-items:center;gap:8px;
  border-bottom:1px solid var(--border);
}
.dot{width:10px;height:10px;border-radius:50%}
.dr{background:#FF5F57}.dy{background:#FFBD2E}.dg{background:#28C840}
.mockup-tab{margin-inline-start:12px;font-size:12px;color:var(--muted);background:rgba(255,255,255,.04);padding:3px 11px;border-radius:6px}
.mockup-body{padding:22px}

.pipe-row{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:10px}
.pipe-lbl{font-size:12px;color:var(--muted)}
.pipe-val{font-family:var(--font-display);font-size:20px;font-weight:700;color:var(--accent)}

.track{height:5px;background:rgba(255,255,255,.06);border-radius:100px;overflow:hidden;margin-bottom:18px}
.fill{height:100%;width:0;background:linear-gradient(90deg,var(--accent),#00A86B);border-radius:100px;transition:width 1.6s 1.2s cubic-bezier(.22,1,.36,1)}

.m-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px}
.m-box {
  background:var(--surface-hi);border:1px solid var(--border);border-radius:11px;padding:14px;
  transition:border-color .25s;
}
.m-box:hover{border-color:rgba(0,232,157,.15)}
.m-lbl{font-size:11px;color:var(--muted);margin-bottom:5px}
.m-val{font-family:var(--font-display);font-size:26px;font-weight:700;color:var(--white);letter-spacing:-1px}
.m-delta{font-size:11px;color:var(--accent);margin-top:3px;font-weight:500}

.leads{display:flex;flex-direction:column;gap:8px}
.lead {
  display:flex;align-items:center;gap:11px;
  padding:11px 13px;background:var(--surface-hi);
  border:1px solid var(--border);border-radius:10px;
  transition:all .25s;
}
.lead:hover{border-color:rgba(0,232,157,.18);transform:translateX(3px)}
[dir="rtl"] .lead:hover{transform:translateX(-3px)}
.av{width:32px;height:32px;border-radius:50%;display:grid;place-items:center;font-size:11px;font-weight:700;color:var(--bg);flex-shrink:0}
.lead-info{flex:1}
.lead-name{font-size:13px;font-weight:600;color:var(--white)}
.lead-sub{font-size:11px;color:var(--muted);margin-top:1px}
.badge{font-size:10px;font-weight:700;letter-spacing:.5px;text-transform:uppercase;padding:2px 8px;border-radius:100px}
.b-won{background:var(--accent-dim);color:var(--accent)}
.b-new{background:var(--gold-dim);color:var(--gold)}
.b-prog{background:rgba(255,189,46,.10);color:#FFBD2E}

/* ═══════════════════════════════════════
   SECTIONS
   ═══════════════════════════════════════ */
.sec{padding:100px 56px;position:relative;z-index:1}
.sec-label {
  display:inline-flex;align-items:center;gap:8px;
  font-size:11px;font-weight:600;letter-spacing:1px;text-transform:uppercase;color:var(--accent);
  margin-bottom:14px;
}
.sec-label::before {
  content:'';width:20px;height:2px;background:var(--accent);border-radius:1px;
}
[dir="rtl"] .sec-label{letter-spacing:0;font-size:12px}

.sec-h2 {
  font-family:var(--font-display);
  font-size:clamp(32px,3.8vw,52px);
  font-weight:800;letter-spacing:-1.5px;color:var(--white);line-height:1.08;
}
[dir="rtl"] .sec-h2{letter-spacing:0;line-height:1.3}

.sec-sub{font-size:16px;color:var(--muted-hi);margin-top:14px;font-weight:300;max-width:520px}
[dir="rtl"] .sec-sub{font-weight:400}

/* Section divider */
.sec-divider {
  height:1px;
  background:linear-gradient(90deg, transparent, var(--border-hi), transparent);
  margin:0 56px;
}

/* ═══════════════════════════════════════
   FEATURES
   ═══════════════════════════════════════ */
.features{background:var(--bg-raised)}
.features-inner{max-width:1100px;margin:0 auto;text-align:center}
.features-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-top:56px;text-align:start}

.feat-card {
  background:var(--bg);border:1px solid var(--border);
  border-radius:var(--radius-lg);padding:30px;
  transition:all .35s cubic-bezier(.22,1,.36,1);
  position:relative;overflow:hidden;
}
.feat-card::before {
  content:'';position:absolute;inset:0;
  background:radial-gradient(circle at 0% 0%, var(--accent-dim), transparent 60%);
  opacity:0;transition:opacity .35s;
}
[dir="rtl"] .feat-card::before{background:radial-gradient(circle at 100% 0%, var(--accent-dim), transparent 60%)}
.feat-card:hover{border-color:rgba(0,232,157,.18);transform:translateY(-4px);box-shadow:0 20px 50px rgba(0,0,0,.3)}
.feat-card:hover::before{opacity:1}

.feat-icon {
  width:46px;height:46px;
  background:linear-gradient(135deg, var(--accent-dim), rgba(0,232,157,.03));
  border:1px solid rgba(0,232,157,.12);
  border-radius:11px;display:grid;place-items:center;margin-bottom:20px;
  transition:all .35s;
}
.feat-card:hover .feat-icon {
  background:linear-gradient(135deg, var(--accent-mid), var(--accent-dim));
  border-color:rgba(0,232,157,.25);
  box-shadow:0 8px 24px var(--accent-glow);
}
.feat-icon svg{width:20px;height:20px;stroke:var(--accent);fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.feat-h{font-family:var(--font-display);font-size:17px;font-weight:700;color:var(--white);margin-bottom:10px;letter-spacing:-.3px}
[dir="rtl"] .feat-h{font-size:18px;letter-spacing:0}
.feat-p{font-size:14px;color:var(--muted-hi);line-height:1.7}
[dir="rtl"] .feat-p{font-size:15px}

/* ═══════════════════════════════════════
   HOW IT WORKS
   ═══════════════════════════════════════ */
.how{background:var(--bg)}
.how-inner{max-width:900px;margin:0 auto;text-align:center}

.how-steps{display:grid;grid-template-columns:repeat(3,1fr);gap:0;margin-top:56px;position:relative}
.how-steps::before {
  content:'';position:absolute;top:30px;
  inset-inline:calc(33.33% - 28px);
  height:1px;
  background:linear-gradient(90deg, transparent, var(--accent-glow), transparent);
}

.step{text-align:center;padding:0 20px}
.step-num {
  width:60px;height:60px;
  background:var(--surface);border:1px solid var(--border-hi);
  border-radius:50%;display:grid;place-items:center;
  margin:0 auto 22px;font-family:var(--font-display);
  font-size:18px;font-weight:700;color:var(--white);
  position:relative;z-index:1;
  transition:all .35s cubic-bezier(.22,1,.36,1);
}
.step-num::after {
  content:'';position:absolute;inset:-3px;border-radius:50%;
  background:linear-gradient(135deg, var(--accent), var(--gold));
  opacity:0;z-index:-1;transition:opacity .35s;
}
.step:hover .step-num{color:var(--accent);border-color:rgba(0,232,157,.3)}
.step:hover .step-num::after{opacity:1}

.step-h{font-family:var(--font-display);font-size:17px;font-weight:700;color:var(--white);margin-bottom:10px;letter-spacing:-.3px}
[dir="rtl"] .step-h{font-size:18px;letter-spacing:0}
.step-p{font-size:14px;color:var(--muted-hi);line-height:1.7}
[dir="rtl"] .step-p{font-size:15px}

/* ═══════════════════════════════════════
   PRICING
   ═══════════════════════════════════════ */
.pricing{background:var(--bg-raised)}
.pricing-inner{max-width:960px;margin:0 auto;text-align:center}
.pricing-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:18px;margin-top:56px;align-items:start;text-align:start}

.p-card {
  background:var(--bg);border:1px solid var(--border);
  border-radius:var(--radius-lg);padding:30px;
  position:relative;transition:all .35s cubic-bezier(.22,1,.36,1);
  overflow:hidden;
}
.p-card::before {
  content:'';position:absolute;top:0;left:0;right:0;height:3px;
  background:linear-gradient(90deg, transparent, var(--accent), transparent);
  opacity:0;transition:opacity .35s;
}
.p-card:hover{border-color:var(--border-hi);transform:translateY(-4px);box-shadow:0 24px 50px rgba(0,0,0,.35)}
.p-card:hover::before{opacity:1}

.p-card.hot {
  background:var(--surface-hi);border-color:rgba(0,232,157,.2);
  box-shadow:0 0 0 1px rgba(0,232,157,.06),0 24px 50px rgba(0,0,0,.35);
  transform:translateY(-6px);
}
.p-card.hot::before{opacity:1}
.p-card.hot:hover{transform:translateY(-10px);border-color:rgba(0,232,157,.35)}

.p-popular {
  position:absolute;top:-13px;left:50%;transform:translateX(-50%);
  background:linear-gradient(135deg, var(--accent), #00C483);
  color:var(--bg);font-size:10px;font-weight:700;letter-spacing:.8px;
  text-transform:uppercase;padding:4px 14px;border-radius:100px;white-space:nowrap;
}
[dir="rtl"] .p-popular{letter-spacing:0;font-size:11px}

.p-name{font-family:var(--font-display);font-size:18px;font-weight:700;color:var(--white);letter-spacing:-.3px}
[dir="rtl"] .p-name{font-size:20px;letter-spacing:0}
.p-desc{font-size:13px;color:var(--muted);margin-top:4px;margin-bottom:22px}
.p-price{display:flex;align-items:flex-end;gap:3px;margin-bottom:24px;padding-bottom:24px;border-bottom:1px solid var(--border)}
.p-cur{font-family:var(--font-display);font-size:20px;font-weight:600;color:var(--muted-hi);margin-bottom:5px}
.p-amt{font-family:var(--font-display);font-size:52px;font-weight:800;color:var(--white);line-height:1;letter-spacing:-3px}
.p-card.hot .p-amt{color:var(--accent)}
.p-per{font-size:13px;color:var(--muted);margin-bottom:7px}

.p-features{list-style:none;display:flex;flex-direction:column;gap:11px;margin-bottom:28px}
.p-features li{display:flex;align-items:center;gap:9px;font-size:14px;color:var(--text)}
[dir="rtl"] .p-features li{font-size:15px}
.chk {
  width:18px;height:18px;border-radius:50%;
  background:linear-gradient(135deg, var(--accent-dim), rgba(0,232,157,.03));
  display:grid;place-items:center;flex-shrink:0;
}
.chk svg{width:9px;height:9px}

.btn-p {
  width:100%;padding:14px;border-radius:10px;
  font-family:var(--font-display);font-weight:700;font-size:14px;
  transition:all .3s cubic-bezier(.22,1,.36,1);
}
.btn-outline {
  background:transparent;border:1px solid var(--border-hi);color:var(--text);
}
.btn-outline:hover{border-color:rgba(255,255,255,.22);background:rgba(255,255,255,.04);color:var(--white)}
.btn-filled {
  background:linear-gradient(135deg, var(--accent), #00C483);
  border:none;color:var(--bg);
  box-shadow:0 4px 16px var(--accent-glow);
}
.btn-filled:hover{transform:translateY(-1px);box-shadow:0 10px 28px var(--accent-glow)}

/* ═══════════════════════════════════════
   CTA
   ═══════════════════════════════════════ */
.cta-sec {
  background:var(--bg);padding:100px 56px;
  position:relative;overflow:hidden;
}
.cta-glow {
  position:absolute;width:600px;height:400px;
  background:radial-gradient(ellipse, rgba(0,232,157,.08), transparent 65%);
  left:50%;top:50%;transform:translate(-50%,-50%);pointer-events:none;
  filter:blur(40px);
}
.cta-inner{max-width:640px;margin:0 auto;text-align:center;position:relative;z-index:1}
.cta-h{
  font-family:var(--font-display);
  font-size:clamp(32px,3.8vw,52px);font-weight:800;
  letter-spacing:-1.5px;color:var(--white);line-height:1.08;margin-bottom:16px;
}
[dir="rtl"] .cta-h{letter-spacing:0;line-height:1.3}
.cta-sub{font-size:16px;color:var(--muted-hi);font-weight:300;margin-bottom:36px}
[dir="rtl"] .cta-sub{font-weight:400}
.cta-btns{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}

/* ═══════════════════════════════════════
   FOOTER
   ═══════════════════════════════════════ */
footer {
  background:var(--bg-raised);border-top:1px solid var(--border);
  padding:56px 56px 32px;position:relative;z-index:1;
}
.foot-top{display:grid;grid-template-columns:1.6fr 1fr 1fr 1fr;gap:40px;margin-bottom:48px}
.foot-brand p{font-size:14px;color:var(--muted);margin-top:12px;line-height:1.7;max-width:240px}
.foot-col h5 {
  font-family:var(--font-display);font-size:14px;font-weight:600;color:var(--white);margin-bottom:14px;
}
[dir="rtl"] .foot-col h5{font-size:16px}
.foot-col ul{list-style:none;display:flex;flex-direction:column;gap:10px}
.foot-col a{text-decoration:none;font-size:14px;color:var(--muted);transition:color .2s}
.foot-col a:hover{color:var(--white)}
.foot-bottom {
  border-top:1px solid var(--border);padding-top:24px;
  display:flex;justify-content:space-between;align-items:center;
  font-size:13px;color:var(--muted);
}

/* ═══════════════════════════════════════
   KEYFRAMES
   ═══════════════════════════════════════ */
@keyframes fadeUp {
  from{opacity:0;transform:translateY(30px)}
  to{opacity:1;transform:translateY(0)}
}
@keyframes floatIn {
  from{opacity:0;transform:translateY(10px) scale(.94)}
  to{opacity:1;transform:translateY(0) scale(1)}
}
@keyframes hoverFloat {
  0%,100%{transform:translateY(0)}
  50%{transform:translateY(-6px)}
}

/* Hero entry animations */
.hero-eyebrow{animation:fadeUp .7s .05s ease both}
.hero-h1{animation:fadeUp .7s .12s ease both}
.hero-p{animation:fadeUp .7s .2s ease both}
.hero-btns{animation:fadeUp .7s .28s ease both}
.hero-stats{animation:fadeUp .7s .36s ease both}
.hero-right{animation:fadeUp .8s .22s ease both}
.notif{animation:floatIn .6s 1.4s ease both, hoverFloat 4s 2s ease-in-out infinite}

/* ═══════════════════════════════════════
   RESPONSIVE
   ═══════════════════════════════════════ */
@media(max-width:1024px) {
  .hero{grid-template-columns:1fr;gap:40px;padding:120px 32px 60px}
  .hero-right{max-width:520px}
  nav{padding:14px 32px}
  .sec{padding:80px 32px}
  .features-grid{grid-template-columns:1fr 1fr}
  .pricing-grid{grid-template-columns:1fr 1fr}
  .foot-top{grid-template-columns:1fr 1fr}
}
@media(max-width:768px) {
  .nav-links{display:none}
  nav{padding:14px 20px}
  .hero{padding:110px 20px 50px}
  .sec{padding:60px 20px}
  .features-grid,.pricing-grid,.how-steps{grid-template-columns:1fr}
  .hero-stats{gap:24px;flex-wrap:wrap}
  .foot-top{grid-template-columns:1fr}
  .foot-bottom{flex-direction:column;gap:8px;text-align:center}
}
@media(max-width:480px) {
  .hero-h1{font-size:34px;letter-spacing:-1px}
  .hero-btns{flex-direction:column;width:100%}
  .btn-hero,.btn-ghost{width:100%;justify-content:center}
  .stat-n{font-size:22px}
}

/* ═══════════════════════════════════════
   REDUCED MOTION
   ═══════════════════════════════════════ */
@media(prefers-reduced-motion:reduce) {
  *,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;transition-duration:.01ms!important}
  .reveal{opacity:1;transform:none}
}
</style>
</head>
<body>

{{-- Animated background --}}
<canvas id="particles-canvas"></canvas>

{{-- ─── NAV ─── --}}
<nav class="glass">
  <a href="#" class="logo">
    <div class="logo-mark">
      <svg viewBox="0 0 24 24" fill="none" stroke="#05070A" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/>
      </svg>
    </div>
    <span class="logo-name">SalesFlow</span>
  </a>

  <ul class="nav-links">
    <li><a href="#features">{{ __('messages.landing.nav_features') }}</a></li>
    <li><a href="#pricing">{{ __('messages.landing.nav_pricing') }}</a></li>
    <li><a href="#how-it-works">{{ __('messages.landing.nav_how') }}</a></li>
  </ul>

  <div class="nav-cta">
    <a href="{{ route('language.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}" class="lang-btn">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
      {{ app()->getLocale() === 'ar' ? 'EN' : 'عربي' }}
    </a>
    <a href="{{ route('login') }}" class="btn-link">{{ __('messages.landing.nav_login') }}</a>
    <a href="{{ route('login') }}" class="btn-nav">{{ __('messages.landing.nav_get_started') }}</a>
  </div>
</nav>

{{-- ─── HERO ─── --}}
<section class="hero">
  <div class="hero-orb hero-orb-1"></div>
  <div class="hero-orb hero-orb-2"></div>

  <div class="hero-left">
    <div class="hero-eyebrow">
      <span class="hero-eyebrow-dot"></span>
      {{ __('messages.landing.hero_badge') }}
    </div>
    <h1 class="hero-h1">
      {{ __('messages.landing.hero_h1_1') }}<br>
      <em>{{ __('messages.landing.hero_h1_2') }}</em><br>
      {{ __('messages.landing.hero_h1_3') }}
    </h1>
    <p class="hero-p">{{ __('messages.landing.hero_p') }}</p>
    <div class="hero-btns">
      <a href="{{ route('login') }}" class="btn-hero">
        {{ __('messages.landing.hero_trial') }}
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
      </a>
      <a href="#how-it-works" class="btn-ghost">{{ __('messages.landing.hero_how') }}</a>
    </div>
    <div class="hero-stats">
      <div><div class="stat-n">10K+</div><div class="stat-l">{{ __('messages.landing.stat_users') }}</div></div>
      <div><div class="stat-n">99.9%</div><div class="stat-l">{{ __('messages.landing.stat_uptime') }}</div></div>
      <div><div class="stat-n">50+</div><div class="stat-l">{{ __('messages.landing.stat_integrations') }}</div></div>
    </div>
  </div>

  <div class="hero-right">
    <div class="mockup-wrap">
      <div class="notif">
        <div class="notif-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="#05070A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div>
          <div class="notif-t">{{ __('messages.landing.notif_title') }}</div>
          <div class="notif-s">{{ __('messages.landing.notif_sub') }}</div>
        </div>
      </div>
      <div class="mockup glass glass-border">
        <div class="mockup-bar">
          <div class="dot dr"></div><div class="dot dy"></div><div class="dot dg"></div>
          <div class="mockup-tab">{{ __('messages.landing.mock_tab') }}</div>
        </div>
        <div class="mockup-body">
          <div class="pipe-row">
            <span class="pipe-lbl">{{ __('messages.landing.mock_pipeline') }}</span>
            <span class="pipe-val">$1.2M</span>
          </div>
          <div class="track"><div class="fill" id="pipeBar"></div></div>
          <div class="m-grid">
            <div class="m-box">
              <div class="m-lbl">{{ __('messages.landing.mock_deals_won') }}</div>
              <div class="m-val">47</div>
              <div class="m-delta">{{ __('messages.landing.mock_delta_deals') }}</div>
            </div>
            <div class="m-box">
              <div class="m-lbl">{{ __('messages.landing.mock_conversion') }}</div>
              <div class="m-val">32%</div>
              <div class="m-delta">{{ __('messages.landing.mock_delta_conv') }}</div>
            </div>
          </div>
          <div class="leads">
            <div class="lead">
              <div class="av" style="background:var(--accent)">JD</div>
              <div class="lead-info">
                <div class="lead-name">John Doe</div>
                <div class="lead-sub">Closed Deal · $45K</div>
              </div>
              <span class="badge b-won">{{ __('messages.landing.badge_won') }}</span>
            </div>
            <div class="lead">
              <div class="av" style="background:var(--gold)">SM</div>
              <div class="lead-info">
                <div class="lead-name">Sarah Miller</div>
                <div class="lead-sub">New Lead · Inbound</div>
              </div>
              <span class="badge b-new">{{ __('messages.landing.badge_new') }}</span>
            </div>
            <div class="lead">
              <div class="av" style="background:#FFBD2E">RK</div>
              <div class="lead-info">
                <div class="lead-name">Ryan Kim</div>
                <div class="lead-sub">Proposal · $18K</div>
              </div>
              <span class="badge b-prog">{{ __('messages.landing.badge_progress') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ─── FEATURES ─── --}}
<section class="sec features" id="features">
  <div class="features-inner">
    <span class="sec-label reveal">{{ __('messages.landing.feat_label') }}</span>
    <h2 class="sec-h2 reveal reveal-delay-1">{{ __('messages.landing.feat_h2') }}</h2>
    <p class="sec-sub reveal reveal-delay-2" style="margin:0 auto">{{ __('messages.landing.feat_sub') }}</p>
    <div class="features-grid">
      @foreach([
        ['icon' => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 17.5h7M17.5 14v7"/>', 'h' => 'feat_1_h', 'p' => 'feat_1_p'],
        ['icon' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>', 'h' => 'feat_2_h', 'p' => 'feat_2_p'],
        ['icon' => '<path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/>', 'h' => 'feat_3_h', 'p' => 'feat_3_p'],
        ['icon' => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>', 'h' => 'feat_4_h', 'p' => 'feat_4_p'],
        ['icon' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>', 'h' => 'feat_5_h', 'p' => 'feat_5_p'],
        ['icon' => '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>', 'h' => 'feat_6_h', 'p' => 'feat_6_p'],
      ] as $i => $feat)
      <div class="feat-card reveal reveal-delay-{{ min($i + 1, 5) }}">
        <div class="feat-icon">
          <svg viewBox="0 0 24 24">{!! $feat['icon'] !!}</svg>
        </div>
        <div class="feat-h">{{ __('messages.landing.' . $feat['h']) }}</div>
        <p class="feat-p">{{ __('messages.landing.' . $feat['p']) }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ─── HOW IT WORKS ─── --}}
<section class="sec how" id="how-it-works">
  <div class="how-inner">
    <span class="sec-label reveal">{{ __('messages.landing.how_label') }}</span>
    <h2 class="sec-h2 reveal reveal-delay-1">{{ __('messages.landing.how_h2') }}</h2>
    <p class="sec-sub reveal reveal-delay-2" style="margin:0 auto">{{ __('messages.landing.how_sub') }}</p>
    <div class="how-steps">
      @foreach([1,2,3] as $i)
      <div class="step reveal reveal-delay-{{ $i }}">
        <div class="step-num">{{ $i }}</div>
        <div class="step-h">{{ __("messages.landing.step_{$i}_h") }}</div>
        <p class="step-p">{{ __("messages.landing.step_{$i}_p") }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ─── PRICING ─── --}}
<section class="sec pricing" id="pricing">
  <div class="pricing-inner">
    <span class="sec-label reveal">{{ __('messages.landing.pricing_label') }}</span>
    <h2 class="sec-h2 reveal reveal-delay-1">{{ __('messages.landing.pricing_h2') }}</h2>
    <p class="sec-sub reveal reveal-delay-2" style="margin:0 auto">{{ __('messages.landing.pricing_sub') }}</p>
    <div class="pricing-grid">

      {{-- Starter --}}
      <div class="p-card reveal reveal-delay-1">
        <div class="p-name">{{ __('messages.landing.plan_starter') }}</div>
        <div class="p-desc">{{ __('messages.landing.plan_starter_d') }}</div>
        <div class="p-price">
          <span class="p-cur">$</span><span class="p-amt">29</span><span class="p-per">/{{ app()->getLocale() === 'ar' ? 'شهر' : 'mo' }}</span>
        </div>
        <ul class="p-features">
          @foreach(['feat_5u','feat_1k','feat_pipeline','feat_email_sup'] as $f)
          <li>
            <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="#00E89D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>
            {{ __("messages.landing.$f") }}
          </li>
          @endforeach
        </ul>
        <a href="{{ route('login') }}" class="btn-p btn-outline" style="display:block;text-align:center">{{ __('messages.landing.btn_get_started') }}</a>
      </div>

      {{-- Pro --}}
      <div class="p-card hot reveal reveal-delay-2">
        <div class="p-popular">{{ __('messages.landing.plan_popular') }}</div>
        <div class="p-name">{{ __('messages.landing.plan_pro') }}</div>
        <div class="p-desc">{{ __('messages.landing.plan_pro_d') }}</div>
        <div class="p-price">
          <span class="p-cur">$</span><span class="p-amt">79</span><span class="p-per">/{{ app()->getLocale() === 'ar' ? 'شهر' : 'mo' }}</span>
        </div>
        <ul class="p-features">
          @foreach(['feat_25u','feat_unlimited','feat_analytics','feat_priority'] as $f)
          <li>
            <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="#00E89D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>
            {{ __("messages.landing.$f") }}
          </li>
          @endforeach
        </ul>
        <a href="{{ route('login') }}" class="btn-p btn-filled" style="display:block;text-align:center">{{ __('messages.landing.btn_get_started') }}</a>
      </div>

      {{-- Enterprise --}}
      <div class="p-card reveal reveal-delay-3">
        <div class="p-name">{{ __('messages.landing.plan_enterprise') }}</div>
        <div class="p-desc">{{ __('messages.landing.plan_enterprise_d') }}</div>
        <div class="p-price">
          <span class="p-cur">$</span><span class="p-amt">199</span><span class="p-per">/{{ app()->getLocale() === 'ar' ? 'شهر' : 'mo' }}</span>
        </div>
        <ul class="p-features">
          @foreach(['feat_unl_users','feat_custom','feat_dedicated','feat_sla'] as $f)
          <li>
            <span class="chk"><svg viewBox="0 0 24 24" fill="none" stroke="#00E89D" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span>
            {{ __("messages.landing.$f") }}
          </li>
          @endforeach
        </ul>
        <a href="{{ route('login') }}" class="btn-p btn-outline" style="display:block;text-align:center">{{ __('messages.landing.btn_contact') }}</a>
      </div>

    </div>
  </div>
</section>

{{-- ─── CTA ─── --}}
<section class="cta-sec">
  <div class="cta-glow"></div>
  <div class="cta-inner">
    <h2 class="cta-h reveal">{{ __('messages.landing.cta_h') }}</h2>
    <p class="cta-sub reveal reveal-delay-1">{{ __('messages.landing.cta_sub') }}</p>
    <div class="cta-btns reveal reveal-delay-2">
      <a href="{{ route('login') }}" class="btn-hero" style="text-decoration:none">
        {{ __('messages.landing.cta_trial') }}
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
      <a href="{{ route('login') }}" class="btn-ghost" style="text-decoration:none">
        {{ __('messages.landing.cta_login') }}
      </a>
    </div>
  </div>
</section>

{{-- ─── FOOTER ─── --}}
<footer>
  <div class="foot-top">
    <div class="foot-brand">
      <a href="#" class="logo">
        <div class="logo-mark"><svg viewBox="0 0 24 24" fill="none" stroke="#05070A" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg></div>
        <span class="logo-name">SalesFlow</span>
      </a>
      <p>{{ __('messages.landing.foot_tagline') }}</p>
    </div>
    <div class="foot-col">
      <h5>{{ __('messages.landing.foot_product') }}</h5>
      <ul>
        <li><a href="#features">{{ __('messages.landing.foot_features') }}</a></li>
        <li><a href="#pricing">{{ __('messages.landing.foot_pricing') }}</a></li>
        <li><a href="#">{{ __('messages.landing.foot_integrations') }}</a></li>
      </ul>
    </div>
    <div class="foot-col">
      <h5>{{ __('messages.landing.foot_company') }}</h5>
      <ul>
        <li><a href="#">{{ __('messages.landing.foot_about') }}</a></li>
        <li><a href="#">{{ __('messages.landing.foot_blog') }}</a></li>
        <li><a href="#">{{ __('messages.landing.foot_contact') }}</a></li>
      </ul>
    </div>
    <div class="foot-col">
      <h5>{{ __('messages.landing.foot_legal') }}</h5>
      <ul>
        <li><a href="#">{{ __('messages.landing.foot_privacy') }}</a></li>
        <li><a href="#">{{ __('messages.landing.foot_terms') }}</a></li>
        <li><a href="#">{{ __('messages.landing.foot_cookies') }}</a></li>
      </ul>
    </div>
  </div>
  <div class="foot-bottom">
    <span>© {{ date('Y') }} {{ __('messages.landing.foot_copy') }}</span>
    <span>{{ __('messages.landing.foot_built') }}</span>
  </div>
</footer>

{{-- ═══════════════════════════════════════
     JAVASCRIPT
     ═══════════════════════════════════════ --}}
<script>
// ── Nav scroll effect ──
window.addEventListener('scroll', () => {
  document.querySelector('nav').classList.toggle('scrolled', window.scrollY > 20);
});

// ── Pipeline bar animation ──
window.addEventListener('load', () => {
  setTimeout(() => {
    const bar = document.getElementById('pipeBar');
    if (bar) bar.style.width = '72%';
  }, 400);
});

// ── Scroll reveal ──
const observer = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('visible');
    }
  });
}, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });

document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

// ── Particle canvas ──
(function() {
  const canvas = document.getElementById('particles-canvas');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  let w, h, particles = [];
  const PARTICLE_COUNT = 50;
  const MAX_DIST = 120;

  function resize() {
    w = canvas.width = window.innerWidth;
    h = canvas.height = window.innerHeight;
  }

  function createParticles() {
    particles = [];
    for (let i = 0; i < PARTICLE_COUNT; i++) {
      particles.push({
        x: Math.random() * w,
        y: Math.random() * h,
        vx: (Math.random() - 0.5) * 0.3,
        vy: (Math.random() - 0.5) * 0.3,
        r: Math.random() * 1.5 + 0.5,
      });
    }
  }

  function drawParticles() {
    ctx.clearRect(0, 0, w, h);

    // Draw connections
    for (let i = 0; i < particles.length; i++) {
      for (let j = i + 1; j < particles.length; j++) {
        const dx = particles[i].x - particles[j].x;
        const dy = particles[i].y - particles[j].y;
        const dist = Math.sqrt(dx * dx + dy * dy);
        if (dist < MAX_DIST) {
          ctx.beginPath();
          ctx.strokeStyle = `rgba(0, 232, 157, ${0.12 * (1 - dist / MAX_DIST)})`;
          ctx.lineWidth = 0.5;
          ctx.moveTo(particles[i].x, particles[i].y);
          ctx.lineTo(particles[j].x, particles[j].y);
          ctx.stroke();
        }
      }
    }

    // Draw dots
    particles.forEach(p => {
      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
      ctx.fillStyle = 'rgba(0, 232, 157, 0.25)';
      ctx.fill();
    });

    // Move
    particles.forEach(p => {
      p.x += p.vx;
      p.y += p.vy;
      if (p.x < 0 || p.x > w) p.vx *= -1;
      if (p.y < 0 || p.y > h) p.vy *= -1;
    });

    requestAnimationFrame(drawParticles);
  }

  resize();
  createParticles();
  drawParticles();
  window.addEventListener('resize', () => { resize(); createParticles(); });
})();
</script>
</body>
</html>