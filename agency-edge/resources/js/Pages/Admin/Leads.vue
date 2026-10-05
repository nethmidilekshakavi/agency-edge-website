<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({ leads: Object, filters: Object });
const q = ref(props.filters.q || '');
const openId = ref(null);
const fmt = (d) => new Date(d).toLocaleString(undefined, { dateStyle: 'medium', timeStyle: 'short' });

let t;
const search = () => { clearTimeout(t); t = setTimeout(() => router.get('/admin/leads', { q: q.value }, { preserveState: true, replace: true }), 300); };
const toggleRead = (l) => router.patch(`/admin/leads/${l.id}`, {}, { preserveScroll: true });
const remove = (l) => { if (confirm(`Delete the enquiry from ${l.name}?`)) router.delete(`/admin/leads/${l.id}`, { preserveScroll: true }); };
const expand = (l) => {
    openId.value = openId.value === l.id ? null : l.id;
    if (openId.value && !l.read_at) toggleRead(l);
};
</script>

<template>
    <Head title="Enquiries" />
    <div class="admin__top">
        <h1>Enquiries</h1>
        <div style="display:flex; gap:8px; flex-wrap: wrap">
            <input v-model="q" class="a-input" style="width: 240px" placeholder="Search…" @input="search">
            <a href="/admin/leads/export" class="a-btn">Export CSV</a>
        </div>
    </div>
    <div class="a-card a-table-wrap">
        <table class="a-table">
            <thead><tr><th>Name</th><th>Company</th><th>Email / phone</th><th>Goal</th><th>Received</th><th></th></tr></thead>
            <tbody>
                <template v-for="l in leads.data" :key="l.id">
                    <tr :class="{ 'is-unread': !l.read_at }" style="cursor:pointer" @click="expand(l)">
                        <td><strong v-if="!l.read_at">{{ l.name }}</strong><span v-else>{{ l.name }}</span></td>
                        <td>{{ l.company || '—' }}</td>
                        <td><a :href="l.contact.includes('@') ? `mailto:${l.contact}` : `tel:${l.contact}`" @click.stop>{{ l.contact }}</a></td>
                        <td>{{ l.goal || '—' }}</td>
                        <td class="muted" style="white-space:nowrap">{{ fmt(l.created_at) }}</td>
                        <td style="white-space:nowrap" @click.stop>
                            <button class="a-btn" @click="toggleRead(l)">{{ l.read_at ? 'Mark unread' : 'Mark read' }}</button>
                            <button class="a-btn a-btn--danger" style="margin-left:6px" @click="remove(l)">Delete</button>
                        </td>
                    </tr>
                    <tr v-if="openId === l.id">
                        <td colspan="6" style="background: var(--bg)">
                            <p style="white-space: pre-wrap; margin: 0 0 8px">{{ l.message || 'No message.' }}</p>
                            <span class="muted" style="font-size: 13px">From: {{ l.source_page || '—' }}</span>
                        </td>
                    </tr>
                </template>
                <tr v-if="!leads.data.length"><td colspan="6" class="muted">No enquiries found.</td></tr>
            </tbody>
        </table>
    </div>
    <div v-if="leads.last_page > 1" style="display:flex; gap:6px; margin-top:16px; flex-wrap:wrap">
        <template v-for="link in leads.links" :key="link.label">
            <Link v-if="link.url" :href="link.url" class="a-btn" :class="{ 'a-btn--primary': link.active }" v-html="link.label" preserve-state />
        </template>
    </div>
</template>
