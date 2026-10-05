import { onMounted, onBeforeUnmount } from 'vue';
import { gsap, whenRevealed, prefersReducedMotion } from '../lib/motion';

/**
 * Run GSAP code scoped to a component root. Everything created inside —
 * tweens, ScrollTriggers, SplitTexts, matchMedia — is reverted on unmount,
 * which is what keeps Inertia page swaps leak-free.
 *
 *   useMotion(rootRef, ({ root, reduced, q }) => { ... return optionalCleanup })
 *
 * Set { waitForReveal: false } for things that must exist immediately
 * (e.g. pinned sections whose spacing affects layout).
 */
export function useMotion(rootRef, setup, { waitForReveal = true } = {}) {
    let ctx;
    let cleanup;
    let alive = true;

    onMounted(async () => {
        if (waitForReveal) await whenRevealed();
        if (!alive || !rootRef.value) return;
        const root = rootRef.value;
        ctx = gsap.context(() => {
            cleanup = setup({
                root,
                reduced: prefersReducedMotion(),
                q: gsap.utils.selector(root),
            });
        }, root);
    });

    onBeforeUnmount(() => {
        alive = false;
        if (typeof cleanup === 'function') cleanup();
        ctx?.revert();
    });
}
