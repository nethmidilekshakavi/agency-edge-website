<script setup>
import { Link } from '@inertiajs/vue3';
defineProps({ post: { type: Object, required: true } });
</script>

<template>
    <Link :href="`/insights/${post.slug}`" class="post-card" data-cursor="Read">
        <div class="post-card__media">
            <img v-if="post.cover" :src="post.cover" :alt="post.title" loading="lazy">
            <span v-else class="post-card__placeholder" aria-hidden="true">AE</span>
        </div>
        <div class="post-card__meta">
            <span v-if="post.category" class="accent">{{ post.category }}</span>
            <span>{{ post.date }}</span>
            <span>{{ post.minutes }} min read</span>
        </div>
        <h3 class="post-card__title">{{ post.title }}</h3>
        <p class="post-card__excerpt">{{ post.excerpt }}</p>
    </Link>
</template>
