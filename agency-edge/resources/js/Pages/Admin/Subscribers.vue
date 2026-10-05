<script setup>
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({ subscribers: Object });
const fmt = (d) => new Date(d).toLocaleDateString(undefined, { dateStyle: 'medium' });
const remove = (s) => { if (confirm(`Remove ${s.email}?`)) router.delete(`/admin/subscribers/${s.id}`, { preserveScroll: true }); };
</script>

<template>
    <Head title="Subscribers" />
    <div class="admin__top"><h1>Subscribers</h1><a href="/admin/subscribers/export" class="a-btn">Export CSV</a></div>
    <div class="a-card a-table-wrap">
        <table class="a-table">
            <thead><tr><th>Email</th><th>Subscribed</th><th></th></tr></thead>
            <tbody>
                <tr v-for="s in subscribers.data" :key="s.id">
                    <td>{{ s.email }}</td>
                    <td class="muted">{{ fmt(s.created_at) }}</td>
                    <td style="text-align:right"><button class="a-btn a-btn--danger" @click="remove(s)">Remove</button></td>
                </tr>
                <tr v-if="!subscribers.data.length"><td colspan="3" class="muted">No subscribers yet.</td></tr>
            </tbody>
        </table>
    </div>
    <div v-if="subscribers.last_page > 1" style="display:flex; gap:6px; margin-top:16px; flex-wrap:wrap">
        <template v-for="link in subscribers.links" :key="link.label">
            <Link v-if="link.url" :href="link.url" class="a-btn" :class="{ 'a-btn--primary': link.active }" v-html="link.label" />
        </template>
    </div>
</template>
