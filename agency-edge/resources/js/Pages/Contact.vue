<script setup>
import { computed, ref, watch, nextTick } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import PageHero from '../Components/site/PageHero.vue';
import Btn from '../Components/site/Btn.vue';
import Icon from '../Components/site/Icon.vue';
import { gsap, prefersReducedMotion, scrollToTarget } from '../lib/motion';

const props = defineProps({ content: { type: Object, required: true } });
const page = usePage();
const c = props.content;
const contact = computed(() => page.props.site?.contact || {});
const dismissed = ref(false);
const success = computed(() => (dismissed.value ? null : page.props.flash?.success));

// ?topic=… from service cards / engagement models pre-selects or pre-fills.
const topic = (() => {
    try { return new URL(page.url, 'http://x').searchParams.get('topic') || ''; } catch { return ''; }
})();
const goals = c.cta.goals || [];

const form = useForm({
    name: '',
    company: '',
    contact: '',
    goal: goals.includes(topic) ? topic : '',
    message: topic && !goals.includes(topic) ? `I'm interested in: ${topic}\n\n` : '',
    source_page: typeof window !== 'undefined' ? window.location.href : '',
    website: '', // honeypot
});

const submit = () => form.post('/contact', {
    preserveScroll: true,
    onSuccess: () => { form.reset(); dismissed.value = false; },
    onError: () => nextTick(() => scrollToTarget('.field__error', { offset: -160 })),
});

const check = ref(null);
watch(success, async (v) => {
    if (!v || prefersReducedMotion()) return;
    await nextTick();
    if (check.value) gsap.fromTo(check.value.querySelector('path'), { drawSVG: '0%' }, { drawSVG: '100%', duration: 0.9, ease: 'power3.out' });
});
</script>

<template>
    <Head title="Contact" />
    <PageHero label="Contact" :title="c.cta.contact_title" :lead="c.cta.contact_body" />

    <section class="section" style="padding-top: 0">
        <div class="container two-col">
            <aside class="sticky-col">
                <p class="h3" v-reveal>{{ c.cta.signoff }}</p>
                <ul class="footer__links mt-m" style="gap: 16px" v-reveal="{ children: true }">
                    <li v-if="contact.email"><a :href="`mailto:${contact.email}`" class="link-arrow"><Icon name="mail" style="width:18px" />{{ contact.email }}</a></li>
                    <li v-if="contact.phone"><a :href="`tel:${contact.phone.replace(/\s/g, '')}`" class="link-arrow"><Icon name="phone" style="width:18px" />{{ contact.phone }}</a></li>
                    <li v-if="contact.address" class="muted" style="display:flex; gap:10px"><Icon name="pin" style="width:18px; flex:none" />{{ contact.address }}</li>
                </ul>
                <div class="mt-l" v-reveal>
                    <p class="label">Ways to work together</p>
                    <ul class="footer__links mt-s">
                        <li v-for="m in c.engagement.models" :key="m.name"><strong>{{ m.name }}</strong><br><span class="muted" style="font-size:15px">{{ m.text }}</span></li>
                    </ul>
                </div>
            </aside>

            <div>
                <div v-if="success" ref="check" class="success" role="status">
                    <svg viewBox="0 0 64 64" fill="none"><circle cx="32" cy="32" r="30" stroke="#ffc41c" stroke-width="2" opacity=".35" /><path d="M18 33l9 9 19-20" stroke="#ffc41c" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" /></svg>
                    <p class="h3" style="margin:0">{{ success }}</p>
                    <button class="link-arrow" @click="dismissed = true">Send another message</button>
                </div>

                <form v-else class="form" novalidate @submit.prevent="submit" v-reveal="{ children: true, stagger: 0.07, y: 30 }">
                    <div class="form__row">
                        <div class="field">
                            <input id="f-name" v-model="form.name" type="text" placeholder=" " autocomplete="name" required>
                            <label for="f-name">Name *</label><span class="field__bar"></span>
                            <p v-if="form.errors.name" class="field__error">{{ form.errors.name }}</p>
                        </div>
                        <div class="field">
                            <input id="f-company" v-model="form.company" type="text" placeholder=" " autocomplete="organization">
                            <label for="f-company">Company</label><span class="field__bar"></span>
                            <p v-if="form.errors.company" class="field__error">{{ form.errors.company }}</p>
                        </div>
                    </div>
                    <div class="field">
                        <input id="f-contact" v-model="form.contact" type="text" placeholder=" " autocomplete="email" required>
                        <label for="f-contact">Email or phone *</label><span class="field__bar"></span>
                        <p v-if="form.errors.contact" class="field__error">{{ form.errors.contact }}</p>
                    </div>
                    <fieldset class="goal-picker">
                        <legend>What do you want to achieve?</legend>
                        <div class="goal-picker__opts">
                            <label v-for="g in goals" :key="g">
                                <input v-model="form.goal" type="radio" name="goal" :value="g">
                                <span>{{ g }}</span>
                            </label>
                        </div>
                        <p v-if="form.errors.goal" class="field__error">{{ form.errors.goal }}</p>
                    </fieldset>
                    <div class="field">
                        <textarea id="f-message" v-model="form.message" placeholder=" " rows="5"></textarea>
                        <label for="f-message">Message</label><span class="field__bar"></span>
                        <p v-if="form.errors.message" class="field__error">{{ form.errors.message }}</p>
                    </div>
                    <div class="hp" aria-hidden="true"><label>Website <input v-model="form.website" type="text" tabindex="-1" autocomplete="off"></label></div>
                    <div class="mt-s">
                        <Btn type="submit" :disabled="form.processing">{{ form.processing ? 'Sending…' : c.cta.primary }}</Btn>
                    </div>
                    <p v-if="Object.keys(form.errors).length" class="form-note form-note--err" role="alert">Please check the highlighted fields.</p>
                </form>
            </div>
        </div>
    </section>
</template>
