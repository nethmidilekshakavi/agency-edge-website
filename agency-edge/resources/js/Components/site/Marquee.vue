<script setup>
/**
 * Infinite marquee whose speed follows scroll velocity and whose direction
 * flips with scroll direction.
 */
import { ref } from 'vue';
import { useMotion } from '../../composables/useMotion';
import { gsap, ScrollTrigger } from '../../lib/motion';

const props = defineProps({
    items: { type: Array, required: true },
    variant: { type: String, default: '' }, // accent | outline
    reverse: { type: Boolean, default: false },
    speed: { type: Number, default: 40 },   // seconds per loop
});
const root = ref(null);

useMotion(root, ({ q, reduced }) => {
    if (reduced) return;
    const track = q('.marquee__track')[0];
    const loop = gsap.to(track, { xPercent: -50, duration: props.speed, ease: 'none', repeat: -1 });
    if (props.reverse) loop.progress(1).timeScale(-1);
    const base = props.reverse ? -1 : 1;
    let dir = base;

    ScrollTrigger.create({
        trigger: root.value, start: 'top bottom', end: 'bottom top',
        onUpdate(self) {
            dir = self.direction === 1 ? base : -base;
            const v = Math.min(6, 1 + Math.abs(self.getVelocity()) / 400);
            gsap.to(loop, { timeScale: dir * v, duration: 0.2, overwrite: true });
            gsap.to(loop, { timeScale: dir, duration: 1.2, delay: 0.2, ease: 'power2.out' });
        },
    });
}, { waitForReveal: false });
</script>

<template>
    <div ref="root" class="marquee" :class="variant && `marquee--${variant}`" aria-hidden="true">
        <div class="marquee__track">
            <template v-for="n in 2" :key="n">
                <div v-for="(item, i) in items" :key="`${n}-${i}`" class="marquee__item">
                    {{ item }}
                    <svg viewBox="0 0 24 24"><path d="M12 0l2.6 9.4L24 12l-9.4 2.6L12 24l-2.6-9.4L0 12l9.4-2.6z" fill="currentColor" /></svg>
                </div>
            </template>
        </div>
    </div>
</template>
