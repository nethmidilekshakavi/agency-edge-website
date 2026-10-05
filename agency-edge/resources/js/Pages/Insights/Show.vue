<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PostCard from '../../Components/site/PostCard.vue';
import CtaBlock from '../../Components/site/CtaBlock.vue';
import { useMotion } from '../../composables/useMotion';
import { gsap, SplitText } from '../../lib/motion';

const props = defineProps({
    post: { type: Object, required: true },
    more: { type: Array, default: () => [] },
    content: { type: Object, required: true },
});
const root = ref(null);

useMotion(root, ({ q, reduced }) => {
    if (reduced) return;
    const split = SplitText.create(q('h1')[0], { type: 'words,lines', mask: 'lines', linesClass: 'split-line' });
    gsap.from(split.words, { yPercent: 110, stagger: 0.03, duration: 1.2 });
    gsap.from(q('.post-meta, .post-cover'), { autoAlpha: 0, y: 30, stagger: 0.15, duration: 1.1, delay: 0.3 });
    gsap.to(q('.read-progress'), {
        scaleX: 1, ease: 'none',
        scrollTrigger: { trigger: q('.prose')[0], start: 'top 30%', end: 'bottom 70%', scrub: true },
    });
    q('.prose > *').forEach((el) => gsap.from(el, {
        y: 24, autoAlpha: 0, duration: 0.9,
        scrollTrigger: { trigger: el, start: 'top 92%', once: true },
    }));
    return () => split.revert();
});
</script>

<template>
    <Head :title="post.title" />
    <article ref="root">
        <div class="read-progress" style="position:fixed; left:0; right:0; top:0; height:3px; background: var(--accent); transform-origin:left; transform: scaleX(0); z-index: 102"></div>
        <header class="page-hero" style="padding-bottom: 40px">
            <div class="container" style="max-width: 1000px">
                <Link href="/insights" class="eyebrow">← Insights</Link>
                <h1 class="h2 mt-m" style="text-transform: none; letter-spacing: -0.035em">{{ post.title }}</h1>
                <div class="post-card__meta post-meta mt-m">
                    <span v-if="post.category" class="accent">{{ post.category }}</span>
                    <span>{{ post.date }}</span>
                    <span>{{ post.minutes }} min read</span>
                </div>
            </div>
        </header>
        <div v-if="post.cover" class="container post-cover" style="max-width: 1200px; margin-bottom: 60px">
            <img :src="post.cover" :alt="post.title" style="width:100%; border-radius: var(--radius-lg); aspect-ratio: 16/8; object-fit: cover">
        </div>
        <div class="container">
            <div class="prose" v-html="post.html"></div>
        </div>
    </article>

    <section v-if="more.length" class="section section--tight">
        <div class="container">
            <p class="label">Keep reading</p>
            <div class="posts mt-m" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr))">
                <PostCard v-for="p in more" :key="p.slug" :post="p" />
            </div>
        </div>
    </section>
    <CtaBlock :cta="content.cta" />
</template>
