<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import PageHero from '../Components/site/PageHero.vue';
import AiJobs from '../Components/site/AiJobs.vue';
import ChatDemo from '../Components/site/ChatDemo.vue';
import CtaBlock from '../Components/site/CtaBlock.vue';
import Btn from '../Components/site/Btn.vue';
import { useMotion } from '../composables/useMotion';
import { gsap } from '../lib/motion';

const props = defineProps({ content: { type: Object, required: true } });
const c = props.content;
const cases = ref(null);

// Use cases: pinned list where each row lights up as it passes the centre.
useMotion(cases, ({ q, reduced }) => {
    if (reduced) return;
    q('.usecase').forEach((row) => {
        gsap.fromTo(row, { opacity: 0.18 }, {
            opacity: 1, ease: 'none',
            scrollTrigger: { trigger: row, start: 'top 75%', end: 'top 45%', scrub: true },
        });
        gsap.from(row.querySelector('.usecase__bar'), {
            scaleX: 0, ease: 'none',
            scrollTrigger: { trigger: row, start: 'top 75%', end: 'top 45%', scrub: true },
        });
    });
});
</script>

<template>
    <Head title="AI for Marketing" />
    <PageHero :label="c.ai.label" :title="c.ai.page_title" :lead="c.ai.page_body">
        <Btn href="/contact?topic=AI%20for%20Marketing">{{ c.ai.cta }}</Btn>
    </PageHero>
    <AiJobs :ai="c.ai" />

    <section ref="cases" class="section" aria-labelledby="uc-title">
        <div class="container two-col">
            <div class="sticky-col">
                <p class="label">Use cases</p>
                <h2 id="uc-title" class="h2 mt-s" v-split>Give AI a useful job.</h2>
                <div class="mt-m"><Btn href="/contact?topic=AI%20for%20Marketing" variant="ghost">{{ c.ai.cta }}</Btn></div>
            </div>
            <ol style="list-style:none; margin:0; padding:0">
                <li v-for="(u, i) in c.ai.use_cases" :key="u.name" class="usecase" style="position:relative; padding: 34px 0; border-bottom: 1px solid var(--line)">
                    <span class="eyebrow">0{{ i + 1 }}</span>
                    <h3 class="h3 mt-s" style="font-size: clamp(24px, 2.6vw, 40px); text-transform: uppercase; font-weight: 800; letter-spacing: -0.035em">{{ u.name }}</h3>
                    <p class="muted" style="margin: 10px 0 0; font-size: 16px">{{ u.text }}</p>
                    <span class="usecase__bar" style="position:absolute; left:0; bottom:-1px; height:2px; width:100%; background: var(--accent); transform-origin:left; display:block"></span>
                </li>
            </ol>
        </div>
    </section>

    <ChatDemo :demo="c.demo" />
    <CtaBlock :cta="c.cta" />
</template>
