<script setup>
/**
 * Marketing × Creative × Technology × AI = Agency Edge  (deck slide 3)
 * Pinned and scroll-scrubbed on desktop; simple reveal on mobile.
 */
import { ref } from 'vue';
import { useMotion } from '../../composables/useMotion';
import { gsap } from '../../lib/motion';

const props = defineProps({ equation: { type: Object, required: true } });
const root = ref(null);

useMotion(root, ({ q, reduced }) => {
    if (reduced) return;
    const mm = gsap.matchMedia();

    mm.add('(min-width: 761px)', () => {
        const tl = gsap.timeline({
            scrollTrigger: { trigger: root.value, start: 'top top', end: '+=180%', scrub: 1, pin: true },
            defaults: { ease: 'power3.out' },
        });
        q('.equation__item').forEach((item, i) => {
            tl.from(item.querySelector('.equation__name'), { yPercent: 120, autoAlpha: 0, duration: 1 }, i * 0.9)
                .from(item.querySelector('.equation__role'), { autoAlpha: 0, duration: 0.6 }, i * 0.9 + 0.4)
                .to(item.querySelector('.equation__role'), { duration: 0.8, scrambleText: { text: props.equation.items[i].role, chars: 'lowerCase', speed: 0.4 } }, i * 0.9 + 0.4);
            const op = item.nextElementSibling;
            if (op?.classList.contains('equation__op')) tl.from(op, { rotate: 180, scale: 0, autoAlpha: 0, duration: 0.6 }, i * 0.9 + 0.5);
        });
        tl.from(q('.equation__eq'), { scale: 0, autoAlpha: 0, duration: 0.8 }, '>')
            .from(q('.equation__brand span'), { yPercent: 100, scale: 1.6, autoAlpha: 0, filter: 'blur(14px)', stagger: 0.05, duration: 1.2 }, '>-0.2')
            .from(q('.equation__sub'), { autoAlpha: 0, y: 20, duration: 0.8 }, '>-0.3')
            .to({}, { duration: 0.6 });
    });

    mm.add('(max-width: 760px)', () => {
        gsap.from(q('.equation__item, .equation__result > *'), {
            y: 40, autoAlpha: 0, stagger: 0.1, duration: 1,
            scrollTrigger: { trigger: q('.equation__row')[0], start: 'top 85%', once: true },
        });
    });
}, { waitForReveal: false });
</script>

<template>
    <section ref="root" class="section equation" aria-labelledby="eq-title">
        <div class="container">
            <p class="label">{{ equation.label }}</p>
            <h2 id="eq-title" class="h2 mt-s" v-split>{{ equation.title }}</h2>
            <div class="equation__row" role="list">
                <template v-for="(item, i) in equation.items" :key="item.name">
                    <div class="equation__item" role="listitem">
                        <div style="overflow: hidden"><div class="equation__name">{{ item.name }}</div></div>
                        <div class="equation__role">{{ item.role }}</div>
                    </div>
                    <div v-if="i < equation.items.length - 1" class="equation__op" aria-hidden="true">×</div>
                </template>
            </div>
            <div class="equation__result">
                <div class="equation__eq" aria-hidden="true">=</div>
                <div class="equation__brand" style="overflow: hidden">
                    <span v-for="(word, i) in equation.result.split(' ')" :key="i" style="display:inline-block">{{ word }}&nbsp;</span>
                </div>
                <div class="equation__sub">{{ equation.result_sub }}</div>
            </div>
        </div>
    </section>
</template>
