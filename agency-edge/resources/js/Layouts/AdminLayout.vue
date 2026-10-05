<script setup>
import { computed, ref, watch, onBeforeUnmount } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import '../../css/admin.css';

const page = usePage();
const open = ref(false);
const flash = ref(null);
let timer;

// Watch the props object (new on every response) so repeat messages still show.
watch(() => page.props, (p) => {
    const v = p.flash?.success;
    if (!v) return;
    flash.value = v;
    clearTimeout(timer);
    timer = setTimeout(() => (flash.value = null), 3500);
}, { immediate: true });

const off = router.on('navigate', () => (open.value = false));
onBeforeUnmount(() => { off(); clearTimeout(timer); });

const links = [
    { href: '/admin', label: 'Dashboard' },
    { href: '/admin/leads', label: 'Enquiries' },
    { href: '/admin/content', label: 'Website content' },
    { href: '/admin/posts', label: 'Insights (blog)' },
    { href: '/admin/subscribers', label: 'Subscribers' },
];
const path = computed(() => (page.url || '').split('?')[0]);
const active = (href) => (href === '/admin' ? path.value === '/admin' : path.value.startsWith(href));
</script>

<template>
    <div class="admin">
        <aside class="admin__side" :class="{ 'is-open': open }">
            <Link href="/admin" class="brand"><span class="brand__mark"></span>Agency Edge</Link>
            <nav class="admin__nav" aria-label="Admin">
                <Link v-for="l in links" :key="l.href" :href="l.href" :class="{ 'is-active': active(l.href) }">{{ l.label }}</Link>
            </nav>
            <div class="admin__foot">
                <a href="/" target="_blank" rel="noopener">View website ↗</a>
                <span>{{ page.props.auth?.user?.email }}</span>
                <Link href="/admin/logout" method="post" as="button" class="a-btn" style="justify-content:center">Log out</Link>
            </div>
        </aside>
        <main class="admin__main">
            <button class="a-btn admin__burger" style="margin-bottom:16px" @click="open = !open">☰ Menu</button>
            <slot />
        </main>
        <div v-if="flash" class="a-flash" role="status">{{ flash }}</div>
    </div>
</template>

<style>
body.is-admin { font-size: 16px; }
</style>
