<script setup>
import { ref } from 'vue';
import { useMotion } from '../../composables/useMotion';
import { gsap } from '../../lib/motion';
import Btn from './Btn.vue';
import heroImg from '../../../images/hero-960.webp';

defineProps({ martech: { type: Object, required: true }, cta: { type: Boolean, default: true } });
const root = ref(null);

useMotion(root, ({ q, reduced }) => {
    if (reduced) return;
    gsap.from(q('.duo__media'), {
        clipPath: 'inset(20% 20% 20% 20% round 32px)', duration: 1.6, ease: 'edgeInOut',
        scrollTrigger: { trigger: root.value, start: 'top 75%', once: true },
    });
    gsap.from(q('.tag-cloud .chip'), {
        scale: 0.4, autoAlpha: 0, stagger: { each: 0.05, from: 'random' }, duration: 0.9, ease: 'back.out(2)',
        scrollTrigger: { trigger: q('.tag-cloud')[0], start: 'top 90%', once: true },
    });
});
</script>

<template>
    <section ref="root" class="section" aria-labelledby="mt-title">
        <div class="container duo">
            <div>
                <p class="label" v-reveal>{{ martech.label }}</p>
                <h2 id="mt-title" class="h2 mt-s">
                    <span v-split style="display:block">{{ martech.title_a }}</span>
                    <span v-split="{ delay: 0.15 }" class="accent" style="display:block">{{ martech.title_b }}</span>
                </h2>
                <p class="lead mt-m" v-reveal>{{ martech.lead }}</p>
                <div class="tag-cloud">
                    <span v-for="t in martech.tags" :key="t" class="chip">{{ t }}</span>
                </div>
                <div v-if="cta" class="mt-m" v-reveal><Btn href="/martech" variant="ghost">Explore MarTech</Btn></div>
            </div>
            <div class="duo__media" data-cursor="Ribelz">
                <img :src="heroImg" alt="Light trails connecting marketing dashboards — technology delivered by Ribelz" loading="lazy" v-parallax="-16">
            </div>
        </div>
    </section>
</template>
