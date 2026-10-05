<script setup>
import { Head } from '@inertiajs/vue3';
import PageHero from '../Components/site/PageHero.vue';
import MartechDuo from '../Components/site/MartechDuo.vue';
import ChatDemo from '../Components/site/ChatDemo.vue';
import HorizontalSteps from '../Components/site/HorizontalSteps.vue';
import CtaBlock from '../Components/site/CtaBlock.vue';
import Btn from '../Components/site/Btn.vue';

const props = defineProps({ content: { type: Object, required: true } });
const c = props.content;
</script>

<template>
    <Head title="MarTech Consulting" />
    <PageHero :label="c.martech.label" :title="c.martech.title_a" :accent="c.martech.title_b" :lead="c.martech.lead">
        <Btn href="/contact?topic=MarTech%20Transformation">Talk MarTech</Btn>
    </PageHero>

    <section class="section section--tight">
        <div class="container two-col">
            <div class="sticky-col">
                <p class="label" v-reveal>Why it matters</p>
                <h2 class="h2 mt-s" style="font-size: clamp(32px, 4.2vw, 64px)" v-split>{{ c.martech.heading }}</h2>
            </div>
            <div>
                <p class="lead" style="max-width:none; color: var(--text)" v-split>{{ c.martech.body }}</p>
                <div class="cap-grid mt-l" v-reveal="{ children: true, stagger: 0.05, y: 30 }">
                    <div v-for="cap in c.martech.capabilities" :key="cap" class="cap"><i aria-hidden="true"></i>{{ cap }}</div>
                </div>
            </div>
        </div>
    </section>

    <MartechDuo :martech="c.martech" :cta="false" />
    <ChatDemo :demo="c.demo" />
    <HorizontalSteps :label="c.process.label" :title="c.process.title" :steps="c.process.steps" summary="Strategy by Agency Edge. Technology by Ribelz." cta-text="Start a MarTech project" cta-href="/contact?topic=MarTech%20Transformation" />
    <CtaBlock :cta="c.cta" />
</template>
