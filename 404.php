<?php http_response_code(404); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Frederik Fazberovič</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;700&family=Space+Grotesk:wght@300;400;700;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:     hsl(0,0%,4%);
            --fg:     hsl(0,0%,93%);
            --muted:  hsl(0,0%,40%);
            --red:    hsl(0,85%,55%);
            --red2:   hsl(15,85%,52%);
            --border: hsl(0,0%,11%);
            --mono:   'JetBrains Mono', monospace;
            --display:'Space Grotesk', sans-serif;
        }

        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

        html, body {
            width:100%; height:100%;
            overflow: hidden;
        }

        body {
            font-family: var(--mono);
            background: var(--bg);
            color: var(--fg);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            position: relative;
        }

        /* ── Grid background ── */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(var(--border) 1px, transparent 1px),
                linear-gradient(90deg, var(--border) 1px, transparent 1px);
            background-size: 44px 44px;
            mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 30%, transparent 100%);
            pointer-events: none;
        }

        /* ── Glitch scanlines ── */
        body::after {
            content: '';
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                0deg,
                transparent,
                transparent 2px,
                hsla(0,0%,0%,0.07) 2px,
                hsla(0,0%,0%,0.07) 4px
            );
            pointer-events: none;
            animation: scanMove 8s linear infinite;
        }

        @keyframes scanMove {
            from { background-position: 0 0; }
            to   { background-position: 0 100px; }
        }

        /* ── Canvas for particles ── */
        #canvas {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        /* ── Main content ── */
        .content {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 2rem;
            user-select: none;
        }

        /* ── 404 big number ── */
        .big-404 {
            font-family: var(--display);
            font-size: clamp(7rem, 22vw, 14rem);
            font-weight: 900;
            line-height: 0.9;
            position: relative;
            display: inline-block;
            letter-spacing: -0.04em;
            margin-bottom: 0.1em;
        }

        .big-404 .n {
            display: inline-block;
            color: transparent;
            -webkit-text-stroke: 1px var(--muted);
            transition: all 0.1s;
            animation: floatChar 4s ease-in-out infinite;
            position: relative;
        }

        .big-404 .n:nth-child(1) { animation-delay: 0s;    color: var(--red);  -webkit-text-stroke: 0; }
        .big-404 .n:nth-child(2) { animation-delay: 0.2s; }
        .big-404 .n:nth-child(3) { animation-delay: 0.4s;  color: var(--red2); -webkit-text-stroke: 0; }

        @keyframes floatChar {
            0%,100% { transform: translateY(0px); }
            50%      { transform: translateY(-8px); }
        }

        /* glitch effect on the outline 0 */
        .big-404 .n:nth-child(2) {
            animation: floatChar 4s ease-in-out infinite 0.2s, glitch 5s step-end infinite 1s;
        }

        @keyframes glitch {
            0%,95%,100% {
                transform: translateY(-8px) skew(0deg);
                -webkit-text-stroke: 1px var(--muted);
                opacity: 1;
            }
            96% {
                transform: translateY(-8px) skew(-8deg) translateX(-4px);
                -webkit-text-stroke: 1px var(--red);
                opacity: 0.7;
            }
            97% {
                transform: translateY(-8px) skew(5deg) translateX(6px);
                -webkit-text-stroke: 1px var(--red2);
                opacity: 0.9;
            }
            98% {
                transform: translateY(-8px) skew(-3deg) translateX(-2px);
                -webkit-text-stroke: 1px var(--muted);
                opacity: 0.6;
            }
        }

        /* ── Subtitle ── */
        .subtitle {
            font-size: clamp(0.65rem, 2vw, 0.8rem);
            color: var(--muted);
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: 2.5rem;
            opacity: 0;
            animation: fadeUp 0.6s ease 0.4s both;
        }

        .subtitle .slash { color: var(--red); margin-right: 0.4rem; }

        /* ── Error message ── */
        .error-line {
            font-size: 0.78rem;
            color: var(--muted);
            margin-bottom: 2.5rem;
            max-width: 340px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.7;
            opacity: 0;
            animation: fadeUp 0.6s ease 0.6s both;
        }

        .error-line .hl { color: var(--fg); }

        /* ── Terminal prompt ── */
        .terminal {
            display: inline-block;
            background: hsl(0,0%,7%);
            border: 1px solid var(--border);
            padding: 0.75rem 1.25rem;
            font-size: 0.75rem;
            color: var(--muted);
            margin-bottom: 2.5rem;
            text-align: left;
            min-width: 280px;
            opacity: 0;
            animation: fadeUp 0.6s ease 0.8s both;
            position: relative;
            overflow: hidden;
        }

        .terminal::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, var(--red), transparent);
        }

        .prompt { color: var(--red); margin-right: 0.5rem; }
        .cmd    { color: var(--fg); }
        .out    { display: block; margin-top: 0.3rem; padding-left: 1.1rem; }
        .cursor {
            display: inline-block;
            width: 7px; height: 1em;
            background: var(--red);
            vertical-align: middle;
            animation: blink 1s step-end infinite;
            margin-left: 2px;
            position: relative;
            bottom: 1px;
        }

        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0} }

        /* ── Back link ── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.78rem;
            padding: 0.6rem 1.25rem;
            border: 1px solid var(--border);
            transition: all 0.18s ease;
            opacity: 0;
            animation: fadeUp 0.6s ease 1s both;
            position: relative;
            overflow: hidden;
        }

        .back-link::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--red);
            transform: translateX(-101%);
            transition: transform 0.25s ease;
            z-index: -1;
        }

        .back-link:hover {
            color: #000;
            border-color: var(--red);
        }

        .back-link:hover::before {
            transform: translateX(0);
        }

        .back-link:hover .arrow { transform: translateX(4px); }

        .arrow { transition: transform 0.18s ease; }

        @keyframes fadeUp {
            from { opacity:0; transform: translateY(10px); }
            to   { opacity:1; transform: translateY(0); }
        }

        /* ── Red accent line left ── */
        .accent-bar {
            position: absolute;
            left: 0; top: 15%; bottom: 15%;
            width: 1px;
            background: linear-gradient(180deg, transparent, var(--red), transparent);
            opacity: 0.4;
        }

        /* ── Corner decorations ── */
        .corner {
            position: absolute;
            width: 20px; height: 20px;
            opacity: 0.3;
        }

        .corner::before, .corner::after {
            content: '';
            position: absolute;
            background: var(--red);
        }

        .corner::before { width: 100%; height: 1px; top: 0; }
        .corner::after  { width: 1px; height: 100%; }

        .c-tl { top: 24px; left: 24px; }
        .c-tr { top: 24px; right: 24px; transform: scaleX(-1); }
        .c-bl { bottom: 24px; left: 24px; transform: scaleY(-1); }
        .c-br { bottom: 24px; right: 24px; transform: scale(-1); }
    </style>
</head>
<body>
    <canvas id="canvas"></canvas>

    <div class="accent-bar"></div>
    <div class="corner c-tl"></div>
    <div class="corner c-tr"></div>
    <div class="corner c-bl"></div>
    <div class="corner c-br"></div>

    <div class="content">
        <div class="big-404">
            <span class="n">4</span><span class="n">0</span><span class="n">4</span>
        </div>

        <div class="subtitle">
            <span class="slash">//</span>page not found
        </div>

        <div class="error-line">
            the page you're looking for <span class="hl">doesn't exist</span>
            or has been moved somewhere else.
        </div>

        <div class="terminal">
            <span class="prompt">$</span><span class="cmd">curl https://frederik-fazberovic.dev<wbr>/???</span>
            <span class="out">→ <span style="color:var(--red)">Error 404</span> — resource not found</span>
            <span class="out">→ <span class="cursor"></span></span>
        </div>

        <a href="/" class="back-link">
            <span class="arrow">←</span> back to homepage
        </a>
    </div>

    <script>
        // ── Particle field ──────────────────
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        let W, H, particles = [];

        function resize() {
            W = canvas.width  = window.innerWidth;
            H = canvas.height = window.innerHeight;
        }
        resize();
        window.addEventListener('resize', () => { resize(); init(); });

        class Particle {
            constructor() { this.reset(true); }
            reset(random = false) {
                this.x  = Math.random() * W;
                this.y  = random ? Math.random() * H : H + 10;
                this.vx = (Math.random() - 0.5) * 0.3;
                this.vy = -(Math.random() * 0.4 + 0.1);
                this.life = 0;
                this.maxLife = Math.random() * 200 + 100;
                this.size = Math.random() * 1.5 + 0.3;
                this.red = Math.random() < 0.3;
            }
            update() {
                this.x += this.vx;
                this.y += this.vy;
                this.life++;
                if (this.life > this.maxLife || this.y < -10) this.reset();
            }
            draw() {
                const alpha = Math.sin((this.life / this.maxLife) * Math.PI) * 0.6;
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fillStyle = this.red
                    ? `hsla(0,85%,55%,${alpha})`
                    : `hsla(0,0%,70%,${alpha * 0.4})`;
                ctx.fill();
            }
        }

        function init() {
            particles = Array.from({ length: 80 }, () => new Particle());
        }

        function loop() {
            ctx.clearRect(0, 0, W, H);
            particles.forEach(p => { p.update(); p.draw(); });
            requestAnimationFrame(loop);
        }

        init();
        loop();

        // ── Mouse repel ──────────────────────
        let mx = -999, my = -999;
        window.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });

        const _origUpdate = Particle.prototype.update;
        Particle.prototype.update = function() {
            _origUpdate.call(this);
            const dx = this.x - mx, dy = this.y - my;
            const dist = Math.sqrt(dx*dx + dy*dy);
            if (dist < 80) {
                const force = (80 - dist) / 80 * 0.8;
                this.vx += (dx / dist) * force;
                this.vy += (dy / dist) * force;
                // clamp velocity
                const speed = Math.sqrt(this.vx*this.vx + this.vy*this.vy);
                if (speed > 2) { this.vx = this.vx/speed*2; this.vy = this.vy/speed*2; }
            }
        };
    </script>
</body>
</html>
