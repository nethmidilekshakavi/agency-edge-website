<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import Icon from './Icon.vue';

const props = defineProps({
    href: { type: String, default: null },
    variant: { type: String, default: 'primary' }, // primary | ghost
    size: { type: String, default: null },          // sm
    icon: { type: [String, Boolean], default: 'arrow' },
    external: { type: Boolean, default: false },
    type: { type: String, default: 'button' },
    magnetic: { type: [Number, Boolean], default: 0.3 },
});

const tag = computed(() => (props.href ? (props.external ? 'a' : Link) : 'button'));
const classes = computed(() => ['btn', `btn--${props.variant}`, props.size && `btn--${props.size}`]);
const bind = computed(() => {
    if (!props.href) return { type: props.type };
    return props.external ? { href: props.href, target: '_blank', rel: 'noopener' } : { href: props.href };
});
</script>

<template>
    <component :is="tag" :class="classes" v-bind="bind" v-magnetic="magnetic || 0" data-cursor>
        <span class="btn__fill" aria-hidden="true"></span>
        <span class="btn__label"><span><slot /></span><span aria-hidden="true"><slot /></span></span>
        <Icon v-if="icon" :name="icon" class="btn__icon" />
    </component>
</template>
