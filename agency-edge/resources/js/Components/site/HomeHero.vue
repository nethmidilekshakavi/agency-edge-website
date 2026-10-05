<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { useMotion } from '../../composables/useMotion';
import { gsap, SplitText, isTouch } from '../../lib/motion';
import HeroField from './HeroField.vue';
import Btn from './Btn.vue';
import heroImg from '../../../images/hero.webp';
import heroImgSm from '../../../images/hero-960.webp';

const props = defineProps({ hero: { type: Object, required: true } });
const root = ref(null);
const visual = ref(null);

const lines = props.hero.title_lines?.length ? props.hero.title_lines : ['Marketing', 'Meets', 'Technology.'];

useMotion(root, ({ q, reduced }) => {
    if (reduced) return;

    const splits = q('.hero__title .line > span').map((el) => SplitText.create(el, { type: 'chars' }));
    const chars = splits.flatMap((s) => s.chars);

    const intro = gsap.timeline({ defaults: { ease: 'edge' } });
    intro
        .fromTo(visual.value, { clipPath: 'inset(100% 0% 0% 0% round 32px)' }, { clipPath: 'inset(0% 0% 0% 0% round 32px)', duration: 1.6, ease: 'edgeInOut' }, 0)
        .from(q('.hero__visual img'), { scale: 1.5, duration: 2.2 }, 0)
        .from(chars, { yPercent: 115, rotate: 8, duration: 1.3, stagger: 0.025 }, 0.15)
        .to(q('.hero__eyebrow'), { duration: 1.2, scrambleText: { text: props.hero.eyebrow, chars: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ.', speed: 0.5 } }, 0.2)
        .from(q('.hero__bottom > *'), { y: 40, autoAlpha: 0, stagger: 0.12, duration: 1.1 }, 0.7)
        .from(q('.hero__scroll'), { autoAlpha: 0, duration: 1 }, 1.2);

    // Scroll-out: headline lifts and fades, visual zooms — a cinematic hand-off.
    gsap.timeline({ scrollTrigger: { trigger: root.value, start: 'top top', end: 'bottom top', scrub: true } })
        .to(q('.hero__title'), { yPercent: -18, autoAlpha: 0.15, ease: 'none' }, 0)
        .to(q('.hero__bottom'), { yPercent: -40, autoAlpha: 0, ease: 'none' }, 0)
        .to(visual.value, { yPercent: 18, scale: 0.92, autoAlpha: 0.4, ease: 'none' }, 0);

    return () => splits.forEach((s) => s.revert());
});

// Mouse-parallax tilt on the hero visual.
let off = () => {};
onMounted(() => {
    if (isTouch()) return;
    const rx = gsap.quickTo(visual.value, 'rotationY', { duration: 1.2, ease: 'power3' });
    const ry = gsap.quickTo(visual.value, 'rotationX', { duration: 1.2, ease: 'power3' });
    const move = (e) => {
        rx(((e.clientX / window.innerWidth) - 0.5) * -10);
        ry(((e.clientY / window.innerHeight) - 0.5) * 8);
    };
    gsap.set(visual.value, { transformPerspective: 1200 });
    window.addEventListener('pointermove', move, { passive: true });
    off = () => window.removeEventListener('pointermove', move);
});
onBeforeUnmount(() => off());
</script>

<template>
    <section ref="root" class="hero" aria-labelledby="hero-title">
        <HeroField />
        <div class="hero__vignette" aria-hidden="true"></div>
        <div class="container hero__inner">
            <div ref="visual" class="hero__visual" data-cursor="Edge">
                <img :src="heroImg" :srcset="`${heroImgSm} 960w, ${heroImg} 1672w`" sizes="(max-width: 900px) 100vw, 54vw"
                     alt="Golden streams of light flowing through a futuristic city of floating marketing dashboards" fetchpriority="high">
            </div>
            <p class="eyebrow hero__eyebrow">{{ hero.eyebrow }}</p>
            <h1 id="hero-title" class="display hero__title">
                <span v-for="(line, i) in lines" :key="i" class="line">
                    <span :class="{ 'outline-text': i === 1 }">
                        <template v-if="i === lines.length - 1 && line.endsWith('.')">{{ line.slice(0, -1) }}<b class="period">.</b></template>
                        <template v-else>{{ line }}</template>
                    </span>
                </span>
            </h1>
            <div class="hero__bottom">
                <div>
                    <p class="lead" style="color: var(--text); font-weight: 500; margin-bottom: 10px">{{ hero.kicker }}</p>
                    <p class="lead">{{ hero.lead }}</p>
                </div>
                <div class="hero__actions">
                    <Btn href="/contact">{{ hero.primary_cta }}</Btn>
                    <Btn href="/what-we-do" variant="ghost">{{ hero.secondary_cta }}</Btn>
                </div>
            </div>
        </div>
        <div class="hero__scroll" aria-hidden="true">Scroll<i></i></div>
    </section>
</template>
