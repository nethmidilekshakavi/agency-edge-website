<script setup>
import { Head, Link } from '@inertiajs/vue3';
import HomeHero from '../Components/site/HomeHero.vue';
import Marquee from '../Components/site/Marquee.vue';
import ShiftStatement from '../Components/site/ShiftStatement.vue';
import Equation from '../Components/site/Equation.vue';
import Disciplines from '../Components/site/Disciplines.vue';
import HorizontalSteps from '../Components/site/HorizontalSteps.vue';
import MartechDuo from '../Components/site/MartechDuo.vue';
import AiJobs from '../Components/site/AiJobs.vue';
import ChatDemo from '../Components/site/ChatDemo.vue';
import IndustryRows from '../Components/site/IndustryRows.vue';
import Founders from '../Components/site/Founders.vue';
import WhyProofs from '../Components/site/WhyProofs.vue';
import EngagementStack from '../Components/site/EngagementStack.vue';
import CtaBlock from '../Components/site/CtaBlock.vue';
import PostCard from '../Components/site/PostCard.vue';
import SectionHead from '../Components/site/SectionHead.vue';
import Btn from '../Components/site/Btn.vue';

const props = defineProps({
    content: { type: Object, required: true },
    posts: { type: Array, default: () => [] },
});
const c = props.content;
</script>

<template>
    <Head title="" />
    <HomeHero :hero="c.hero" />
    <Marquee :items="c.hero.marquee" variant="accent" :speed="36" />

    <ShiftStatement :shift="c.shift" />

    <section class="section section--tight" aria-labelledby="intro-title">
        <div class="container two-col">
            <div>
                <p class="label" v-reveal>{{ c.intro.label }}</p>
                <h2 id="intro-title" class="h2 mt-s" v-split>{{ c.intro.title }}</h2>
            </div>
            <div>
                <p v-for="(p, i) in c.intro.paragraphs" :key="i" class="lead" style="max-width: none; margin-bottom: 1em" :style="i === 0 ? 'color: var(--text)' : ''" v-split="{ delay: i * 0.1 }">{{ p }}</p>
                <div class="mt-m" v-reveal><Btn href="/what-we-do" variant="ghost">{{ c.intro.cta }}</Btn></div>
            </div>
        </div>
    </section>

    <Equation :equation="c.equation" />
    <Disciplines :disciplines="c.disciplines" />
    <HorizontalSteps
        :label="c.journey.label"
        :title="c.journey.title"
        :steps="c.journey.stages"
        :summary="c.journey.summary"
        cta-text="Map my journey"
    />
    <MartechDuo :martech="c.martech" />
    <AiJobs :ai="c.ai" />
    <ChatDemo :demo="c.demo" />
    <Marquee :items="c.industries.items.map((i) => i.name)" variant="outline" reverse :speed="60" />
    <IndustryRows :industries="c.industries" />
    <Founders :founders="c.founders" />
    <WhyProofs :why="c.why" />
    <EngagementStack :engagement="c.engagement" />

    <section v-if="posts.length" class="section section--tight" aria-labelledby="ins-title">
        <div class="container">
            <SectionHead label="Insights" title="Edge thinking.">
                <Link href="/insights" class="link-arrow" v-reveal>All insights →</Link>
            </SectionHead>
            <div class="posts mt-l" v-reveal="{ children: true, stagger: 0.12 }">
                <PostCard v-for="p in posts" :key="p.slug" :post="p" />
            </div>
        </div>
    </section>

    <CtaBlock :cta="c.cta" />
</template>
