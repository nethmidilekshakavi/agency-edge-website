<script setup>
/**
 * Recursive editor that infers the form from the content's shape:
 * string → input/textarea (or image upload), list of strings → list,
 * list of objects → repeatable groups, object → nested group.
 * It edits `parent[k]` in place.
 */
import { computed, ref } from 'vue';
import { humanize, isImageKey, isLong, blankLike } from '../../lib/admin';

defineOptions({ name: 'FieldEditor' });

const props = defineProps({
    parent: { type: [Object, Array], required: true },
    k: { type: [String, Number], required: true },
    tpl: { default: undefined },       // default value, used for shape
    label: { type: String, default: '' },
});

const value = computed(() => props.parent[props.k]);
const shape = computed(() => (props.tpl !== undefined ? props.tpl : value.value));
const kind = computed(() => {
    const s = shape.value;
    if (Array.isArray(s)) {
        const first = s[0] ?? value.value?.[0];
        return first && typeof first === 'object' ? 'objects' : 'strings';
    }
    if (s && typeof s === 'object') return 'object';
    return 'string';
});
const title = computed(() => props.label || humanize(props.k));

// Make sure the value has the right container type.
if (kind.value === 'objects' || kind.value === 'strings') {
    if (!Array.isArray(props.parent[props.k])) props.parent[props.k] = [];
} else if (kind.value === 'object') {
    if (!props.parent[props.k] || typeof props.parent[props.k] !== 'object') props.parent[props.k] = blankLike(shape.value);
    for (const key of Object.keys(shape.value)) {
        if (!(key in props.parent[props.k])) props.parent[props.k][key] = blankLike(shape.value[key]);
    }
} else if (props.parent[props.k] == null) {
    props.parent[props.k] = '';
}

const itemTpl = computed(() => (Array.isArray(shape.value) ? shape.value[0] ?? value.value?.[0] : undefined));
const add = () => value.value.push(kind.value === 'objects' ? blankLike(itemTpl.value) : '');
const remove = (i) => value.value.splice(i, 1);
const move = (i, d) => {
    const j = i + d;
    if (j < 0 || j >= value.value.length) return;
    const [x] = value.value.splice(i, 1);
    value.value.splice(j, 0, x);
};

const uploading = ref(false);
const uploadError = ref('');
async function upload(e) {
    const file = e.target.files?.[0];
    if (!file) return;
    uploading.value = true; uploadError.value = '';
    const body = new FormData();
    body.append('file', file);
    const token = decodeURIComponent((document.cookie.match(/XSRF-TOKEN=([^;]+)/) || [])[1] || '');
    try {
        const res = await fetch('/admin/uploads', { method: 'POST', body, headers: { 'X-XSRF-TOKEN': token, Accept: 'application/json' }, credentials: 'same-origin' });
        if (!res.ok) throw new Error((await res.json().catch(() => ({}))).message || 'Upload failed');
        props.parent[props.k] = (await res.json()).url;
    } catch (err) {
        uploadError.value = err.message;
    } finally {
        uploading.value = false;
        e.target.value = '';
    }
}
</script>

<template>
    <!-- Plain text -->
    <div v-if="kind === 'string'" class="a-field">
        <label class="a-label">{{ title }}</label>
        <div v-if="isImageKey(k)" class="a-photo">
            <img v-if="value" :src="value" alt="">
            <input type="file" accept="image/*" @change="upload">
            <button v-if="value" type="button" class="a-btn" @click="parent[k] = ''">Remove</button>
            <span v-if="uploading" class="muted">Uploading…</span>
            <span v-if="uploadError" class="a-error">{{ uploadError }}</span>
        </div>
        <textarea v-else-if="isLong(k, value || shape)" v-model="parent[k]" class="a-textarea" rows="3"></textarea>
        <input v-else v-model="parent[k]" class="a-input" type="text">
    </div>

    <!-- List of strings -->
    <div v-else-if="kind === 'strings'" class="a-group">
        <div class="a-group__head"><span class="a-group__title">{{ title }}</span><button type="button" class="a-btn" @click="add">+ Add</button></div>
        <div v-for="(item, i) in value" :key="i" class="a-list-item">
            <input v-model="value[i]" class="a-input" type="text">
            <div class="a-list-item__tools">
                <button type="button" class="a-icon-btn" title="Move up" @click="move(i, -1)">↑</button>
                <button type="button" class="a-icon-btn" title="Move down" @click="move(i, 1)">↓</button>
                <button type="button" class="a-icon-btn" title="Remove" @click="remove(i)">✕</button>
            </div>
        </div>
    </div>

    <!-- List of objects -->
    <div v-else-if="kind === 'objects'" class="a-group">
        <div class="a-group__head"><span class="a-group__title">{{ title }}</span><button type="button" class="a-btn" @click="add">+ Add item</button></div>
        <div v-for="(item, i) in value" :key="i" class="a-group" style="background: var(--bg)">
            <div class="a-group__head">
                <span class="muted" style="font-size: 13px">#{{ i + 1 }} {{ item.name || item.title || '' }}</span>
                <div class="a-list-item__tools">
                    <button type="button" class="a-icon-btn" title="Move up" @click="move(i, -1)">↑</button>
                    <button type="button" class="a-icon-btn" title="Move down" @click="move(i, 1)">↓</button>
                    <button type="button" class="a-icon-btn" title="Remove" @click="remove(i)">✕</button>
                </div>
            </div>
            <FieldEditor v-for="key in Object.keys(itemTpl || item)" :key="key" :parent="item" :k="key" :tpl="(itemTpl || item)[key]" />
        </div>
    </div>

    <!-- Nested object -->
    <div v-else class="a-group">
        <div class="a-group__head"><span class="a-group__title">{{ title }}</span></div>
        <FieldEditor v-for="key in Object.keys(shape)" :key="key" :parent="value" :k="key" :tpl="shape[key]" />
    </div>
</template>
