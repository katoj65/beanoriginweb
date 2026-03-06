<script setup>
import { Link } from '@inertiajs/vue3';

</script>

<template>
<div class="modern-exchange">
<header class="site-nav">
<div class="container">
<nav class="navbar navbar-expand-lg py-3">
<Link class="navbar-brand brandmark" href="/">
<img src="../../images/logo.png" alt="Commodity Origin logo" class="brand-logo" />
<span class="brand-title">Commodity Origin</span>
</Link>
<div class="header-auth">
<Link href="/login" class="header-auth-link">Login</Link>
<Link href="/register" class="header-auth-link register-link">Register</Link>
</div>
</nav>
</div>
</header>

<div class="container mt-5">
<slot></slot>
</div>

</div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

.modern-exchange {
--mx-bg: #f4f9fb;
--mx-bg-2: #eef4f7;
--mx-text: #102a43;
--mx-muted: #5f7386;
--mx-brand: #0e8a7d;
--mx-brand-dark: #0b6f65;
--mx-border: #dbe7ee;
min-height: 100vh;
background:
radial-gradient(900px 400px at 0% -10%, rgba(14, 138, 125, 0.16), transparent 70%),
radial-gradient(800px 380px at 100% 0%, rgba(242, 167, 75, 0.15), transparent 70%),
linear-gradient(180deg, var(--mx-bg), var(--mx-bg-2) 60%, #ffffff);
color: var(--mx-text);
font-family: 'Manrope', sans-serif;
}

.modern-exchange h1,
.modern-exchange h2,
.modern-exchange h3,
.modern-exchange h4,
.modern-exchange h5 {
font-family: 'Space Grotesk', sans-serif;
letter-spacing: -0.02em;
}

.site-nav {
position: sticky;
top: 0;
z-index: 40;
backdrop-filter: blur(8px);
background: rgba(244, 249, 251, 0.88);
border-bottom: 1px solid rgba(219, 231, 238, 0.9);
}

.brandmark {
display: inline-flex;
align-items: center;
gap: 10px;
font-weight: 700;
color: var(--mx-text);
}

.brand-logo {
width: 34px;
height: 34px;
object-fit: contain;
border-radius: 10px;
background: #fff;
border: 1px solid #d7e7ef;
padding: 3px;
}

.header-auth {
margin-left: auto;
display: inline-flex;
gap: 12px;
align-items: center;
}

.header-auth-link {
display: inline-flex;
align-items: center;
justify-content: center;
padding: 8px 14px;
border-radius: 10px;
border: 1px solid #b9d5ce;
background: #ffffff;
color: #334e68;
font-size: 0.9rem;
font-weight: 700;
text-decoration: none;
line-height: 1;
transition: all 0.2s ease;
}

.header-auth-link:hover {
color: #0b6f65;
border-color: #0b6f65;
background: #f4fbf9;
}

.header-auth-link.register-link {
background: linear-gradient(135deg, var(--mx-brand), var(--mx-brand-dark));
border-color: var(--mx-brand-dark);
color: #ffffff;
}

.hero-section {
padding: 4.5rem 0 2rem;
}

.hero-focus-title {
font-family: 'Manrope', sans-serif !important;
font-weight: 800;
}

.hero-kicker {
color: var(--mx-brand-dark);
font-weight: 700;
text-transform: uppercase;
letter-spacing: 0.08em;
font-size: 0.74rem;
margin-bottom: 0.75rem;
}

.hero-lead {
color: var(--mx-muted);
max-width: 62ch;
}

.btn-brand {
background: linear-gradient(135deg, var(--mx-brand), var(--mx-brand-dark));
border: none;
color: #fff;
font-weight: 700;
}

.btn-soft {
background: #e8f4f2;
border: 1px solid #c6e7e2;
color: var(--mx-brand-dark);
font-weight: 700;
}

.hero-panel {
border: 1px solid var(--mx-border);
border-radius: 18px;
background: linear-gradient(180deg, #ffffff, #f9fcfd);
box-shadow: 0 16px 30px rgba(16, 42, 67, 0.08);
}

.hero-panel .table-responsive {
border: 1px solid #d7e7ef;
border-radius: 14px;
overflow: hidden;
background: #fff;
}

.badge-live {
background: #e8fff6;
border: 1px solid #ace8cc;
color: #147a5a;
font-weight: 700;
}

.market-table thead th {
border-bottom-color: #d9e8f0;
color: #486581;
font-size: 0.75rem;
text-transform: uppercase;
letter-spacing: 0.06em;
}

.market-table td {
color: #102a43;
border-bottom-color: #edf3f7;
}

.metric-box {
border: 1px solid #d7e7ef;
border-radius: 12px;
padding: 10px 12px;
background: rgba(255, 255, 255, 0.9);
}

.metric-box small {
color: #627d98;
display: block;
}
</style>
