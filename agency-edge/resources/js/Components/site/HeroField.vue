<script setup>
/**
 * "Data streams" — golden light ribbons and particles flowing across a
 * dark field, echoing the deck's cinematic hero image. Plain 2D canvas
 * (no WebGL dependency), reacts to pointer position and scroll velocity,
 * pauses when off-screen, draws a single still frame for reduced motion.
 */
import { onMounted, onBeforeUnmount, ref } from 'vue';
import { ScrollTrigger, prefersReducedMotion, isTouch } from '../../lib/motion';

const props = defineProps({ density: { type: Number, default: 1 } });
const canvas = ref(null);

let raf = 0;
let running = false;
let io;
let cleanup = () => {};

onMounted(() => {
    const el = canvas.value;
    const ctx = el.getContext('2d');
    const reduced = prefersReducedMotion();
    const touch = isTouch();
    let w = 0, h = 0, dpr = 1;
    let t = 0;
    let boost = 0;
    const mouse = { x: 0.65, y: 0.45, tx: 0.65, ty: 0.45 };

    const streamCount = Math.round((touch ? 16 : 26) * props.density);
    const streams = Array.from({ length: streamCount }, (_, i) => ({
        base: 0.18 + (i / streamCount) * 0.7 + (Math.random() - 0.5) * 0.04,
        amp: 0.03 + Math.random() * 0.09,
        freq: 0.8 + Math.random() * 1.8,
        phase: Math.random() * Math.PI * 2,
        speed: 0.12 + Math.random() * 0.35,
        width: 0.4 + Math.random() * 1.4,
        alpha: 0.08 + Math.random() * 0.32,
    }));
    const particles = Array.from({ length: touch ? 50 : 110 }, () => spawn());

    function spawn() {
        return {
            s: Math.floor(Math.random() * streamCount),
            x: Math.random(),
            v: 0.0008 + Math.random() * 0.0022,
            r: 0.6 + Math.random() * 1.8,
            life: Math.random(),
        };
    }

    function streamY(s, x, time) {
        // Streams pinch toward a vanishing point on the right, like light trails.
        const pinch = 1 - Math.pow(x, 1.6) * 0.55;
        const center = 0.48 + (mouse.y - 0.5) * 0.12 * x;
        const wave = Math.sin(x * s.freq * Math.PI * 2 + s.phase + time * s.speed) * s.amp;
        const wave2 = Math.sin(x * 9 + time * 0.6 + s.phase) * 0.006;
        const pull = Math.exp(-Math.pow((x - mouse.x) * 4, 2)) * (mouse.y - s.base) * 0.18;
        return (center + (s.base - 0.5) * pinch + wave * pinch + wave2 + pull) * h;
    }

    function resize() {
        dpr = Math.min(window.devicePixelRatio || 1, touch ? 1.25 : 1.6);
        w = el.clientWidth || window.innerWidth; h = el.clientHeight || window.innerHeight;
        el.width = Math.floor(w * dpr); el.height = Math.floor(h * dpr);
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    }

    function draw() {
        mouse.x += (mouse.tx - mouse.x) * 0.05;
        mouse.y += (mouse.ty - mouse.y) * 0.05;
        boost *= 0.94;
        t += 0.016 * (1 + boost);

        ctx.globalCompositeOperation = 'source-over';
        ctx.fillStyle = 'rgba(10,10,11,0.34)';
        ctx.fillRect(0, 0, w, h);
        ctx.globalCompositeOperation = 'lighter';

        const steps = touch ? 48 : 80;
        for (const s of streams) {
            const grad = ctx.createLinearGradient(0, 0, w, 0);
            grad.addColorStop(0, 'rgba(255,196,28,0)');
            grad.addColorStop(0.35, `rgba(255,196,28,${s.alpha * 0.6})`);
            grad.addColorStop(0.8, `rgba(255,216,92,${s.alpha})`);
            grad.addColorStop(1, 'rgba(255,240,180,0)');
            ctx.strokeStyle = grad;
            ctx.lineWidth = s.width;
            ctx.beginPath();
            for (let i = 0; i <= steps; i++) {
                const x = i / steps;
                const y = streamY(s, x, t);
                i ? ctx.lineTo(x * w, y) : ctx.moveTo(0, y);
            }
            ctx.stroke();
        }

        for (const p of particles) {
            p.x += p.v * (1 + boost * 3);
            p.life += 0.004;
            if (p.x > 1.02) Object.assign(p, spawn(), { x: -0.02 });
            const s = streams[p.s];
            const y = streamY(s, p.x, t);
            if (!Number.isFinite(y) || !w) continue;
            const glow = ctx.createRadialGradient(p.x * w, y, 0, p.x * w, y, p.r * 6);
            glow.addColorStop(0, 'rgba(255,236,170,0.95)');
            glow.addColorStop(0.3, 'rgba(255,196,28,0.45)');
            glow.addColorStop(1, 'rgba(255,196,28,0)');
            ctx.fillStyle = glow;
            ctx.beginPath();
            ctx.arc(p.x * w, y, p.r * 6, 0, Math.PI * 2);
            ctx.fill();
        }
    }

    function loop() {
        if (!running) return;
        draw();
        raf = requestAnimationFrame(loop);
    }
    const start = () => { if (!running && !reduced) { running = true; raf = requestAnimationFrame(loop); } };
    const stop = () => { running = false; cancelAnimationFrame(raf); };

    const onMove = (e) => {
        const r = el.getBoundingClientRect();
        if (!r.width || !r.height) return;
        mouse.tx = (e.clientX - r.left) / r.width;
        mouse.ty = (e.clientY - r.top) / r.height;
    };
    const onVisibility = () => (document.hidden ? stop() : start());

    resize();
    // Paint a few frames immediately so the hero is never empty.
    for (let i = 0; i < (reduced ? 40 : 4); i++) draw();

    window.addEventListener('resize', resize);
    window.addEventListener('pointermove', onMove, { passive: true });
    document.addEventListener('visibilitychange', onVisibility);
    const st = ScrollTrigger.create({
        trigger: el, start: 'top bottom', end: 'bottom top',
        onUpdate: (self) => { const v = Math.abs(self.getVelocity()) / 900; if (Number.isFinite(v)) boost = Math.min(4, v); },
    });
    io = new IntersectionObserver(([entry]) => (entry.isIntersecting ? start() : stop()));
    io.observe(el);

    cleanup = () => {
        stop();
        io?.disconnect();
        st.kill();
        window.removeEventListener('resize', resize);
        window.removeEventListener('pointermove', onMove);
        document.removeEventListener('visibilitychange', onVisibility);
    };
});

onBeforeUnmount(() => cleanup());
</script>

<template>
    <canvas ref="canvas" class="hero__canvas" aria-hidden="true"></canvas>
</template>
