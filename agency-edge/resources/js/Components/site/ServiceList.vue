<script setup>
/** Service cards with hover descriptions, each linking to the enquiry form (deck slide 16). */
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useMotion } from '../../composables/useMotion';
import { gsap } from '../../lib/motion';

defineProps({ block: { type: Object, required: true } });
const root = ref(null);

useMotion(root, ({ q, reduced }) => {
    if (reduced) return;
    gsap.from(q('.service'), {
        y: 40, autoAlpha: 0, stagger: 0.06, duration: 1.1, ease: 'edge',
        scrollTrigger: { trigger: q('.services')[0], start: 'top 85%', once: true },
    });
});
</script>

<template>
    <div ref="root" class="two-col">
        <div class="sticky-col">
            <p class="label" v-reveal>{{ block.label }}</p>
            <h2 class="h2 mt-s" style="font-size: clamp(28px, 3.4vw, 52px)" v-split>{{ block.title }}</h2>
            <p class="muted mt-m" v-reveal>{{ block.summary }}</p>
        </div>
        <div class="services">
            <Link v-for="(item, i) in block.items" :key="item.name" :href="`/contact?topic=${encodeURIComponent(item.name)}`" class="service" data-cursor="Enquire">
                <span class="service__num">{{ String(i + 1).padStart(2, '0') }}</span>
                <div>
                    <div class="service__name">{{ item.name }}</div>
                    <p class="service__text">{{ item.text }}</p>
                </div>
                <span class="service__go">Enquire →</span>
            </Link>
        </div>
    </div>
</template>
