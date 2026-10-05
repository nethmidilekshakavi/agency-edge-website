<script setup>
defineProps({
    label: { type: String, default: '' },
    title: { type: String, required: true },
    lead: { type: String, default: '' },
    tag: { type: String, default: 'h2' },
    size: { type: String, default: 'h2' },
});
</script>

<template>
    <div class="flex-between">
        <div style="max-width: 1100px">
            <p v-if="label" class="label" v-reveal>{{ label }}</p>
            <component :is="tag" :class="size" class="mt-s" v-split>{{ title }}</component>
        </div>
        <p v-if="lead" class="lead" v-reveal="{ delay: 0.2 }">{{ lead }}</p>
        <slot />
    </div>
</template>
