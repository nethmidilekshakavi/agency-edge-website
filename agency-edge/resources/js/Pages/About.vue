<script setup>
import { Head } from '@inertiajs/vue3';
import PageHero from '../Components/site/PageHero.vue';
import Founders from '../Components/site/Founders.vue';
import WhyProofs from '../Components/site/WhyProofs.vue';
import IndustryRows from '../Components/site/IndustryRows.vue';
import Equation from '../Components/site/Equation.vue';
import CtaBlock from '../Components/site/CtaBlock.vue';

const props = defineProps({ content: { type: Object, required: true } });
const c = props.content;
</script>

<template>
    <Head title="About" />
    <PageHero :label="c.about.label" title="Marketing." accent="Meets technology." :lead="c.about.positioning" />

    <section class="section section--tight">
        <div class="container two-col">
            <div class="sticky-col">
                <p class="label" v-reveal>Our belief</p>
                <h2 class="h2 mt-s" style="font-size: clamp(26px, 3vw, 46px)" v-split>{{ c.about.title }}</h2>
            </div>
            <div>
                <p v-for="(p, i) in c.about.paragraphs" :key="i" class="lead" style="max-width:none; margin-bottom: 1.2em" :style="i === 0 ? 'color: var(--text)' : ''" v-split>{{ p }}</p>
            </div>
        </div>
    </section>

    <section class="section section--tight">
        <div class="container">
            <blockquote class="big-quote" v-split="{ type: 'words' }">{{ c.about.quote }}</blockquote>
            <p class="eyebrow mt-m" v-reveal>— The co-founders</p>
        </div>
    </section>

    <Founders :founders="c.founders" full />
    <Equation :equation="c.equation" />
    <WhyProofs :why="c.why" />
    <IndustryRows :industries="c.industries" />
    <CtaBlock :cta="c.cta" />
</template>
