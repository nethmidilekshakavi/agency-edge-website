<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { humanize, SECTION_HINTS } from '../../../lib/admin';

defineProps({ sections: Array });
</script>

<template>
    <Head title="Website content" />
    <div class="admin__top"><h1>Website content</h1></div>
    <p class="muted" style="margin-top:-12px; margin-bottom: 24px; max-width: 70ch">
        Every block of copy on the site. Edits go live as soon as you save. "Reset" brings back the original copy from the customer deck.
    </p>
    <div class="a-stats">
        <Link v-for="s in sections" :key="s.key" :href="`/admin/content/${s.key}`" class="a-card" style="display:grid; gap:6px">
            <strong>{{ humanize(s.key) }}</strong>
            <span class="muted" style="font-size: 13px">{{ SECTION_HINTS[s.key] || '' }}</span>
            <span><span class="a-pill" :class="{ 'a-pill--accent': s.customised }">{{ s.customised ? `Edited ${s.updated_at}` : 'Default copy' }}</span></span>
        </Link>
    </div>
</template>
