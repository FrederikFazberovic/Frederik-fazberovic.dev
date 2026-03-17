/**
 * F.F. Homepage — Animations
 */

function splitIntoChars(el) {
    const text = el.textContent;
    el.innerHTML = '';
    text.split('').forEach((char) => {
        const span = document.createElement('span');
        span.textContent = char === ' ' ? '\u00A0' : char;
        span.style.cssText = 'display:inline-block; transition:none; will-change:transform;';
        el.appendChild(span);
    });
    return Array.from(el.children);
}

function rnd(min, max) {
    return Math.random() * (max - min) + min;
}

// ========================================
// Logo explosion — Web Animations API
// ========================================
function initLogoExplosion() {
    const logoText = document.querySelector('#logo-explosion .logo-text');
    if (!logoText) return;

    const chars = splitIntoChars(logoText);
    let animations = [];
    let exploding = false;
    let pendingAssemble = false;

    function assembleNow() {
        animations.forEach(a => a.cancel());
        animations = [];
        pendingAssemble = false;

        chars.forEach((char) => {
            const currentTransform = char.style.transform || 'translate(0px,0px) rotate(0deg)';
            const delay = Math.random() * 150;
            const anim = char.animate([
                { transform: currentTransform },
                { transform: 'translate(0px,0px) rotate(0deg)' }
            ], {
                duration: 800,
                delay: delay,
                easing: 'cubic-bezier(0.34, 1.56, 0.64, 1)',
                fill: 'forwards',
            });
            animations.push(anim);
            anim.addEventListener('finish', () => {
                char.style.transform = 'translate(0px,0px) rotate(0deg)';
            });
        });
    }

    logoText.parentElement.addEventListener('pointerenter', () => {
        pendingAssemble = false;
        animations.forEach(a => a.cancel());
        animations = [];
        exploding = true;

        let finished = 0;
        chars.forEach((char) => {
            const tx = rnd(-100, 100);
            const ty = rnd(-80, 80);
            const rot = rnd(-220, 220);
            const delay = Math.random() * 200;

            const anim = char.animate([
                { transform: char.style.transform || 'translate(0px,0px) rotate(0deg)' },
                { transform: `translate(${tx}px,${ty}px) rotate(${rot}deg)` }
            ], {
                duration: 700,
                delay: delay,
                easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)',
                fill: 'forwards',
            });
            animations.push(anim);

            anim.addEventListener('finish', () => {
                char.style.transform = `translate(${tx}px,${ty}px) rotate(${rot}deg)`;
                finished++;
                // All chars done exploding — if cursor already left, assemble now
                if (finished === chars.length) {
                    exploding = false;
                    if (pendingAssemble) assembleNow();
                }
            });
        });
    });

    logoText.parentElement.addEventListener('pointerleave', () => {
        if (exploding) {
            // Let explosion finish, then assemble
            pendingAssemble = true;
        } else {
            assembleNow();
        }
    });
}

// ========================================
// Initialize
// ========================================
document.addEventListener('DOMContentLoaded', () => {

    document.body.style.opacity = '0';
    anime({ targets: document.body, opacity: 1, duration: 400, easing: 'easeOutQuad' });

    // Links — slide right
    document.querySelectorAll('.links .link').forEach((link) => {
        link.addEventListener('mouseenter', () => {
            anime.remove(link);
            anime({ targets: link, translateX: 8, duration: 200, easing: 'easeOutQuad' });
        });
        link.addEventListener('mouseleave', () => {
            anime.remove(link);
            anime({ targets: link, translateX: 0, duration: 250, easing: 'easeOutQuad' });
        });
    });

    // Projects — scale up/down
    document.querySelectorAll('.project:not(.coming-soon)').forEach((project) => {
        project.addEventListener('mouseenter', () => {
            anime.remove(project);
            anime({ targets: project, scale: 1.02, duration: 200, easing: 'easeOutQuad' });
        });
        project.addEventListener('mouseleave', () => {
            anime.remove(project);
            anime({ targets: project, scale: 1, duration: 200, easing: 'easeOutQuad' });
        });
    });

    // Tech items stagger in
    const techItems = document.querySelectorAll('.tech-item');
    anime.set(techItems, { opacity: 0, translateY: 10 });
    setTimeout(() => {
        anime({
            targets:    techItems,
            opacity:    1,
            translateY: 0,
            duration:   400,
            delay:      anime.stagger(50),
            easing:     'easeOutQuad',
        });
    }, 500);

    initLogoExplosion();

    console.log('%c⟁ KiBar', 'font-family: monospace; font-size: 20px; font-weight: bold; color: #e63946;');
    console.log('%cMinimalist Developer Portfolio', 'font-family: monospace; font-size: 12px; color: #666;');

    document.querySelectorAll('a:not([href^="#"]):not([target="_blank"])').forEach((link) => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');
            if (href && !href.startsWith('http') && !href.startsWith('mailto')) {
                e.preventDefault();
                anime({
                    targets:  document.body,
                    opacity:  0,
                    duration: 150,
                    easing:   'easeInQuad',
                    complete: () => { window.location.href = href; },
                });
            }
        });
    });
});
