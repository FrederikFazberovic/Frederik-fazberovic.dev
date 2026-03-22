/**
 * F.F. Portfolio — Animations + Particles
 */

// ── Particles ────────────────────────────────────────
(function() {
    const canvas = document.getElementById('particles');
    const ctx = canvas.getContext('2d');
    let W, H, particles = [];

    function resize() {
        W = canvas.width  = window.innerWidth;
        H = canvas.height = window.innerHeight;
    }
    resize();
    window.addEventListener('resize', () => { resize(); init(); });

    class Particle {
        constructor(random) {
            this.x    = Math.random() * (W || 800);
            this.y    = random ? Math.random() * (H || 600) : (H || 600) + 10;
            this.vx   = (Math.random() - .5) * .25;
            this.vy   = -(Math.random() * .35 + .08);
            this.life = 0;
            this.max  = Math.random() * 220 + 80;
            this.size = Math.random() * 1.4 + .3;
            this.red  = Math.random() < .25;
        }
        update() {
            this.x += this.vx; this.y += this.vy; this.life++;
            if (this.life > this.max || this.y < -10) Object.assign(this, new Particle(false));
        }
        draw() {
            const a = Math.sin(this.life / this.max * Math.PI) * .55;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fillStyle = this.red
                ? `hsla(0,85%,55%,${a})`
                : `hsla(0,0%,70%,${a * .35})`;
            ctx.fill();
        }
    }

    function init() { particles = Array.from({length: 70}, () => new Particle(true)); }

    let mx = -999, my = -999;
    window.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });

    function loop() {
        ctx.clearRect(0, 0, W, H);
        particles.forEach(p => {
            // mouse repel
            const dx = p.x - mx, dy = p.y - my;
            const dist = Math.sqrt(dx*dx + dy*dy);
            if (dist < 90) {
                const f = (90 - dist) / 90 * .7;
                p.vx += dx/dist * f; p.vy += dy/dist * f;
                const sp = Math.sqrt(p.vx*p.vx + p.vy*p.vy);
                if (sp > 2) { p.vx = p.vx/sp*2; p.vy = p.vy/sp*2; }
            }
            p.update(); p.draw();
        });
        requestAnimationFrame(loop);
    }

    init(); loop();
})();

// ── Char split ───────────────────────────────────────
function splitIntoChars(el) {
    const text = el.textContent;
    el.innerHTML = '';
    text.split('').forEach(char => {
        const span = document.createElement('span');
        span.textContent = char === ' ' ? '\u00A0' : char;
        span.style.cssText = 'display:inline-block;transition:none;will-change:transform;';
        el.appendChild(span);
    });
    return Array.from(el.children);
}

function rnd(a, b) { return Math.random() * (b - a) + a; }

// ── Logo explosion (Web Animations API) ──────────────
function initLogoExplosion() {
    const logoText = document.querySelector('#logo-explosion .logo-text');
    if (!logoText) return;

    const chars = splitIntoChars(logoText);
    let anims = [], exploding = false, pending = false;

    function assemble() {
        anims.forEach(a => a.cancel()); anims = []; pending = false;
        chars.forEach(char => {
            const cur = char.style.transform || 'translate(0px,0px) rotate(0deg)';
            const a = char.animate([
                { transform: cur },
                { transform: 'translate(0px,0px) rotate(0deg)' }
            ], { duration: 800, delay: Math.random()*150, easing: 'cubic-bezier(0.34,1.56,0.64,1)', fill: 'forwards' });
            anims.push(a);
            a.addEventListener('finish', () => { char.style.transform = 'translate(0px,0px) rotate(0deg)'; });
        });
    }

    logoText.parentElement.addEventListener('pointerenter', () => {
        pending = false; anims.forEach(a => a.cancel()); anims = []; exploding = true;
        let done = 0;
        chars.forEach(char => {
            const tx = rnd(-100,100), ty = rnd(-80,80), rot = rnd(-220,220);
            const a = char.animate([
                { transform: char.style.transform || 'translate(0px,0px) rotate(0deg)' },
                { transform: `translate(${tx}px,${ty}px) rotate(${rot}deg)` }
            ], { duration: 700, delay: Math.random()*200, easing: 'cubic-bezier(0.25,0.46,0.45,0.94)', fill: 'forwards' });
            anims.push(a);
            a.addEventListener('finish', () => {
                char.style.transform = `translate(${tx}px,${ty}px) rotate(${rot}deg)`;
                done++;
                if (done === chars.length) { exploding = false; if (pending) assemble(); }
            });
        });
    });

    logoText.parentElement.addEventListener('pointerleave', () => {
        if (exploding) pending = true; else assemble();
    });
}

// ── Main init ────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {

    // Body fade in
    document.body.style.opacity = '0';
    anime({ targets: document.body, opacity: 1, duration: 400, easing: 'easeOutQuad' });

    // Links — slide right on hover
    document.querySelectorAll('.links .link').forEach(link => {
        link.addEventListener('mouseenter', () => {
            anime.remove(link);
            anime({ targets: link, translateX: 8, duration: 200, easing: 'easeOutQuad' });
        });
        link.addEventListener('mouseleave', () => {
            anime.remove(link);
            anime({ targets: link, translateX: 0, duration: 250, easing: 'easeOutQuad' });
        });
    });

    // Projects — scale
    document.querySelectorAll('.project:not(.coming-soon)').forEach(p => {
        p.addEventListener('mouseenter', () => {
            anime.remove(p);
            anime({ targets: p, scale: 1.02, duration: 200, easing: 'easeOutQuad' });
        });
        p.addEventListener('mouseleave', () => {
            anime.remove(p);
            anime({ targets: p, scale: 1, duration: 200, easing: 'easeOutQuad' });
        });
    });

    // Tech items stagger in
    const techItems = document.querySelectorAll('.tech-item');
    anime.set(techItems, { opacity: 0, translateY: 10 });
    setTimeout(() => {
        anime({
            targets: techItems, opacity: 1, translateY: 0,
            duration: 400, delay: anime.stagger(50), easing: 'easeOutQuad'
        });
    }, 500);

    initLogoExplosion();
});
