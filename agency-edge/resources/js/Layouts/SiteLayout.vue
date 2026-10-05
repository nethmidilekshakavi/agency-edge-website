<script setup>
import { onMounted, onBeforeUnmount, ref, nextTick } from 'vue';
import { router } from '@inertiajs/vue3';
import {
    gsap, ScrollTrigger, startSmoothScroll, stopSmoothScroll, getLenis, markCovered, markRevealed,
    refreshSoon, prefersReducedMotion, lockScroll,
} from '../lib/motion';
import Cursor from '../Components/site/Cursor.vue';
import Preloader from '../Components/site/Preloader.vue';
import SiteHeader from '../Components/site/SiteHeader.vue';
import SiteFooter from '../Components/site/SiteFooter.vue';

const overlay = ref(null);
const offs = [];
let covering = null;
let navigated = false;

function cover() {
    const q = gsap.utils.selector(overlay.value);
    lockScroll(true);
    return gsap.timeline()
        .set(q('.transition__panel'), { y: 0, yPercent: 100 }) // y:0 clears the px value GSAP parses from the CSS translateY
        .to(q('.transition__panel--a'), { yPercent: 0, duration: 0.55, ease: 'edgeInOut' })
        .to(q('.transition__panel--b'), { yPercent: 0, duration: 0.55, ease: 'edgeInOut' }, 0.08)
        .fromTo(q('.transition__mark'), { autoAlpha: 0, y: 14 }, { autoAlpha: 1, y: 0, duration: 0.35 }, 0.35)
        .then();
}

function uncover() {
    const q = gsap.utils.selector(overlay.value);
    lockScroll(false);
    gsap.timeline()
        .to(q('.transition__mark'), { autoAlpha: 0, duration: 0.2 })
        .to(q('.transition__panel--b'), { yPercent: -100, duration: 0.75, ease: 'edgeInOut' }, 0.05)
        .to(q('.transition__panel--a'), { yPercent: -100, duration: 0.75, ease: 'edgeInOut' }, 0.12);
}

function isPageVisit(visit) {
    if (visit.method !== 'get' || visit.preserveState === true || visit.only?.length) return false;
    try {
        const next = new URL(visit.url, window.location.href);
        return next.pathname.replace(/\/$/, '') !== window.location.pathname.replace(/\/$/, '') || next.search !== window.location.search;
    } catch { return true; }
}

function onPreloaderDone() {
    markRevealed();
    refreshSoon();
}

onMounted(() => {
    startSmoothScroll();

    if (prefersReducedMotion()) return;

    offs.push(router.on('start', (event) => {
        if (!isPageVisit(event.detail.visit)) return;
        navigated = false;
        markCovered();
        covering = cover();
    }));

    offs.push(router.on('navigate', async () => {
        if (!covering) return;
        navigated = true;
        await covering;
        covering = null;
        getLenis()?.scrollTo(0, { immediate: true, force: true });
        window.scrollTo(0, 0);
        await nextTick();
        ScrollTrigger.refresh();
        uncover();
        markRevealed();
        refreshSoon();
    }));

    // A cancelled or failed visit must never leave the overlay up.
    offs.push(router.on('finish', async () => {
        if (!covering || navigated) return;
        await covering;
        covering = null;
        uncover();
        markRevealed();
    }));
});

onBeforeUnmount(() => {
    offs.forEach((off) => off());
    stopSmoothScroll();
    markRevealed();
});
</script>

<template>
    <a class="skip-link" href="#main">Skip to content</a>
    <Preloader @done="onPreloaderDone" />
    <SiteHeader />
    <main id="main">
        <slot />
    </main>
    <SiteFooter />
    <Cursor />
    <div ref="overlay" class="transition" aria-hidden="true">
        <div class="transition__panel transition__panel--a"></div>
        <div class="transition__panel transition__panel--b">
            <div class="transition__mark"><span class="brand__mark"></span>Agency Edge</div>
        </div>
    </div>
    <div class="grain" aria-hidden="true"></div>
</template>
