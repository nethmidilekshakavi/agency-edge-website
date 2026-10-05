<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';

const props = defineProps({ post: { type: Object, default: null } });
const p = props.post || {};

const form = useForm({
    title: p.title || '',
    slug: p.slug || '',
    category: p.category || '',
    excerpt: p.excerpt || '',
    body: p.body || '',
    meta_description: p.meta_description || '',
    published_at: p.published_at || '',
    cover: null,
    remove_cover: false,
});
const preview = ref(p.cover_url || null);

const onCover = (e) => {
    const file = e.target.files?.[0];
    form.cover = file || null;
    form.remove_cover = false;
    preview.value = file ? URL.createObjectURL(file) : p.cover_url || null;
};
const clearCover = () => { form.cover = null; form.remove_cover = true; preview.value = null; };
const publishNow = () => {
    const d = new Date();
    d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
    form.published_at = d.toISOString().slice(0, 16);
};

const save = () => {
    if (props.post) {
        // Files require multipart POST with method spoofing.
        form.transform((data) => ({ ...data, _method: 'put' })).post(`/admin/posts/${props.post.id}`, { forceFormData: true, preserveScroll: true });
    } else {
        form.post('/admin/posts', { forceFormData: true });
    }
};
const destroy = () => { if (confirm('Delete this article permanently?')) router.delete(`/admin/posts/${props.post.id}`); };
</script>

<template>
    <Head :title="post ? 'Edit article' : 'New article'" />
    <div class="admin__top">
        <div>
            <Link href="/admin/posts" class="muted" style="font-size:14px">← All articles</Link>
            <h1>{{ post ? 'Edit article' : 'New article' }}</h1>
        </div>
        <div style="display:flex; gap:8px">
            <button v-if="post" type="button" class="a-btn a-btn--danger" @click="destroy">Delete</button>
            <button type="button" class="a-btn a-btn--primary" :disabled="form.processing" @click="save">{{ form.processing ? 'Saving…' : 'Save' }}</button>
        </div>
    </div>
    <form class="a-grid-2" @submit.prevent="save">
        <div class="a-card">
            <div class="a-field">
                <label class="a-label" for="title">Title</label>
                <input id="title" v-model="form.title" class="a-input" style="font-size: 20px">
                <p v-if="form.errors.title" class="a-error">{{ form.errors.title }}</p>
            </div>
            <div class="a-field">
                <label class="a-label" for="excerpt">Excerpt (shown on cards)</label>
                <textarea id="excerpt" v-model="form.excerpt" class="a-textarea" rows="2" maxlength="400"></textarea>
            </div>
            <div class="a-field">
                <label class="a-label" for="body">Body — Markdown (## heading, **bold**, *italic*, - list, [link](https://…))</label>
                <textarea id="body" v-model="form.body" class="a-textarea a-textarea--code"></textarea>
                <p v-if="form.errors.body" class="a-error">{{ form.errors.body }}</p>
            </div>
        </div>
        <div style="display:grid; gap:20px">
            <div class="a-card">
                <div class="a-field">
                    <label class="a-label" for="pub">Publish date (empty = draft)</label>
                    <input id="pub" v-model="form.published_at" class="a-input" type="datetime-local">
                    <button type="button" class="a-btn" style="margin-top:8px" @click="publishNow">Publish now</button>
                    <p v-if="form.errors.published_at" class="a-error">{{ form.errors.published_at }}</p>
                </div>
                <div class="a-field">
                    <label class="a-label" for="cat">Category</label>
                    <input id="cat" v-model="form.category" class="a-input" placeholder="e.g. AI for Marketing">
                </div>
                <div class="a-field">
                    <label class="a-label" for="slug">URL slug (auto from title if empty)</label>
                    <input id="slug" v-model="form.slug" class="a-input">
                    <p v-if="form.errors.slug" class="a-error">{{ form.errors.slug }}</p>
                </div>
            </div>
            <div class="a-card">
                <label class="a-label">Cover image</label>
                <img v-if="preview" :src="preview" alt="" style="border-radius:10px; margin-bottom:10px; aspect-ratio:16/9; object-fit:cover; width:100%">
                <input type="file" accept="image/*" @change="onCover">
                <button v-if="preview" type="button" class="a-btn" style="margin-top:8px" @click="clearCover">Remove image</button>
                <p v-if="form.errors.cover" class="a-error">{{ form.errors.cover }}</p>
            </div>
            <div class="a-card">
                <label class="a-label" for="meta">Search description (SEO)</label>
                <textarea id="meta" v-model="form.meta_description" class="a-textarea" maxlength="300"></textarea>
            </div>
        </div>
    </form>
</template>
