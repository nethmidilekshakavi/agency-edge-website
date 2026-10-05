<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({ stats: Object, recentLeads: Array });
const fmt = (d) => new Date(d).toLocaleString(undefined, { dateStyle: 'medium', timeStyle: 'short' });
</script>

<template>
    <Head title="Admin" />
    <div class="admin__top"><h1>Dashboard</h1><a href="/" target="_blank" class="a-btn">View website ↗</a></div>
    <div class="a-stats">
        <Link href="/admin/leads" class="a-card a-stat"><b>{{ stats.unread }}</b><span>Unread enquiries ({{ stats.leads }} total)</span></Link>
        <Link href="/admin/subscribers" class="a-card a-stat"><b>{{ stats.subscribers }}</b><span>Newsletter subscribers</span></Link>
        <Link href="/admin/posts" class="a-card a-stat"><b>{{ stats.posts }}</b><span>Published articles ({{ stats.drafts }} drafts)</span></Link>
        <Link href="/admin/content" class="a-card a-stat"><b>✎</b><span>Edit website content</span></Link>
    </div>
    <div class="a-card">
        <div class="admin__top" style="margin-bottom: 10px"><h2 style="margin:0; font-size:18px">Latest enquiries</h2><Link href="/admin/leads" class="a-btn">All enquiries</Link></div>
        <div class="a-table-wrap">
            <table class="a-table">
                <thead><tr><th>Name</th><th>Company</th><th>Goal</th><th>Received</th></tr></thead>
                <tbody>
                    <tr v-for="l in recentLeads" :key="l.id" :class="{ 'is-unread': !l.read_at }">
                        <td>{{ l.name }}</td><td>{{ l.company || '—' }}</td><td>{{ l.goal || '—' }}</td><td class="muted">{{ fmt(l.created_at) }}</td>
                    </tr>
                    <tr v-if="!recentLeads.length"><td colspan="4" class="muted">No enquiries yet.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
