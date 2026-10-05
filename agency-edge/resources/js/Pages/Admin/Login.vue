<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import '../../../css/admin.css';

const form = useForm({ email: '', password: '', remember: true });
const submit = () => form.post('/admin/login', { onFinish: () => form.reset('password') });
</script>

<template>
    <Head title="Admin login" />
    <div class="login">
        <form class="a-card login__card" @submit.prevent="submit">
            <div class="brand" style="margin-bottom: 24px"><span class="brand__mark"></span>Agency Edge</div>
            <h1 style="font-family: var(--font-display); font-size: 28px; margin: 0 0 20px">Admin sign in</h1>
            <div class="a-field">
                <label class="a-label" for="email">Email</label>
                <input id="email" v-model="form.email" class="a-input" type="email" autocomplete="username" required autofocus>
                <p v-if="form.errors.email" class="a-error">{{ form.errors.email }}</p>
            </div>
            <div class="a-field">
                <label class="a-label" for="password">Password</label>
                <input id="password" v-model="form.password" class="a-input" type="password" autocomplete="current-password" required>
                <p v-if="form.errors.password" class="a-error">{{ form.errors.password }}</p>
            </div>
            <label style="display:flex; gap:8px; align-items:center; font-size:14px; color: var(--muted); margin-bottom: 18px">
                <input v-model="form.remember" type="checkbox"> Keep me signed in
            </label>
            <button class="a-btn a-btn--primary" style="width:100%; justify-content:center; height:44px" :disabled="form.processing">Sign in</button>
        </form>
    </div>
</template>
