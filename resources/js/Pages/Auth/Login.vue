<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';

defineProps({
canResetPassword: Boolean,
status: String,
});

const form = useForm({
email: '',
password: '',
remember: false,
});

const submit = () => {
form.transform(data => ({
...data,
remember: form.remember ? 'on' : '',
})).post(route('login'), {
onFinish: () => form.reset('password'),
});
};
</script>

<template>
<div class="auth-page">
<header class="auth-header">
<Link href="/" class="auth-brand">
<img src="../../../images/logo.png" alt="Commodity Origin logo" class="auth-logo" />
<span>Commodity Origin</span>
</Link>
</header>

<main class="auth-main">
<section class="auth-card">
<p class="auth-kicker">Welcome Back</p>
<h1 class="auth-focus-title">Sign in to continue</h1>
<p class="auth-subtitle">Access your marketplace dashboard, listings, and trade workflows.</p>

<p v-if="status" class="status-alert">{{ status }}</p>

<form @submit.prevent="submit" class="auth-form">
<div class="form-group">
<label class="form-label" for="email">Email</label>
<InputError :message="form.errors.email" class="mb-2"></InputError>
<div class="form-control-wrap">
<input
type="text"
class="form-control form-control-lg"
id="email"
placeholder="Enter your email address"
v-model="form.email"
>
</div>
</div>

<div class="form-group">
<div class="label-row">
<label class="form-label" for="password">Password</label>
<Link v-if="canResetPassword" href="/forgot-password" class="meta-link">Forgot password?</Link>
</div>
<InputError :message="form.errors.password" class="mb-2"></InputError>
<div class="form-control-wrap">
<input
type="password"
class="form-control form-control-lg"
id="password"
placeholder="Enter your password"
v-model="form.password"
>
</div>
</div>

<div class="form-group">
<button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
</div>
</form>

<div class="form-note-s2">Don't have an account?
<Link href="/register"><strong>Register</strong></Link>
</div>

<div class="auth-footer-links">
<a href="#">Terms</a>
<a href="#">Privacy</a>
<a href="#">Help</a>
</div>
</section>
</main>
</div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap');

.auth-page {
--mx-bg: #f4f9fb;
--mx-bg-2: #eef4f7;
--mx-text: #102a43;
--mx-muted: #5f7386;
--mx-brand: #0e8a7d;
--mx-brand-dark: #0b6f65;
--mx-border: #dbe7ee;
min-height: 100vh;
background:
radial-gradient(900px 400px at 0% -10%, rgba(14, 138, 125, 0.14), transparent 70%),
radial-gradient(820px 360px at 100% -5%, rgba(242, 167, 75, 0.12), transparent 70%),
linear-gradient(180deg, var(--mx-bg), var(--mx-bg-2) 60%, #ffffff);
font-family: 'Manrope', sans-serif;
color: var(--mx-text);
}

.auth-header {
padding: 1rem 1.25rem;
display: flex;
justify-content: center;
}

.auth-brand {
display: inline-flex;
align-items: center;
gap: 10px;
font-family: 'Space Grotesk', sans-serif;
font-weight: 700;
color: var(--mx-text);
text-decoration: none;
}

.auth-logo {
width: 36px;
height: 36px;
object-fit: contain;
border-radius: 10px;
background: #fff;
border: 1px solid #d7e7ef;
padding: 3px;
}

.auth-main {
min-height: calc(100vh - 72px);
display: grid;
place-items: center;
padding: 24px 16px 34px;
}

.auth-card {
width: 100%;
max-width: 460px;
padding: 28px;
border: 1px solid var(--mx-border);
border-radius: 18px;
background: linear-gradient(180deg, #ffffff, #f9fcfd);
box-shadow: 0 16px 30px rgba(16, 42, 67, 0.08);
}

.auth-kicker {
margin: 0;
font-size: 0.74rem;
font-weight: 700;
letter-spacing: 0.08em;
text-transform: uppercase;
color: var(--mx-brand-dark);
}

.auth-card h1 {
margin: 8px 0 10px;
font-family: 'Space Grotesk', sans-serif;
font-size: 1.72rem;
letter-spacing: -0.02em;
}

.auth-focus-title {
font-family: 'Manrope', sans-serif !important;
font-weight: 800;
letter-spacing: -0.01em;
}

.auth-subtitle {
margin: 0 0 18px;
color: var(--mx-muted);
font-size: 0.95rem;
}

.status-alert {
margin: 0 0 14px;
padding: 10px 12px;
border: 1px solid #b7eadf;
background: #eaf9f5;
border-radius: 10px;
color: #0b6f65;
font-size: 0.88rem;
font-weight: 600;
}

.auth-form .form-group {
margin-bottom: 14px;
}

.form-label {
color: #334e68;
font-weight: 700;
font-size: 0.86rem;
}

.label-row {
display: flex;
align-items: center;
justify-content: space-between;
gap: 10px;
}

.meta-link {
font-size: 0.82rem;
font-weight: 700;
color: var(--mx-brand-dark);
text-decoration: none;
}

.meta-link:hover {
text-decoration: underline;
}

.form-control {
border: 1px solid var(--mx-border);
border-radius: 12px;
height: 48px;
background: #ffffff;
color: #102a43;
}

.form-control:focus {
border-color: #93d4ca;
box-shadow: 0 0 0 3px rgba(14, 138, 125, 0.12);
}

.btn-primary {
height: 48px;
border: 0;
border-radius: 12px;
font-weight: 700;
background: linear-gradient(135deg, var(--mx-brand), var(--mx-brand-dark));
}

.btn-primary:hover {
filter: brightness(0.98);
}

.form-note-s2 {
margin-top: 8px;
font-size: 0.9rem;
color: #486581;
}

.form-note-s2 a {
color: var(--mx-brand-dark);
text-decoration: none;
margin-left: 4px;
}

.form-note-s2 a:hover {
text-decoration: underline;
}

.auth-footer-links {
display: flex;
gap: 14px;
flex-wrap: wrap;
padding-top: 18px;
margin-top: 16px;
border-top: 1px solid #e7eff4;
}

.auth-footer-links a {
font-size: 0.84rem;
font-weight: 600;
color: #627d98;
text-decoration: none;
}

.auth-footer-links a:hover {
color: var(--mx-brand-dark);
}

@media (max-width: 575.98px) {
.auth-card {
padding: 22px 16px;
border-radius: 14px;
}

.auth-card h1 {
font-size: 1.42rem;
}
}
</style>
