/**
 * Small, reusable motion directives:
 *   v-reveal      fade/slide elements in on scroll       v-reveal="{ y: 60, stagger: 0.1, children: true }"
 *   v-split       masked line/word/char text reveal       v-split="{ type: 'lines' }"
 *   v-magnetic    element drifts toward the cursor        v-magnetic="0.35"
 *   v-tilt        3D tilt + spotlight position vars       v-tilt="8"
 *   v-parallax    scroll-linked vertical drift            v-parallax="-15"
 *   v-scramble    scramble text on hover                  v-scramble
 */
import { gsap, ScrollTrigger, SplitText, whenRevealed, prefersReducedMotion, isTouch } from './motion';

const store = new WeakMap();
const keep = (el, cleanup) => store.set(el, [...(store.get(el) || []), cleanup]);
const clean = (el) => { (store.get(el) || []).forEach((fn) => fn?.()); store.delete(el); };

export const reveal = {
    async mounted(el, { value = {} }) {
        if (prefersReducedMotion()) return;
        const targets = value.children ? Array.from(el.children) : [el];
        gsap.set(targets, { autoAlpha: 0, y: value.y ?? 50, scale: value.scale ?? 1 });
        await whenRevealed();
        if (!el.isConnected) return;
        const tween = gsap.to(targets, {
            autoAlpha: 1, y: 0, scale: 1,
            duration: value.duration ?? 1.2,
            delay: value.delay ?? 0,
            stagger: value.stagger ?? 0.08,
            ease: 'edge',
            clearProps: 'transform',
            scrollTrigger: { trigger: el, start: value.start ?? 'top 88%', once: true },
        });
        keep(el, () => { tween.scrollTrigger?.kill(); tween.kill(); });
    },
    unmounted: clean,
};

export const split = {
    async mounted(el, { value = {} }) {
        if (prefersReducedMotion()) return;
        el.setAttribute('data-split', '');
        const type = value.type ?? 'lines';
        gsap.set(el, { autoAlpha: 0 });
        await whenRevealed();
        await document.fonts?.ready;
        if (!el.isConnected) return;
        gsap.set(el, { autoAlpha: 1 });

        const splitType = type === 'chars' ? 'chars,words,lines' : type === 'words' ? 'words,lines' : 'lines';
        const instance = SplitText.create(el, {
            type: splitType,
            mask: 'lines',
            linesClass: 'split-line',
            autoSplit: true,
            onSplit(self) {
                const targets = type === 'chars' ? self.chars : type === 'words' ? self.words : self.lines;
                return gsap.from(targets, {
                    yPercent: 115,
                    rotate: type === 'chars' ? 6 : 0,
                    duration: value.duration ?? 1.25,
                    delay: value.delay ?? 0,
                    stagger: value.stagger ?? (type === 'chars' ? 0.018 : type === 'words' ? 0.04 : 0.09),
                    ease: 'edge',
                    scrollTrigger: value.immediate ? undefined : { trigger: el, start: value.start ?? 'top 88%', once: true },
                });
            },
        });
        keep(el, () => instance.revert());
    },
    unmounted: clean,
};

export const magnetic = {
    mounted(el, { value }) {
        if (isTouch() || prefersReducedMotion()) return;
        const strength = typeof value === 'number' ? value : 0.35;
        const xTo = gsap.quickTo(el, 'x', { duration: 0.8, ease: 'elastic.out(1, 0.4)' });
        const yTo = gsap.quickTo(el, 'y', { duration: 0.8, ease: 'elastic.out(1, 0.4)' });
        const move = (e) => {
            const r = el.getBoundingClientRect();
            xTo((e.clientX - (r.left + r.width / 2)) * strength);
            yTo((e.clientY - (r.top + r.height / 2)) * strength);
        };
        const leave = () => { xTo(0); yTo(0); };
        el.addEventListener('pointermove', move);
        el.addEventListener('pointerleave', leave);
        keep(el, () => { el.removeEventListener('pointermove', move); el.removeEventListener('pointerleave', leave); gsap.set(el, { x: 0, y: 0 }); });
    },
    unmounted: clean,
};

export const tilt = {
    mounted(el, { value }) {
        const max = typeof value === 'number' ? value : 7;
        const move = (e) => {
            const r = el.getBoundingClientRect();
            const px = (e.clientX - r.left) / r.width;
            const py = (e.clientY - r.top) / r.height;
            el.style.setProperty('--mx', `${px * 100}%`);
            el.style.setProperty('--my', `${py * 100}%`);
            if (isTouch() || prefersReducedMotion()) return;
            gsap.to(el, { rotationY: (px - 0.5) * max * 2, rotationX: (0.5 - py) * max * 2, transformPerspective: 900, duration: 0.6, ease: 'power3.out' });
        };
        const leave = () => gsap.to(el, { rotationX: 0, rotationY: 0, duration: 1, ease: 'elastic.out(1, 0.5)' });
        el.setAttribute('data-tilt', '');
        el.addEventListener('pointermove', move);
        el.addEventListener('pointerleave', leave);
        keep(el, () => { el.removeEventListener('pointermove', move); el.removeEventListener('pointerleave', leave); });
    },
    unmounted: clean,
};

export const parallax = {
    async mounted(el, { value }) {
        if (prefersReducedMotion()) return;
        await whenRevealed();
        if (!el.isConnected) return;
        const amount = typeof value === 'number' ? value : -12;
        const tween = gsap.fromTo(el, { yPercent: -amount / 2 }, {
            yPercent: amount / 2, ease: 'none',
            scrollTrigger: { trigger: el.parentElement || el, start: 'top bottom', end: 'bottom top', scrub: true },
        });
        keep(el, () => { tween.scrollTrigger?.kill(); tween.kill(); });
    },
    unmounted: clean,
};

export const scramble = {
    mounted(el) {
        if (prefersReducedMotion()) return;
        const original = el.textContent;
        const enter = () => gsap.to(el, { duration: 0.6, scrambleText: { text: original, chars: 'upperCase', speed: 0.6, revealDelay: 0.1 } });
        el.addEventListener('pointerenter', enter);
        keep(el, () => el.removeEventListener('pointerenter', enter));
    },
    unmounted: clean,
};

export function installDirectives(app) {
    app.directive('reveal', reveal);
    app.directive('split', split);
    app.directive('magnetic', magnetic);
    app.directive('tilt', tilt);
    app.directive('parallax', parallax);
    app.directive('scramble', scramble);
}

export { ScrollTrigger };
