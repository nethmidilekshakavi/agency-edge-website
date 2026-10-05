<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useMotion } from '../../composables/useMotion';
import { gsap } from '../../lib/motion';
import Icon from './Icon.vue';

defineProps({ industries: { type: Object, required: true } });
const root = ref(null);

useMotion(root, ({ q, reduced }) => {
    if (reduced) return;
    q('.row').forEach((row) => {
        gsap.from(row, {
            y: 50, autoAlpha: 0, duration: 1.1, ease: 'edge',
            scrollTrigger: { trigger: row, start: 'top 92%', once: true },
        });
    });
});
</script>

<template>
    <section ref="root" class="section" aria-labelledby="ind-title">
        <div class="container">
            <p class="label" v-reveal>{{ industries.label }}</p>
            <div class="two-col mt-s" style="align-items: end">
                <h2 id="ind-title" class="h2" v-split>{{ industries.title }} <span class="accent">{{ industries.highlight }}</span></h2>
                <div v-reveal="{ delay: 0.15 }">
                    <p class="h3" style="margin-bottom: 14px">{{ industries.intro }}</p>
                    <p class="muted" style="margin: 0">{{ industries.body }}</p>
                </div>
            </div>
            <div class="rows mt-l">
                <Link v-for="(item, i) in industries.items" :key="item.name" href="/contact" class="row" data-cursor="Let's talk">
                    <span class="row__bg" aria-hidden="true"></span>
                    <span class="row__num">0{{ i + 1 }}</span>
                    <span class="row__name">{{ item.name }}</span>
                    <span class="row__text">{{ item.text }}</span>
                    <span class="row__arrow" aria-hidden="true"><Icon name="arrow" /></span>
                </Link>
            </div>
        </div>
    </section>
</template>
