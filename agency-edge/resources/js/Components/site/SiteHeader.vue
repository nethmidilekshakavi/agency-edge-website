<script setup>
import { ref, watch, onMounted, onBeforeUnmount, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { gsap, ScrollTrigger, lockScroll } from '../../lib/motion';
import Btn from './Btn.vue';
import { NAV } from '../../lib/nav';


const page = usePage();
const scrolled = ref(false);
const hidden = ref(false);
const open = ref(false);
const menu = ref(null);
const progress = ref(null);
let trigger;
let progressTween;
let menuTl;

const path = computed(() => (page.url || '/').split('?')[0]);
const isActive = (href) => (href === '/' ? path.value === '/' : path.value.startsWith(href));

onMounted(() => {
    trigger = ScrollTrigger.create({
        start: 0,
        end: 'max',
        onUpdate(self) {
            scrolled.value = self.scroll() > 40;
            hidden.value = !open.value && self.direction === 1 && self.scroll() > 400;
        },
    });
    progressTween = gsap.to(progress.value, {
        scaleX: 1, ease: 'none',
        scrollTrigger: { start: 0, end: 'max', scrub: 0.3 },
    });

    const q = gsap.utils.selector(menu.value);
    menuTl = gsap.timeline({ paused: true })
        .set(menu.value, { visibility: 'visible' })
        .to(menu.value, { clipPath: 'inset(0 0 0% 0)', duration: 0.8, ease: 'edgeInOut' })
        .from(q('.mobile-menu__links a span'), { yPercent: 110, stagger: 0.05, duration: 0.8 }, 0.3)
        .from(q('.mobile-menu__foot'), { autoAlpha: 0, y: 20, duration: 0.6 }, 0.5);
});

onBeforeUnmount(() => {
    trigger?.kill();
    progressTween?.scrollTrigger?.kill();
    progressTween?.kill();
    menuTl?.kill();
    lockScroll(false);
});

watch(open, (v) => {
    lockScroll(v);
    if (!menuTl) return;
    v ? menuTl.timeScale(1).play() : menuTl.timeScale(1.6).reverse();
});

// Close the menu whenever a visit starts.
const off = router.on('start', () => (open.value = false));
onBeforeUnmount(off);
</script>

<template>
    <div ref="progress" class="scroll-progress" aria-hidden="true"></div>
    <header class="header" :class="{ 'is-scrolled': scrolled || open, 'is-hidden': hidden }">
        <div class="container header__inner">
            <Link href="/" class="brand" aria-label="Agency Edge — home" data-cursor>
                <span class="brand__mark" aria-hidden="true"></span>Agency Edge
            </Link>
            <nav class="nav" aria-label="Main">
                <Link
                    v-for="item in NAV.slice(0, -1)"
                    :key="item.href"
                    :href="item.href"
                    class="nav__link"
                    :class="{ 'is-active': isActive(item.href) }"
                    :aria-current="isActive(item.href) ? 'page' : undefined"
                ><span>{{ item.label }}</span><span aria-hidden="true">{{ item.label }}</span></Link>
            </nav>
            <div class="header__cta">
                <Btn href="/contact" size="sm">Talk to Us</Btn>
                <button class="menu-btn" :class="{ 'is-open': open }" :aria-expanded="open" aria-controls="mobile-menu" @click="open = !open">
                    <span class="sr-only">{{ open ? 'Close menu' : 'Open menu' }}</span><i></i><i></i>
                </button>
            </div>
        </div>
    </header>
    <div id="mobile-menu" ref="menu" class="mobile-menu" :aria-hidden="!open">
        <nav class="mobile-menu__links" aria-label="Mobile">
            <Link v-for="item in NAV" :key="item.href" :href="item.href" :class="{ 'is-active': isActive(item.href) }" :tabindex="open ? 0 : -1">
                <span>{{ item.label }}</span>
            </Link>
        </nav>
        <div class="mobile-menu__foot">
            <span class="eyebrow">Marketing Meets Technology.</span>
            <Btn href="/contact" :magnetic="false">Start a Conversation</Btn>
        </div>
    </div>
</template>
