<script setup>
import { onMounted, ref } from 'vue';
import { gsap, prefersReducedMotion, lockScroll } from '../../lib/motion';

const emit = defineEmits(['done']);
const root = ref(null);
const count = ref(0);
const visible = ref(true);
const words = ['Strategy.', 'Creative.', 'Growth.', 'MarTech.'];

function seen() {
    try { return sessionStorage.getItem('ae-loaded') === '1'; } catch { return false; }
}
function remember() {
    try { sessionStorage.setItem('ae-loaded', '1'); } catch { /* storage unavailable */ }
}

onMounted(() => {
    if (seen() || prefersReducedMotion()) {
        visible.value = false;
        emit('done');
        return;
    }
    lockScroll(true);
    const q = gsap.utils.selector(root.value);
    const counter = { v: 0 };
    const tl = gsap.timeline({
        onComplete: () => { visible.value = false; remember(); },
    });

    tl.from(q('.preloader__brand'), { autoAlpha: 0, y: 12, duration: 0.6 })
        .from(q('.preloader__words span'), { yPercent: 110, stagger: 0.12, duration: 0.8 }, 0.1)
        .to(counter, {
            v: 100, duration: 1.6, ease: 'power2.inOut',
            onUpdate: () => (count.value = Math.round(counter.v)),
        }, 0)
        .to(q('.preloader__bar'), { scaleX: 1, duration: 1.6, ease: 'power2.inOut' }, 0)
        .to(q('.preloader__words span'), { yPercent: -110, stagger: 0.06, duration: 0.6, ease: 'power3.in' }, 1.6)
        .to(q('.preloader__count'), { yPercent: -30, autoAlpha: 0, duration: 0.6, ease: 'power3.in' }, 1.65)
        .add(() => { lockScroll(false); emit('done'); }, 2.0)
        .to(root.value, { clipPath: 'inset(0 0 100% 0)', duration: 1, ease: 'edgeInOut' }, 1.95);
});
</script>

<template>
    <div v-if="visible" ref="root" class="preloader" style="clip-path: inset(0 0 0 0)" aria-hidden="true">
        <div class="preloader__brand brand"><span class="brand__mark"></span>Agency Edge</div>
        <div class="preloader__words">
            <div v-for="w in words" :key="w"><span>{{ w }}</span></div>
        </div>
        <div class="preloader__count">{{ count }}<span>%</span></div>
        <div class="preloader__bar"></div>
    </div>
</template>
