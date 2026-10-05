<script setup>
/**
 * Pinned statement: words light up one by one as you scroll, then the
 * accent line punches in. Deck slide 2 — "The Shift".
 */
import { ref } from 'vue';
import { useMotion } from '../../composables/useMotion';
import { gsap } from '../../lib/motion';

const props = defineProps({ shift: { type: Object, required: true } });
const root = ref(null);
const words = (s) => (s || '').split(' ');

useMotion(root, ({ q, reduced }) => {
    if (reduced) {
        gsap.set(q('.statement .word'), { color: 'var(--text)' });
        return;
    }
    const mm = gsap.matchMedia();
    mm.add({ desktop: '(min-width: 901px)', mobile: '(max-width: 900px)' }, (ctx) => {
        const { desktop } = ctx.conditions;
        const tl = gsap.timeline({
            scrollTrigger: {
                trigger: root.value,
                start: desktop ? 'top top' : 'top 70%',
                end: desktop ? '+=140%' : 'bottom 60%',
                scrub: 0.8,
                pin: desktop,
            },
        });
        tl.to(q('.statement--main .word'), { color: '#f5f5f2', stagger: 0.1, ease: 'none' })
            .from(q('.statement--accent .word'), { yPercent: 60, autoAlpha: 0, rotateX: -60, stagger: 0.08, ease: 'power3.out' }, '>-0.1')
            .from(q('.shift__body'), { autoAlpha: 0, y: 30, ease: 'none' }, '>-0.2');
    });
}, { waitForReveal: false });
</script>

<template>
    <section ref="root" class="section shift" aria-label="The shift">
        <div class="container">
            <p class="label">{{ shift.label }}</p>
            <h2 class="statement statement--main mt-m">
                <template v-for="(w, i) in words(shift.title)" :key="'a' + i"><span class="word">{{ w }}</span>{{ ' ' }}</template>
            </h2>
            <p class="statement statement--accent" style="perspective: 800px">
                <template v-for="(w, i) in words(shift.highlight)" :key="'b' + i"><span class="word" style="color: inherit">{{ w }}</span>{{ ' ' }}</template>
            </p>
            <p class="shift__body">{{ shift.body }}</p>
        </div>
    </section>
</template>
