"use client";

import { useRef, useEffect } from "react";

const N_SPHERE = 700;
const N_BG     = 260;

function genCluster(n) {
    const pts = [];
    for (let i = 0; i < n; i++) {
        const u1 = Math.random(), u2 = Math.random();
        const z0 = Math.sqrt(-2 * Math.log(u1)) * Math.cos(2 * Math.PI * u2);
        const z1 = Math.sqrt(-2 * Math.log(u1)) * Math.sin(2 * Math.PI * u2);
        const z2 = Math.sqrt(-2 * Math.log(Math.random())) * Math.cos(2 * Math.PI * Math.random());
        const sig = 0.40;
        const x = z0 * sig, y = z1 * sig, z = z2 * sig;
        const dist = Math.sqrt(x*x + y*y + z*z);
        pts.push({
            x, y, z, dist,
            bright: dist < 0.18,
            gold:   dist < 0.48,
            size:   dist < 0.15 ? 3.4 : dist < 0.3 ? 2.4 : dist < 0.5 ? 1.6 : 1.1,
        });
    }
    return pts;
}

function genBg(n) {
    return Array.from({ length: n }, () => ({
        x: Math.random(), y: Math.random(),
        r: 0.5 + Math.random() * 1.8,
        c: Math.random() < 0.4 ? 0 : Math.random() < 0.5 ? 1 : 2,
        phase: Math.random() * Math.PI * 2,
        spd:   0.006 + Math.random() * 0.014,
    }));
}

const CLUSTER = genCluster(N_SPHERE);
const BG      = genBg(N_BG);

const ORBS = [
    { x: 0.72, y: 0.75, r: 0.32, col: [232,  67, 26], a: 0.12 },
    { x: 0.90, y: 0.28, r: 0.20, col: [200,  80, 20], a: 0.07 },
    { x: 0.08, y: 0.50, r: 0.24, col: [140,  28,  0], a: 0.09 },
    { x: 0.90, y: 0.32, r: 0.16, col: [255, 110, 40], a: 0.06 },
    { x: 0.18, y: 0.20, r: 0.22, col: [180,  40, 10], a: 0.055 },
    { x: 0.05, y: 0.80, r: 0.20, col: [120,  20,  0], a: 0.05 },
];

const ORBS_HOT = [
    { col: [255, 100, 30], a: 0.30 },
    { col: [255, 120, 20], a: 0.20 },
    { col: [220,  60,  0], a: 0.26 },
    { col: [255, 130, 50], a: 0.20 },
    { col: [240,  80, 20], a: 0.18 },
    { col: [200,  50,  0], a: 0.16 },
];

const PAR_BG      = 0.008;
const PAR_ORBS    = 0.018;
const PAR_CLUSTER = 0.038;

const lerp    = (a, b, t) => a + (b - a) * t;
const lerpRGB = (ca, cb, t) => [
    Math.round(lerp(ca[0], cb[0], t)),
    Math.round(lerp(ca[1], cb[1], t)),
    Math.round(lerp(ca[2], cb[2], t)),
];

export default function HeroParticles() {
    const canvasRef = useRef(null);
    const state = useRef({
        aY: 0, aX: 0.12,
        hovered: false, ctaHover: false,
        t: 0,
        rawMx: 0.5, rawMy: 0.5,
        mx: 0.5, my: 0.5,
        raf: null,
    });

    useEffect(() => {
        const canvas = canvasRef.current;
        if (!canvas) return;
        const ctx = canvas.getContext("2d");
        const s   = state.current;

        const resize = () => {
            canvas.width  = canvas.offsetWidth  * (window.devicePixelRatio || 1);
            canvas.height = canvas.offsetHeight * (window.devicePixelRatio || 1);
        };
        resize();
        window.addEventListener("resize", resize);

        const hero = canvas.parentElement;
        const onMove = e => {
            const rect = hero.getBoundingClientRect();
            s.rawMx = (e.clientX - rect.left) / rect.width;
            s.rawMy = (e.clientY - rect.top)  / rect.height;
        };
        hero.addEventListener("mousemove", onMove);
        hero.addEventListener("mouseleave", () => { s.rawMx = 0.5; s.rawMy = 0.5; });

        const loop = () => {
            const W   = canvas.width, H = canvas.height;
            const dpr = window.devicePixelRatio || 1;

            s.mx += (s.rawMx - s.mx) * 0.07;
            s.my += (s.rawMy - s.my) * 0.07;
            const offX = s.mx - 0.5;
            const offY = s.my - 0.5;

            const CX = (W * 0.62) + offX * W * PAR_CLUSTER;
            const CY = (H * 0.50) + offY * H * PAR_CLUSTER;
            const R  = Math.min(W, H) * 0.33;

            const tTarget = s.ctaHover ? 1 : 0;
            s.t += (tTarget - s.t) * 0.055;
            const t = s.t;

            s.aY += s.hovered ? 0.0015 : 0.0038;
            s.aX += s.hovered ? 0.0004 : 0.0009;
            const cY = Math.cos(s.aY), sY = Math.sin(s.aY);
            const cX = Math.cos(s.aX), sX = Math.sin(s.aX);

            // Background
            const bgR = Math.round(lerp(7,  32, t));
            const bgG = Math.round(lerp(7,   9, t));
            const bgB = Math.round(lerp(7,   2, t));
            ctx.fillStyle = `rgb(${bgR},${bgG},${bgB})`;
            ctx.fillRect(0, 0, W, H);

            // Vignette
            const vig = ctx.createRadialGradient(W*0.5/dpr, H*0.5/dpr, H*0.1/dpr, W*0.5/dpr, H*0.5/dpr, H*0.75/dpr);
            vig.addColorStop(0,   "rgba(0,0,0,0)");
            vig.addColorStop(0.6, `rgba(0,0,0,${lerp(0.08, 0.0, t).toFixed(3)})`);
            vig.addColorStop(1,   `rgba(0,0,0,${lerp(0.55, 0.30, t).toFixed(3)})`);
            ctx.fillStyle = vig;
            ctx.fillRect(0, 0, W/dpr, H/dpr);

            // Orbs
            ORBS.forEach((o, i) => {
                const hot   = ORBS_HOT[i];
                const col   = lerpRGB(o.col, hot.col, t);
                const alpha = lerp(o.a, hot.a, t);
                const ox = (o.x * W + offX * W * PAR_ORBS * W/H) / dpr;
                const oy = (o.y * H + offY * H * PAR_ORBS) / dpr;
                const or = lerp(o.r, o.r * 1.55, t) * Math.min(W, H) / dpr;
                const g  = ctx.createRadialGradient(ox, oy, 0, ox, oy, or);
                g.addColorStop(0,   `rgba(${col[0]},${col[1]},${col[2]},${alpha.toFixed(3)})`);
                g.addColorStop(0.5, `rgba(${col[0]},${col[1]},${col[2]},${(alpha*0.35).toFixed(3)})`);
                g.addColorStop(1,   `rgba(${col[0]},${col[1]},${col[2]},0)`);
                ctx.beginPath();
                ctx.arc(ox, oy, or, 0, Math.PI * 2);
                ctx.fillStyle = g;
                ctx.fill();
            });

            // CTA warm flood
            if (t > 0.01) {
                const wash = ctx.createRadialGradient(W*0.5/dpr, H*0.5/dpr, 0, W*0.5/dpr, H*0.5/dpr, Math.max(W,H)*0.80/dpr);
                wash.addColorStop(0,   `rgba(200,70,10,${(t * 0.16).toFixed(3)})`);
                wash.addColorStop(0.5, `rgba(150,35, 0,${(t * 0.10).toFixed(3)})`);
                wash.addColorStop(1,   "rgba(0,0,0,0)");
                ctx.fillStyle = wash;
                ctx.fillRect(0, 0, W/dpr, H/dpr);
            }

            // BG particles
            const bpts = BG.map(p => {
                p.phase += p.spd;
                const px = (p.x * W + offX * W * PAR_BG) / dpr;
                const py = (p.y * H + offY * H * PAR_BG) / dpr;
                return { px, py, r: p.r, c: p.c, a: 0.13 + Math.sin(p.phase) * 0.09 };
            });

            // Constellation lines
            for (let i = 0; i < bpts.length; i++) {
                for (let j = i + 1; j < bpts.length; j++) {
                    const dx = bpts[i].px - bpts[j].px;
                    const dy = bpts[i].py - bpts[j].py;
                    const d  = Math.sqrt(dx*dx + dy*dy);
                    if (d < 110) {
                        const base  = (1 - d/110) * 0.06;
                        const lineA = lerp(base, base * 3.2, t);
                        const lineR = Math.round(lerp(220, 255, t));
                        const lineG = Math.round(lerp(110,  85, t));
                        const lineB = Math.round(lerp( 40,  15, t));
                        ctx.beginPath();
                        ctx.moveTo(bpts[i].px, bpts[i].py);
                        ctx.lineTo(bpts[j].px, bpts[j].py);
                        ctx.strokeStyle = `rgba(${lineR},${lineG},${lineB},${lineA.toFixed(3)})`;
                        ctx.lineWidth = lerp(0.5, 1.3, t);
                        ctx.stroke();
                    }
                }
            }

            bpts.forEach(({ px, py, r, c, a }) => {
                ctx.beginPath();
                ctx.arc(px, py, r, 0, Math.PI * 2);
                if (c === 0) {
                    const rr = Math.round(lerp(232, 255, t)), gg = Math.round(lerp(67, 80, t)), bb = Math.round(lerp(26, 10, t));
                    ctx.fillStyle = `rgba(${rr},${gg},${bb},${lerp(a, a*2.2, t).toFixed(3)})`;
                } else if (c === 1) {
                    const rr = Math.round(lerp(210, 255, t)), gg = Math.round(lerp(140, 105, t)), bb = Math.round(lerp(20, 0, t));
                    ctx.fillStyle = `rgba(${rr},${gg},${bb},${lerp(a, a*2.0, t).toFixed(3)})`;
                } else {
                    const gg = Math.round(lerp(255, 155, t)), bb = Math.round(lerp(255, 75, t));
                    ctx.fillStyle = `rgba(255,${gg},${bb},${lerp(a*0.5, a*1.8, t).toFixed(3)})`;
                }
                ctx.fill();
            });

            // Central glow
            const glowA0 = lerp(0.20, 0.50, t);
            const glowA1 = lerp(0.09, 0.25, t);
            const gG0    = Math.round(lerp(220, 115, t));
            const gB0    = Math.round(lerp(120,  25, t));
            const glow   = ctx.createRadialGradient(CX/dpr, CY/dpr, 0, CX/dpr, CY/dpr, R*0.60/dpr);
            glow.addColorStop(0,   `rgba(255,${gG0},${gB0},${glowA0.toFixed(3)})`);
            glow.addColorStop(0.4, `rgba(${Math.round(lerp(212,240,t))},${Math.round(lerp(140,75,t))},0,${glowA1.toFixed(3)})`);
            glow.addColorStop(1,   "rgba(0,0,0,0)");
            ctx.beginPath();
            ctx.arc(CX/dpr, CY/dpr, R*0.60/dpr, 0, Math.PI * 2);
            ctx.fillStyle = glow;
            ctx.fill();

            // Cluster
            const proj = CLUSTER.map(p => {
                const x1 =  p.x * cY + p.z * sY;
                const z1 = -p.x * sY + p.z * cY;
                const y2 =  p.y * cX - z1 * sX;
                const z2 =  p.y * sX + z1 * cX;
                const fov = 3.5;
                const sc  = fov / (fov + z2);
                return {
                    sx: (CX + x1 * R * sc) / dpr,
                    sy: (CY + y2 * R * sc) / dpr,
                    depth: (z2 + 1) / 2,
                    sc, bright: p.bright, gold: p.gold, size: p.size,
                };
            });
            proj.sort((a, b) => a.depth - b.depth);

            proj.forEach(({ sx, sy, depth, sc, bright, gold, size }) => {
                const s2    = Math.max(0.4, sc * size * (0.5 + depth * 0.7));
                const alpha = 0.25 + depth * 0.75;

                if (bright) {
                    const bG = Math.round(lerp(250, 195, t));
                    const bB = Math.round(lerp(220, 115, t));
                    ctx.shadowColor = t > 0.3 ? "rgba(255,140,40,0.9)" : "rgba(255,240,180,0.9)";
                    ctx.shadowBlur  = lerp(8, 20, t);
                    ctx.beginPath();
                    ctx.arc(sx, sy, s2 * lerp(1.1, 1.45, t), 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(255,${bG},${bB},${alpha.toFixed(2)})`;
                    ctx.fill();
                    ctx.shadowBlur = 0;
                } else if (gold) {
                    const l   = Math.round(44 + depth * 26);
                    const hue = Math.round(lerp(40, 18, t));
                    const sat = Math.round(lerp(88, 96, t));
                    const lit = Math.round(lerp(l, Math.min(l + 16, 74), t));
                    ctx.shadowColor = depth > 0.6
                        ? `hsla(${hue},${sat}%,${lit}%,${lerp(0.4, 0.85, t).toFixed(2)})`
                        : "transparent";
                    ctx.shadowBlur  = depth > 0.6 ? lerp(4, 12, t) : 0;
                    ctx.beginPath();
                    ctx.arc(sx, sy, s2 * lerp(1, 1.18, t), 0, Math.PI * 2);
                    ctx.fillStyle = `hsla(${hue},${sat}%,${lit}%,${alpha.toFixed(2)})`;
                    ctx.fill();
                    ctx.shadowBlur = 0;
                } else {
                    const oR = Math.round(lerp(180, 225, t));
                    const oG = Math.round(lerp(130,  65, t));
                    const oB = Math.round(lerp( 50,   8, t));
                    ctx.beginPath();
                    ctx.arc(sx, sy, s2 * 0.85, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(${oR},${oG},${oB},${(alpha * lerp(0.5, 0.88, t)).toFixed(2)})`;
                    ctx.fill();
                }
            });

            // Spotlight / Torch
            const spotX = s.mx * W / dpr;
            const spotY = s.my * H / dpr;
            const spotR = Math.min(W, H) * 0.38 / dpr;

            const dark = ctx.createRadialGradient(spotX, spotY, spotR * 0.25, spotX, spotY, spotR * 1.20);
            dark.addColorStop(0,   "rgba(0,0,0,0)");
            dark.addColorStop(0.4, "rgba(0,0,0,0.18)");
            dark.addColorStop(1,   "rgba(0,0,0,0.60)");
            ctx.fillStyle = dark;
            ctx.fillRect(0, 0, W/dpr, H/dpr);

            const bloom = ctx.createRadialGradient(spotX, spotY, 0, spotX, spotY, spotR * 0.60);
            bloom.addColorStop(0,   `rgba(255,170,60,${lerp(0.22, 0.32, t).toFixed(3)})`);
            bloom.addColorStop(0.3, `rgba(232, 80,20,${lerp(0.10, 0.16, t).toFixed(3)})`);
            bloom.addColorStop(1,   "rgba(0,0,0,0)");
            ctx.fillStyle = bloom;
            ctx.fillRect(0, 0, W/dpr, H/dpr);

            s.raf = requestAnimationFrame(loop);
        };

        loop();
        return () => {
            cancelAnimationFrame(s.raf);
            window.removeEventListener("resize", resize);
            hero.removeEventListener("mousemove", onMove);
        };
    }, []);

    return (
        <div style={{
            position: "relative", width: "100%", height: "100vh",
            minHeight: "580px", overflow: "hidden", background: "#070707",
        }}>
            <canvas
                ref={canvasRef}
                onMouseEnter={() => state.current.hovered = true}
                onMouseLeave={() => state.current.hovered = false}
                style={{ position: "absolute", inset: 0, width: "100%", height: "100%", display: "block" }}
            />

            {/* Film grain */}
            <div style={{
                position: "absolute", inset: 0, zIndex: 1,
                pointerEvents: "none", opacity: 0.045, mixBlendMode: "overlay",
                backgroundImage: `url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='256' height='256'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/><feColorMatrix type='saturate' values='0'/></filter><rect width='256' height='256' filter='url(%23n)'/></svg>")`,
                backgroundSize: "200px 200px",
            }} />

            {/* Content */}
            <div style={{
                position: "absolute", inset: 0, zIndex: 2,
                display: "flex", alignItems: "center",
                pointerEvents: "none",
            }}>
                <div style={{ width: "100%", maxWidth: "1200px", margin: "0 auto", padding: "0 48px" }}>
                    <div style={{ maxWidth: "440px", pointerEvents: "auto" }}>

                        <h1 style={{
                            fontSize: "clamp(2.8rem, 5.5vw, 4.8rem)",
                            fontWeight: 900, lineHeight: 1.05,
                            color: "#fff", margin: "0 0 16px",
                            letterSpacing: "-0.02em",
                            textShadow: "0 2px 40px rgba(0,0,0,0.6)",
                        }}>
                            Phoenix<br />Whistleblowing<br />Software
                        </h1>

                        <div style={{
                            width: "64px", height: "4px", borderRadius: "2px",
                            background: "linear-gradient(90deg, #e8431a, #ff6b3d)",
                            margin: "0 0 20px",
                        }} />

                        <p style={{
                            fontSize: "1.05rem", color: "rgba(255,255,255,0.72)",
                            margin: "0 0 36px", lineHeight: 1.6,
                            letterSpacing: "0.01em",
                        }}>
                            Inspiring Integrity, Guiding Growth
                        </p>

                        <style>{`
                            @keyframes ctaPulse {
                                0%, 100% { box-shadow: 0 6px 24px rgba(232,67,26,0.45), 0 0 0 0 rgba(232,67,26,0.30); }
                                50%       { box-shadow: 0 6px 24px rgba(232,67,26,0.45), 0 0 0 10px rgba(232,67,26,0); }
                            }
                            @keyframes scrollLine {
                                0%,100% { opacity:0.25; transform:scaleY(0.4); transform-origin:top }
                                50%     { opacity:0.75; transform:scaleY(1);   transform-origin:top }
                            }
                        `}</style>

                        <a href="/get-started" style={{
                            display: "inline-block",
                            padding: "14px 32px", borderRadius: "8px",
                            background: "#e8431a", color: "#fff",
                            fontSize: "0.82rem", fontWeight: 800,
                            textDecoration: "none", letterSpacing: "0.07em",
                            textTransform: "uppercase",
                            animation: "ctaPulse 2.4s ease-in-out infinite",
                            transition: "background 0.2s, transform 0.2s, box-shadow 0.2s",
                            marginBottom: "16px",
                        }}
                            onMouseEnter={e => {
                                state.current.ctaHover = true;
                                e.currentTarget.style.animation = "none";
                                e.currentTarget.style.background = "#ff6b3d";
                                e.currentTarget.style.transform = "translateY(-2px)";
                                e.currentTarget.style.boxShadow = "0 10px 36px rgba(232,67,26,0.70)";
                            }}
                            onMouseLeave={e => {
                                state.current.ctaHover = false;
                                e.currentTarget.style.animation = "ctaPulse 2.4s ease-in-out infinite";
                                e.currentTarget.style.background = "#e8431a";
                                e.currentTarget.style.transform = "translateY(0)";
                                e.currentTarget.style.boxShadow = "";
                            }}
                        >
                            Get Started for Free
                        </a>

                        <p style={{ fontSize: "0.72rem", color: "rgba(255,255,255,0.32)", margin: 0 }}>
                            No credit card required · No hidden fees
                        </p>
                    </div>
                </div>
            </div>

            {/* Scroll indicator */}
            <div style={{
                position: "absolute", bottom: "28px", left: "50%",
                transform: "translateX(-50%)", zIndex: 2,
                display: "flex", flexDirection: "column", alignItems: "center", gap: "6px",
            }}>
                <span style={{ fontSize: "0.62rem", letterSpacing: "0.14em", color: "rgba(255,255,255,0.30)", textTransform: "uppercase" }}>Scroll</span>
                <div style={{
                    width: "1.5px", height: "40px",
                    background: "linear-gradient(to bottom, rgba(232,67,26,0.7), rgba(255,255,255,0.08))",
                    animation: "scrollLine 2s ease-in-out infinite",
                }} />
            </div>
        </div>
    );
}
