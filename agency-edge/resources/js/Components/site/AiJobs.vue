<script setup>
import { ref } from 'vue';
import { useMotion } from '../../composables/useMotion';
import { gsap } from '../../lib/motion';
import Icon from './Icon.vue';

defineProps({ ai: { type: Object, required: true } });
const root = ref(null);
const icons = ['chat', 'target', 'bolt', 'spark'];

useMotion(root, ({ q, reduced }) => {
    if (reduced) return;
    const mm = gsap.matchMedia();
    mm.add('(min-width: 901px)', () => {
        // Two halves of the statement slide in from opposite sides as you scroll.
        gsap.timeline({ scrollTrigger: { trigger: root.value, start: 'top 85%', end: 'top 25%', scrub: 1 } })
            .from(q('.ai-a'), { xPercent: -30, autoAlpha: 0.1, ease: 'none' }, 0)
            .from(q('.ai-b'), { xPercent: 30, autoAlpha: 0.1, ease: 'none' }, 0);
    });
    gsap.from(q('.job'), {
        y: 70, autoAlpha: 0, stagger: 0.1, duration: 1.2, ease: 'edge',
        scrollTrigger: { trigger: q('.jobs')[0], start: 'top 85%', once: true },
    });
    gsap.from(q('.job__icon'), {
        scale: 0, rotate: -90, stagger: 0.1, duration: 1, ease: 'back.out(2)', delay: 0.3,
        scrollTrigger: { trigger: q('.jobs')[0], start: 'top 85%', once: true },
    });
});
</script>

<template>
    <section ref="root" class="section" aria-labelledby="ai-title" style="overflow: clip">
        <div class="container">
            <p class="label" v-reveal>{{ ai.label }}</p>
            <h2 id="ai-title" class="h1 mt-s">
                <span class="ai-a" style="display:block">{{ ai.title_a }}</span>
                <span class="ai-b accent" style="display:block; text-align:right">{{ ai.title_b }}</span>
            </h2>
            <div class="jobs">
                <article v-for="(job, i) in ai.jobs" :key="job.name" class="card job" v-tilt="6">
                    <span class="job__icon"><Icon :name="icons[i % icons.length]" /></span>
                    <div>
                        <h3 class="job__name">{{ job.name }}</h3>
                        <p class="job__text">{{ job.text }}</p>
                    </div>
                </article>
            </div>
            <p class="eyebrow mt-m" v-reveal style="color: var(--text)">{{ ai.tagline }}</p>
        </div>
    </section>
</template>
