<script setup>
/**
 * Pinned horizontal journey on desktop, vertical timeline on mobile
 * (deck slide 20 developer note). Used for the customer journey and
 * the "How we work" process.
 */
import { ref } from 'vue';
import { useMotion } from '../../composables/useMotion';
import { gsap } from '../../lib/motion';
import SectionHead from './SectionHead.vue';
import Btn from './Btn.vue';

const props = defineProps({
    label: String,
    title: String,
    steps: { type: Array, required: true }, // [{ name, text?, points? }]
    summary: { type: String, default: '' },
    ctaText: { type: String, default: '' },
    ctaHref: { type: String, default: '/contact' },
});
const root = ref(null);

useMotion(root, ({ q, reduced }) => {
    if (reduced) return;
    const mm = gsap.matchMedia();
    const track = q('.hscroll__track')[0];

    mm.add('(min-width: 901px)', () => {
        const distance = () => Math.max(0, track.scrollWidth - window.innerWidth);
        const move = gsap.to(track, {
            x: () => -distance(),
            ease: 'none',
            scrollTrigger: {
                trigger: root.value,
                start: 'top top',
                end: () => `+=${distance()}`,
                scrub: 1,
                pin: true,
                invalidateOnRefresh: true,
            },
        });
        gsap.to(q('.hscroll__progress i'), {
            scaleX: 1, ease: 'none',
            scrollTrigger: { trigger: root.value, start: 'top top', end: () => `+=${distance()}`, scrub: 1, invalidateOnRefresh: true },
        });
        // Each panel's content drifts against the track for depth.
        q('.hscroll__panel').forEach((panel) => {
            const parts = panel.querySelectorAll('.hscroll__name, .hscroll__text, .hscroll__chips');
            if (!parts.length) return;
            gsap.from(parts, {
                x: 120, autoAlpha: 0.2, stagger: 0.05, ease: 'none',
                scrollTrigger: { trigger: panel, containerAnimation: move, start: 'left 95%', end: 'left 40%', scrub: true },
            });
        });
    });

    mm.add('(max-width: 900px)', () => {
        gsap.to(q('.timeline__fill'), {
            scaleY: 1, ease: 'none',
            scrollTrigger: { trigger: track, start: 'top 70%', end: 'bottom 70%', scrub: true },
        });
        q('.hscroll__panel').forEach((panel) => gsap.from(panel, {
            y: 50, autoAlpha: 0, duration: 1,
            scrollTrigger: { trigger: panel, start: 'top 88%', once: true },
        }));
    });
}, { waitForReveal: false });
</script>

<template>
    <section ref="root" class="section hscroll" :aria-label="title">
        <div class="container hscroll__head">
            <SectionHead :label="label" :title="title" />
        </div>
        <div class="timeline">
            <span class="timeline__fill" aria-hidden="true"></span>
            <ol class="hscroll__track" style="list-style: none; margin: 0">
                <li v-for="(step, i) in steps" :key="step.name" class="card hscroll__panel" v-tilt="3">
                    <span class="hscroll__index">{{ String(i + 1).padStart(2, '0') }} / {{ String(steps.length).padStart(2, '0') }}</span>
                    <h3 class="hscroll__name">{{ step.name }}</h3>
                    <p v-if="step.text" class="hscroll__text">{{ step.text }}</p>
                    <div v-if="step.points" class="hscroll__chips">
                        <span v-for="p in step.points" :key="p" class="chip">{{ p }}</span>
                    </div>
                </li>
                <li v-if="summary" class="card hscroll__panel hscroll__panel--end">
                    <span class="hscroll__index" style="color: inherit">→</span>
                    <p class="h3">{{ summary }}</p>
                    <div v-if="ctaText"><Btn :href="ctaHref" variant="ghost" style="border-color: rgba(10,10,11,.3); color: var(--accent-ink)">{{ ctaText }}</Btn></div>
                </li>
            </ol>
        </div>
        <div class="hscroll__progress" aria-hidden="true"><i></i></div>
    </section>
</template>
