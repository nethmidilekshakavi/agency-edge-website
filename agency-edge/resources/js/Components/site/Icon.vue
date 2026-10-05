<script setup>
defineProps({ name: { type: String, required: true } });
</script>

<template>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <template v-if="name === 'arrow'"><path d="M5 12h14M13 6l6 6-6 6" /></template>
        <template v-else-if="name === 'arrow-up-right'"><path d="M7 17 17 7M8 7h9v9" /></template>
        <template v-else-if="name === 'star'"><path d="M12 2v20M2 12h20M5 5l14 14M19 5 5 19" /></template>
        <template v-else-if="name === 'chat'"><path d="M21 12a8 8 0 0 1-11.6 7.1L4 20l1-4.6A8 8 0 1 1 21 12Z" /></template>
        <template v-else-if="name === 'target'"><circle cx="12" cy="12" r="9" /><circle cx="12" cy="12" r="5" /><circle cx="12" cy="12" r="1" /></template>
        <template v-else-if="name === 'bolt'"><path d="M13 2 4 14h7l-1 8 9-12h-7l1-8Z" /></template>
        <template v-else-if="name === 'spark'"><path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.5 2.5M15.5 15.5 18 18M6 18l2.5-2.5M15.5 8.5 18 6" /></template>
        <template v-else-if="name === 'linkedin'"><path d="M4 9h3v11H4zM5.5 4a1.8 1.8 0 1 1 0 3.6 1.8 1.8 0 0 1 0-3.6ZM10 9h3v1.6c.5-.9 1.7-1.8 3.4-1.8 3 0 3.6 2 3.6 4.6V20h-3v-5.8c0-1.4 0-3-1.9-3s-2.1 1.4-2.1 2.9V20h-3V9Z" fill="currentColor" stroke="none" /></template>
        <template v-else-if="name === 'check'"><path d="M4 12.5 9.5 18 20 6" /></template>
        <template v-else-if="name === 'mail'"><rect x="3" y="5" width="18" height="14" rx="2" /><path d="m3 7 9 6 9-6" /></template>
        <template v-else-if="name === 'phone'"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2" /></template>
        <template v-else-if="name === 'pin'"><path d="M12 21s-7-6.1-7-11a7 7 0 1 1 14 0c0 4.9-7 11-7 11Z" /><circle cx="12" cy="10" r="2.5" /></template>
    </svg>
</template>
