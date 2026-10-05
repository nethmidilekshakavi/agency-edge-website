<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PageHero from '../../Components/site/PageHero.vue';
import PostCard from '../../Components/site/PostCard.vue';
import CtaBlock from '../../Components/site/CtaBlock.vue';

const props = defineProps({
    content: { type: Object, required: true },
    posts: { type: Object, required: true }, // Laravel paginator
});
const c = props.content;
</script>

<template>
    <Head title="Insights" />
    <PageHero :label="c.insights.label" :title="c.insights.title" />
    <section class="section" style="padding-top: 0">
        <div class="container">
            <div v-if="posts.data.length" class="posts" v-reveal="{ children: true, stagger: 0.1 }">
                <PostCard v-for="p in posts.data" :key="p.slug" :post="p" />
            </div>
            <p v-else class="lead">{{ c.insights.empty }}</p>

            <nav v-if="posts.last_page > 1" class="pagination" aria-label="Pagination">
                <template v-for="link in posts.links" :key="link.label">
                    <Link v-if="link.url" :href="link.url" :class="{ 'is-active': link.active }" preserve-scroll v-html="link.label" />
                    <span v-else class="is-disabled" v-html="link.label" />
                </template>
            </nav>
        </div>
    </section>
    <CtaBlock :cta="c.cta" />
</template>
