<script setup>
import { ref, computed } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { useMotion } from '../../composables/useMotion';
import { gsap } from '../../lib/motion';
import { NAV } from '../../lib/nav';
import Btn from './Btn.vue';
import Icon from './Icon.vue';

const page = usePage();
const site = computed(() => page.props.site || {});
const contact = computed(() => site.value.contact || {});
const flash = computed(() => page.props.flash || {});
const nlErrors = computed(() => page.props.errors?.newsletter || {});

const form = useForm({ email: '', website: '' });
const submit = () => form.post('/newsletter', {
    preserveScroll: true,
    errorBag: 'newsletter',
    onSuccess: () => form.reset('email'),
});

const root = ref(null);
const word = 'AGENCY EDGE'.split('');

useMotion(root, ({ q, reduced }) => {
    if (reduced) return;
    gsap.from(q('.footer__word span'), {
        yPercent: 100, stagger: 0.04, ease: 'edge', duration: 1.2,
        scrollTrigger: { trigger: q('.footer__word')[0], start: 'top 98%', end: 'bottom bottom', scrub: 1 },
    });
});
</script>

<template>
    <footer ref="root" class="footer">
        <div class="container">
            <div class="footer__grid">
                <div>
                    <h3>{{ site.newsletter?.title || 'Edge notes.' }}</h3>
                    <p class="muted" style="max-width: 40ch">{{ site.newsletter?.text }}</p>
                    <form class="newsletter" novalidate @submit.prevent="submit">
                        <label class="sr-only" for="nl-email">Email address</label>
                        <input id="nl-email" v-model="form.email" type="email" placeholder="you@company.com" autocomplete="email" required>
                        <div class="hp" aria-hidden="true"><input v-model="form.website" type="text" tabindex="-1" autocomplete="off"></div>
                        <Btn type="submit" size="sm" :magnetic="false" :icon="false" :disabled="form.processing">Subscribe</Btn>
                    </form>
                    <p class="form-note" :class="nlErrors.email ? 'form-note--err' : 'form-note--ok'" role="status">
                        {{ nlErrors.email || flash.newsletter || '' }}
                    </p>
                </div>
                <div>
                    <h3>Explore</h3>
                    <ul class="footer__links">
                        <li v-for="item in NAV" :key="item.href"><Link :href="item.href">{{ item.label }}</Link></li>
                    </ul>
                </div>
                <div>
                    <h3>Talk to us</h3>
                    <ul class="footer__links">
                        <li v-if="contact.email"><a :href="`mailto:${contact.email}`">{{ contact.email }}</a></li>
                        <li v-if="contact.phone"><a :href="`tel:${contact.phone.replace(/\s/g, '')}`">{{ contact.phone }}</a></li>
                        <li v-if="contact.address" class="muted">{{ contact.address }}</li>
                        <li v-if="contact.company_linkedin"><a :href="contact.company_linkedin" target="_blank" rel="noopener">LinkedIn</a></li>
                        <li><Link href="/contact" class="link-arrow">Start a Conversation <Icon name="arrow-up-right" style="width:16px;height:16px" /></Link></li>
                    </ul>
                    <p class="muted mt-m" style="font-size:14px">Strategy by Agency Edge.<br>Technology by
                        <a v-if="contact.ribelz_url" :href="contact.ribelz_url" target="_blank" rel="noopener" class="accent">Ribelz</a><span v-else>Ribelz</span>.
                    </p>
                </div>
            </div>
            <div class="footer__word" aria-hidden="true">
                <span v-for="(ch, i) in word" :key="i">{{ ch === ' ' ? ' ' : ch }}</span><i class="accent-sq"></i>
            </div>
            <div class="footer__bottom">
                <span>© {{ site.year || new Date().getFullYear() }} Agency Edge. All rights reserved.</span>
                <span>{{ site.meta?.positioning }}</span>
            </div>
        </div>
    </footer>
</template>
