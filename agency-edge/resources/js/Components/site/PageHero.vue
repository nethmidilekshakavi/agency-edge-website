<script setup>
import { ref } from 'vue';
import { useMotion } from '../../composables/useMotion';
import { gsap, SplitText } from '../../lib/motion';

const props = defineProps({
    label: { type: String, default: '' },
    title: { type: String, required: true },
    accent: { type: String, default: '' },
    lead: { type: String, default: '' },
});
const root = ref(null);

useMotion(root, ({ q, reduced }) => {
    if (reduced) return;
    const split = SplitText.create(q('.page-hero__title')[0], { type: 'words,lines', mask: 'lines', linesClass: 'split-line' });
    gsap.timeline()
        .from(q('.label'), { autoAlpha: 0, x: -20, duration: 0.8 })
        .from(split.words, { yPercent: 115, rotate: 4, stagger: 0.035, duration: 1.3 }, 0.05)
        .from(q('.page-hero__grid > *'), { autoAlpha: 0, y: 30, stagger: 0.12, duration: 1 }, 0.5)
        .from(q('.page-hero__glow'), { scale: 0.4, autoAlpha: 0, duration: 2.4 }, 0);
    gsap.to(q('.page-hero__glow'), {
        yPercent: 40, ease: 'none',
        scrollTrigger: { trigger: root.value, start: 'top top', end: 'bottom top', scrub: true },
    });
    return () => split.revert();
});
</script>

<template>
    <section ref="root" class="page-hero">
        <div class="page-hero__glow" aria-hidden="true"></div>
        <div class="container">
            <p v-if="label" class="label">{{ label }}</p>
            <h1 class="h1 page-hero__title">{{ title }} <span v-if="accent" class="accent">{{ accent }}</span></h1>
            <div v-if="lead || $slots.default" class="page-hero__grid">
                <p v-if="lead" class="lead">{{ lead }}</p>
                <div><slot /></div>
            </div>
        </div>
    </section>
</template>
