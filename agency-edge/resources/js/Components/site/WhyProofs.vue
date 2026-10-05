<script setup>
import { ref } from 'vue';
import { useMotion } from '../../composables/useMotion';
import { gsap } from '../../lib/motion';

defineProps({ why: { type: Object, required: true } });
const root = ref(null);

useMotion(root, ({ q, reduced }) => {
    if (reduced) return;
    q('.proof').forEach((row) => {
        gsap.timeline({ scrollTrigger: { trigger: row, start: 'top 88%', once: true } })
            .to(row.querySelector('.proof__line'), { scaleX: 1, duration: 1.4, ease: 'edgeInOut' })
            .from(row.querySelectorAll('.proof__num, .proof__name, .proof__text'), { yPercent: 60, autoAlpha: 0, stagger: 0.08, duration: 1.1 }, 0.1)
            .to(row.querySelector('.proof__line'), { autoAlpha: 0.25, duration: 0.8 }, '>');
    });
});
</script>

<template>
    <section ref="root" class="section" aria-labelledby="why-title">
        <div class="container">
            <p class="label" v-reveal>{{ why.label }}</p>
            <h2 id="why-title" class="h1 mt-s">
                <span v-split style="display:block">{{ why.title_a }}</span>
                <span v-split="{ delay: 0.15 }" class="accent" style="display:block">{{ why.title_b }}</span>
            </h2>
            <div class="proofs mt-l" style="border-top: 1px solid var(--line)">
                <div v-for="(p, i) in why.points" :key="p.name" class="proof">
                    <span class="proof__num">0{{ i + 1 }}</span>
                    <h3 class="proof__name">{{ p.name }}</h3>
                    <p class="proof__text">{{ p.text }}</p>
                    <span class="proof__line" aria-hidden="true"></span>
                </div>
            </div>
        </div>
    </section>
</template>
