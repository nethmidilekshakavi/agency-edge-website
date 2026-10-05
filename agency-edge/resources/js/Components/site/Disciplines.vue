<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useMotion } from '../../composables/useMotion';
import { gsap } from '../../lib/motion';
import SectionHead from './SectionHead.vue';

defineProps({ disciplines: { type: Object, required: true }, linked: { type: Boolean, default: true } });
const root = ref(null);

useMotion(root, ({ q, reduced }) => {
    if (reduced) return;
    gsap.from(q('.discipline'), {
        y: 120, rotateX: -35, autoAlpha: 0, transformOrigin: '50% 100%',
        stagger: 0.12, duration: 1.4, ease: 'edge',
        scrollTrigger: { trigger: q('.disciplines')[0], start: 'top 85%', once: true },
    });
});
</script>

<template>
    <section ref="root" class="section" aria-labelledby="disc-title">
        <div class="container">
            <SectionHead :label="disciplines.label" :title="disciplines.title" />
            <div class="disciplines mt-l">
                <component
                    :is="linked ? Link : 'article'"
                    v-for="(d, i) in disciplines.items"
                    :key="d.name"
                    :href="linked ? (d.name === 'MarTech' ? '/martech' : '/what-we-do') : undefined"
                    class="card discipline"
                    v-tilt="6"
                    data-cursor="Explore"
                >
                    <div class="discipline__num">0{{ i + 1 }}</div>
                    <h3 class="discipline__name">{{ d.name }}</h3>
                    <p class="discipline__summary">{{ d.summary }}</p>
                    <ul class="discipline__points">
                        <li v-for="p in d.points" :key="p">{{ p }}</li>
                    </ul>
                </component>
            </div>
        </div>
    </section>
</template>
