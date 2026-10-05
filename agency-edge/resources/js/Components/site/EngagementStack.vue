<script setup>
/** Sticky stacking cards: each model slides over the last, which recedes. */
import { ref } from 'vue';
import { useMotion } from '../../composables/useMotion';
import { gsap } from '../../lib/motion';
import SectionHead from './SectionHead.vue';
import Btn from './Btn.vue';

defineProps({ engagement: { type: Object, required: true } });
const root = ref(null);

useMotion(root, ({ q, reduced }) => {
    if (reduced) return;
    const mm = gsap.matchMedia();
    mm.add('(min-width: 761px)', () => {
        const cards = q('.stack__card');
        cards.forEach((card, i) => {
            if (i === cards.length - 1) return;
            gsap.to(card, {
                scale: 0.92 - (cards.length - i - 2) * 0.03,
                filter: 'brightness(0.55)',
                ease: 'none',
                scrollTrigger: { trigger: cards[i + 1], start: 'top bottom', end: 'top 30%', scrub: true },
            });
        });
    });
    q('.stack__num').forEach((num) => gsap.from(num, {
        yPercent: 60, autoAlpha: 0, duration: 1.2,
        scrollTrigger: { trigger: num, start: 'top 90%', once: true },
    }));
});
</script>

<template>
    <section ref="root" class="section" aria-labelledby="eng-title">
        <div class="container">
            <SectionHead :label="engagement.label" :title="engagement.title" />
            <div class="stack mt-l">
                <article v-for="(m, i) in engagement.models" :key="m.name" class="stack__card">
                    <span class="stack__num">0{{ i + 1 }}</span>
                    <div>
                        <h3 class="stack__name">{{ m.name }}</h3>
                        <p class="stack__text">{{ m.text }}</p>
                        <Btn :href="`/contact?topic=${encodeURIComponent(m.name)}`" variant="ghost">Start here</Btn>
                    </div>
                </article>
            </div>
        </div>
    </section>
</template>
