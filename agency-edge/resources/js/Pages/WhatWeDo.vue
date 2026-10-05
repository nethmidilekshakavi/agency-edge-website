<script setup>
import { Head } from '@inertiajs/vue3';
import PageHero from '../Components/site/PageHero.vue';
import Disciplines from '../Components/site/Disciplines.vue';
import ServiceList from '../Components/site/ServiceList.vue';
import HorizontalSteps from '../Components/site/HorizontalSteps.vue';
import EngagementStack from '../Components/site/EngagementStack.vue';
import Marquee from '../Components/site/Marquee.vue';
import CtaBlock from '../Components/site/CtaBlock.vue';
import Btn from '../Components/site/Btn.vue';

const props = defineProps({ content: { type: Object, required: true } });
const c = props.content;
</script>

<template>
    <Head title="What We Do" />
    <PageHero label="What we do" title="One growth system." accent="Four disciplines." :lead="c.journey.summary">
        <Btn href="/contact">Tell Us Your Challenge</Btn>
    </PageHero>
    <Disciplines :disciplines="c.disciplines" :linked="false" />
    <Marquee :items="c.disciplines.items.flatMap((d) => d.points)" variant="accent" :speed="50" />
    <section class="section"><div class="container"><ServiceList :block="c.digital" /></div></section>
    <section class="section" style="padding-top: 0"><div class="container"><ServiceList :block="c.creative" /></div></section>
    <HorizontalSteps :label="c.process.label" :title="c.process.title" :steps="c.process.steps" summary="Strategy before execution — every time." cta-text="Start with Understand" />
    <EngagementStack :engagement="c.engagement" />
    <CtaBlock :cta="c.cta" />
</template>
