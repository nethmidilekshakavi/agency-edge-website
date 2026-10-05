<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import FieldEditor from '../../../Components/admin/FieldEditor.vue';
import { humanize, SECTION_HINTS } from '../../../lib/admin';

const props = defineProps({ section: String, value: Object, defaults: Object, customised: Boolean });

const form = useForm({ value: JSON.parse(JSON.stringify(props.value)) });
const save = () => form.put(`/admin/content/${props.section}`, { preserveScroll: true });
const reset = () => {
    if (!confirm('Reset this section to the original copy? Your edits to it will be lost.')) return;
    router.delete(`/admin/content/${props.section}`, {
        preserveScroll: true,
        onSuccess: (page) => { form.value = JSON.parse(JSON.stringify(page.props.value)); form.defaults(); },
    });
};
</script>

<template>
    <Head :title="`Edit ${humanize(section)}`" />
    <div class="admin__top">
        <div>
            <Link href="/admin/content" class="muted" style="font-size:14px">← All content</Link>
            <h1>{{ humanize(section) }}</h1>
            <p class="muted" style="margin:4px 0 0">{{ SECTION_HINTS[section] }}</p>
        </div>
        <div style="display:flex; gap:8px">
            <button v-if="customised" type="button" class="a-btn a-btn--danger" @click="reset">Reset to default</button>
            <button type="button" class="a-btn a-btn--primary" :disabled="form.processing || !form.isDirty" @click="save">{{ form.processing ? 'Saving…' : 'Save changes' }}</button>
        </div>
    </div>
    <form class="a-card" style="max-width: 900px" @submit.prevent="save">
        <FieldEditor v-for="key in Object.keys(defaults)" :key="key" :parent="form.value" :k="key" :tpl="defaults[key]" />
        <p v-if="Object.keys(form.errors).length" class="a-error">{{ Object.values(form.errors)[0] }}</p>
        <button class="a-btn a-btn--primary" :disabled="form.processing">Save changes</button>
    </form>
</template>
