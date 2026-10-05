<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useMotion } from '../../composables/useMotion';
import { gsap } from '../../lib/motion';
import Marquee from './Marquee.vue';
import Btn from './Btn.vue';
import Icon from './Icon.vue';

defineProps({ cta: { type: Object, required: true } });
const root = ref(null);

useMotion(root, ({ q, reduced }) => {
    if (reduced) return;
    gsap.timeline({ scrollTrigger: { trigger: root.value, start: 'top 80%', end: 'center 55%', scrub: 1 } })
        .from(q('.cta__title .t-a'), { xPercent: -12, autoAlpha: 0.15, ease: 'none' }, 0)
        .from(q('.cta__title .t-b'), { xPercent: 14, autoAlpha: 0, ease: 'none' }, 0.1);
    gsap.from(q('.cta__circle'), {
        scale: 0, rotate: -120, duration: 1.4, ease: 'back.out(1.4)',
        scrollTrigger: { trigger: q('.cta__row')[0], start: 'top 90%', once: true },
    });
});
</script>

<template>
    <section ref="root" class="cta" aria-labelledby="cta-title">
        <Marquee :items="['Marketing Meets Technology', 'Let\'s build your edge']" variant="outline" :speed="50" style="position:absolute; top: 0; left: 0; right: 0; border: 0" />
        <div class="container">
            <h2 id="cta-title" class="cta__title">
                <span class="t-a" style="display:block">{{ cta.title_a }}</span>
                <span class="t-b accent">{{ cta.title_b }}</span>
            </h2>
            <div class="cta__row">
                <div style="max-width: 52ch">
                    <p class="h3" style="margin-bottom: 12px">{{ cta.contact_title }}</p>
                    <p class="muted" style="margin-bottom: 24px">{{ cta.contact_body }}</p>
                    <Btn href="/contact" variant="ghost">{{ cta.secondary }}</Btn>
                </div>
                <Link href="/contact" class="cta__circle" v-magnetic="0.45" data-cursor>
                    <span>{{ cta.primary }}<Icon name="arrow" /></span>
                </Link>
            </div>
        </div>
    </section>
</template>
