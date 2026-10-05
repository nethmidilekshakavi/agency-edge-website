<script setup>
/**
 * "A customer asks. The brand responds. The system moves." (deck slide 8)
 * Chat plays out when scrolled into view, then the signal travels
 * AI Assistant → CRM → Automation → Team along drawn SVG paths.
 */
import { ref, onMounted, onBeforeUnmount, nextTick } from 'vue';
import { useMotion } from '../../composables/useMotion';
import { gsap } from '../../lib/motion';
import SectionHead from './SectionHead.vue';

const props = defineProps({ demo: { type: Object, required: true }, showHead: { type: Boolean, default: true } });
const root = ref(null);
const flow = ref(null);
const paths = ref([]);
const active = ref(-1);
const stage = ref(3); // 0 nothing, 1 customer, 2 typing, 3 reply (+ lead)

function measure() {
    const box = flow.value?.getBoundingClientRect();
    if (!box) return;
    const nodes = Array.from(flow.value.querySelectorAll('.flow__node')).map((n) => {
        const r = n.getBoundingClientRect();
        return { x: r.left - box.left + r.width / 2, y: r.top - box.top + r.height / 2 };
    });
    const out = [];
    for (let i = 0; i < nodes.length - 1; i++) {
        const a = nodes[i], b = nodes[i + 1];
        const mx = (a.x + b.x) / 2;
        out.push(`M${a.x},${a.y} C${mx},${a.y} ${mx},${b.y} ${b.x},${b.y}`);
    }
    paths.value = out;
}

let ro;
onMounted(async () => {
    await nextTick();
    measure();
    ro = new ResizeObserver(measure);
    ro.observe(flow.value);
});
onBeforeUnmount(() => ro?.disconnect());

useMotion(root, ({ q, reduced }) => {
    if (reduced) { active.value = 3; return; }
    stage.value = 0;
    active.value = -1;
    const nodeCount = props.demo.nodes.length;
    // Paths are measured after render, so look them up when each step runs.
    const travel = (i) => {
        const path = flow.value?.querySelector(`.flow__path-${i}`);
        const dot = flow.value?.querySelector('.flow__dot');
        if (!path || !dot) return;
        gsap.timeline()
            .fromTo(path, { drawSVG: '0% 0%' }, { drawSVG: '0% 100%', duration: 0.7, ease: 'power2.inOut' })
            .to(path, { drawSVG: '100% 100%', duration: 0.6, ease: 'power2.in' });
        gsap.set(dot, { autoAlpha: 1 });
        gsap.to(dot, { duration: 0.7, ease: 'power2.inOut', motionPath: { path, align: path, alignOrigin: [0.5, 0.5] } });
    };
    if (q('.flow__svg path').length) gsap.set(q('.flow__svg path'), { drawSVG: '0%' });
    gsap.set(q('.flow__dot'), { autoAlpha: 0 });

    const tl = gsap.timeline({ paused: true });
    tl.add(() => (stage.value = 1))
        .to({}, { duration: 0.6 })
        .add(() => (stage.value = 2))
        .to({}, { duration: 1.1 })
        .add(() => (stage.value = 3))
        .to({}, { duration: 0.5 });
    for (let i = 0; i < nodeCount; i++) {
        tl.add(() => (active.value = i));
        if (i < nodeCount - 1) tl.add(() => travel(i)).to({}, { duration: 0.75 });
    }

    gsap.from(q('.phone'), {
        y: 80, rotate: -4, autoAlpha: 0, duration: 1.4, ease: 'edge',
        scrollTrigger: { trigger: root.value, start: 'top 75%', once: true, onEnter: () => tl.play() },
    });
    gsap.from(q('.flow__node'), {
        y: 40, autoAlpha: 0, stagger: 0.1, duration: 1.1, ease: 'edge',
        scrollTrigger: { trigger: flow.value, start: 'top 85%', once: true },
    });
    return () => { const els = flow.value?.querySelectorAll('path, circle'); if (els?.length) gsap.killTweensOf(els); };
});
</script>

<template>
    <section ref="root" class="section" aria-labelledby="demo-title">
        <div class="container">
            <SectionHead v-if="showHead" :label="demo.label" :title="demo.title" />
            <div class="demo mt-l">
                <div class="phone" aria-label="Example conversation">
                    <div class="phone__screen">
                        <div class="phone__top">
                            <span class="phone__avatar">AE</span>
                            <div><div style="font-weight:600;font-size:14px">Brand assistant</div><div class="phone__status">Online</div></div>
                        </div>
                        <span class="bubble__meta">Customer</span>
                        <transition name="pop"><div v-if="stage >= 1" class="bubble bubble--in">{{ demo.customer_message }}</div></transition>
                        <transition name="pop"><div v-if="stage === 2" class="bubble bubble--out bubble--typing" aria-label="typing"><i></i><i></i><i></i></div></transition>
                        <transition name="pop"><div v-if="stage >= 3" class="bubble bubble--out">{{ demo.bot_reply }}</div></transition>
                        <transition name="pop"><div v-if="active >= 1" class="phone__lead"><b>Lead captured →</b> CRM · follow-up scheduled · team notified</div></transition>
                    </div>
                </div>
                <div ref="flow" class="flow">
                    <svg class="flow__svg" aria-hidden="true">
                        <path v-for="(d, i) in paths" :key="i" :d="d" :class="`flow__path-${i}`" fill="none" stroke="#ffc41c" stroke-width="2" style="stroke-dasharray: 0 100000" opacity=".9" />
                        <circle class="flow__dot" r="6" fill="#ffc41c" cx="0" cy="0" style="filter: drop-shadow(0 0 8px #ffc41c)" />
                    </svg>
                    <div v-for="(node, i) in demo.nodes" :key="node.name" class="card flow__node" :class="{ 'is-active': active >= i }">
                        <span class="pulse" aria-hidden="true"></span>
                        <span class="flow__step">0{{ i + 1 }}</span>
                        <div>
                            <div class="flow__name">{{ node.name }}</div>
                            <p class="flow__text">{{ node.text }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <p class="lead mt-l" style="max-width: 60ch" v-reveal>{{ demo.summary }}</p>
        </div>
    </section>
</template>

<style scoped>
.pop-enter-active { transition: transform 0.55s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.3s; transform-origin: bottom left; }
.bubble--out.pop-enter-active { transform-origin: bottom right; }
.pop-leave-active { transition: opacity 0.15s; position: absolute; }
.pop-enter-from { transform: scale(0.6) translateY(10px); opacity: 0; }
.pop-leave-to { opacity: 0; }
</style>
