<script setup>
import { onMounted, onBeforeUnmount, ref } from 'vue';
import { gsap, isTouch, prefersReducedMotion } from '../../lib/motion';

const root = ref(null);
const dot = ref(null);
const ring = ref(null);
const label = ref('');
const state = ref('is-hidden'); // hidden until the pointer first moves
let cleanup = () => {};

onMounted(() => {
    if (isTouch() || prefersReducedMotion()) return;
    document.documentElement.classList.add('has-cursor');

    const dx = gsap.quickTo(dot.value, 'x', { duration: 0.12, ease: 'power3' });
    const dy = gsap.quickTo(dot.value, 'y', { duration: 0.12, ease: 'power3' });
    const rx = gsap.quickTo(ring.value, 'x', { duration: 0.55, ease: 'power3' });
    const ry = gsap.quickTo(ring.value, 'y', { duration: 0.55, ease: 'power3' });

    const move = (e) => {
        dx(e.clientX); dy(e.clientY); rx(e.clientX); ry(e.clientY);
        const target = e.target.closest?.('[data-cursor], a, button, input, textarea, label');
        if (!target) { state.value = ''; label.value = ''; return; }
        const text = target.getAttribute?.('data-cursor');
        if (text) { state.value = 'is-label'; label.value = text; }
        else if (target.matches('input, textarea')) { state.value = 'is-hidden'; }
        else { state.value = 'is-hover'; label.value = ''; }
    };
    const leave = () => (state.value = 'is-hidden');
    const down = () => gsap.to(ring.value, { scale: 0.8, duration: 0.2 });
    const up = () => gsap.to(ring.value, { scale: 1, duration: 0.5, ease: 'elastic.out(1, 0.4)' });

    window.addEventListener('pointermove', move, { passive: true });
    document.addEventListener('pointerleave', leave);
    window.addEventListener('pointerdown', down);
    window.addEventListener('pointerup', up);
    cleanup = () => {
        window.removeEventListener('pointermove', move);
        document.removeEventListener('pointerleave', leave);
        window.removeEventListener('pointerdown', down);
        window.removeEventListener('pointerup', up);
        document.documentElement.classList.remove('has-cursor');
    };
});

onBeforeUnmount(() => cleanup());
</script>

<template>
    <div ref="root" class="cursor" :class="state" aria-hidden="true">
        <div ref="ring" class="cursor__ring"><span class="cursor__label">{{ label }}</span></div>
        <div ref="dot" class="cursor__dot"></div>
    </div>
</template>
