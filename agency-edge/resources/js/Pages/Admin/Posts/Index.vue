<script setup>
import { Head, Link } from '@inertiajs/vue3';
defineProps({ posts: Object });
</script>

<template>
    <Head title="Insights" />
    <div class="admin__top"><h1>Insights (blog)</h1><Link href="/admin/posts/create" class="a-btn a-btn--primary">+ New article</Link></div>
    <div class="a-card a-table-wrap">
        <table class="a-table">
            <thead><tr><th>Title</th><th>Category</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <tr v-for="p in posts.data" :key="p.id">
                    <td><Link :href="`/admin/posts/${p.id}/edit`"><strong>{{ p.title }}</strong></Link></td>
                    <td class="muted">{{ p.category || '—' }}</td>
                    <td>
                        <span v-if="p.is_live" class="a-pill a-pill--live">Live · {{ p.published_at }}</span>
                        <span v-else-if="p.published_at" class="a-pill a-pill--accent">Scheduled · {{ p.published_at }}</span>
                        <span v-else class="a-pill">Draft</span>
                    </td>
                    <td style="text-align:right; white-space:nowrap">
                        <a v-if="p.is_live" :href="`/insights/${p.slug}`" target="_blank" class="a-btn">View ↗</a>
                        <Link :href="`/admin/posts/${p.id}/edit`" class="a-btn" style="margin-left:6px">Edit</Link>
                    </td>
                </tr>
                <tr v-if="!posts.data.length"><td colspan="4" class="muted">No articles yet.</td></tr>
            </tbody>
        </table>
    </div>
</template>
