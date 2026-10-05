/**
 * Central GSAP + Lenis setup. Every animated component imports from here so
 * plugins are registered once and smooth scrolling stays in sync with
 * ScrollTrigger.
 */
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';
import { ScrambleTextPlugin } from 'gsap/ScrambleTextPlugin';
import { DrawSVGPlugin } from 'gsap/DrawSVGPlugin';
import { MotionPathPlugin } from 'gsap/MotionPathPlugin';
import { CustomEase } from 'gsap/CustomEase';
import Lenis from 'lenis';

gsap.registerPlugin(ScrollTrigger, SplitText, ScrambleTextPlugin, DrawSVGPlugin, MotionPathPlugin, CustomEase);

CustomEase.create('edge', 'M0,0 C0.16,1 0.3,1 1,1');          // fast-out, long settle
CustomEase.create('edgeInOut', 'M0,0 C0.76,0 0.24,1 1,1');
gsap.defaults({ ease: 'edge', duration: 1 });
gsap.config({ nullTargetWarn: false }); // optional elements (e.g. a hero without a lead) are fine

export { gsap, ScrollTrigger, SplitText };

export const isBrowser = typeof window !== 'undefined';

export const prefersReducedMotion = () =>
    isBrowser && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

export const isTouch = () =>
    isBrowser && window.matchMedia('(hover: none), (pointer: coarse)').matches;

/* ---------------- Smooth scroll (Lenis) ---------------- */

let lenis = null;

export function startSmoothScroll() {
    if (!isBrowser || lenis || prefersReducedMotion()) return lenis;

    lenis = new Lenis({
        duration: 1.15,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        smoothWheel: true,
        // Native touch scrolling feels best on phones.
        syncTouch: false,
    });

    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add(tick);
    gsap.ticker.lagSmoothing(0);
    document.documentElement.classList.add('lenis');

    return lenis;
}

function tick(time) {
    lenis?.raf(time * 1000);
}

export function stopSmoothScroll() {
    if (!lenis) return;
    gsap.ticker.remove(tick);
    lenis.destroy();
    lenis = null;
    document.documentElement.classList.remove('lenis');
}

export const getLenis = () => lenis;

export function scrollToTarget(target, opts = {}) {
    if (lenis) lenis.scrollTo(target, { offset: -80, duration: 1.4, ...opts });
    else if (typeof target === 'number') window.scrollTo({ top: target, behavior: opts.immediate ? 'auto' : 'smooth' });
    else document.querySelector(target)?.scrollIntoView({ behavior: 'smooth' });
}

export function lockScroll(locked) {
    if (lenis) locked ? lenis.stop() : lenis.start();
    document.documentElement.classList.toggle('is-locked', locked);
}

/* ---------------- Page reveal gate ----------------
 * Entrance animations wait until the preloader / page transition has lifted,
 * so nothing plays hidden behind an overlay.
 */
let resolveReveal;
let revealPromise = new Promise((r) => (resolveReveal = r));

export const whenRevealed = () => revealPromise;

export function markCovered() {
    revealPromise = new Promise((r) => (resolveReveal = r));
}

export function markRevealed() {
    resolveReveal?.();
}

/** Refresh ScrollTrigger once fonts and images have settled. */
export function refreshSoon() {
    if (!isBrowser) return;
    requestAnimationFrame(() => ScrollTrigger.refresh());
    document.fonts?.ready.then(() => ScrollTrigger.refresh());
    setTimeout(() => ScrollTrigger.refresh(), 600);
}
