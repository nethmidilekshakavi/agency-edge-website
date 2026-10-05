<script setup>
import { ref } from 'vue';
import { useMotion } from '../../composables/useMotion';
import { gsap } from '../../lib/motion';
import SectionHead from './SectionHead.vue';
import Btn from './Btn.vue';

defineProps({
    founders: { type: Object, required: true },
    full: { type: Boolean, default: false }, // full bios on About, short on Home
});
const root = ref(null);

useMotion(root, ({ q, reduced }) => {
    if (reduced) return;
    q('.founder').forEach((card, i) => {
        const tl = gsap.timeline({ scrollTrigger: { trigger: card, start: 'top 85%', once: true } });
        tl.from(card, { y: 90, autoAlpha: 0, duration: 1.3, ease: 'edge', delay: i * 0.12 })
            .from(card.querySelector('.founder__media'), { clipPath: 'inset(0 0 100% 0)', duration: 1.3, ease: 'edgeInOut' }, '<0.1')
            .from(card.querySelector('.founder__mono'), { scale: 1.6, autoAlpha: 0, duration: 1.6 }, '<0.2');
        // Count the years up.
        const yearsEl = card.querySelector('.founder__years b');
        if (yearsEl) {
            const target = parseInt(yearsEl.textContent, 10) || 0;
            const obj = { v: 0 };
            tl.to(obj, { v: target, duration: 1.6, ease: 'power2.out', onUpdate: () => (yearsEl.textContent = Math.round(obj.v)) }, '<');
        }
    });
});
</script>

<template>
    <section ref="root" class="section" aria-labelledby="founders-title">
        <div class="container">
            <SectionHead :label="founders.label" :title="founders.title" />
            <div class="founders mt-l">
                <article v-for="person in founders.people" :key="person.name" class="card founder" v-tilt="3">
                    <div class="founder__media">
                        <img v-if="person.photo" :src="person.photo" :alt="`Portrait of ${person.name}`" loading="lazy">
                        <span v-else class="founder__mono" aria-hidden="true">{{ person.initials }}</span>
                        <div class="founder__years" v-if="person.years">
                            <b>{{ parseInt(person.years, 10) }}</b>+<small>years</small>
                        </div>
                    </div>
                    <div class="founder__body">
                        <h3 class="founder__name">{{ person.name }}</h3>
                        <div class="founder__role">{{ person.role }}</div>
                        <p class="founder__bio">{{ full ? person.bio : person.short_bio }}</p>
                        <div v-if="person.linkedin" class="founder__links">
                            <Btn :href="person.linkedin" external variant="ghost" size="sm" icon="linkedin">Connect on LinkedIn</Btn>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </section>
</template>
