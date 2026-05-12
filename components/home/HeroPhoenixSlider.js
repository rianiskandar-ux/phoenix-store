"use client";

import { useState, useEffect, useRef } from "react";

/* ── Phoenix Bird SVG ─────────────────────────────────────── */
function PhoenixBird({ style }) {
    return (
        <svg viewBox="0 0 700 600" xmlns="http://www.w3.org/2000/svg" style={{ width: "100%", height: "100%", ...style }}>
            <defs>
                <radialGradient id="pb_body" cx="48%" cy="38%" r="62%">
                    <stop offset="0%"   stopColor="#ffe066"/>
                    <stop offset="28%"  stopColor="#ff8c1a"/>
                    <stop offset="65%"  stopColor="#e8431a"/>
                    <stop offset="100%" stopColor="#7a1200"/>
                </radialGradient>
                <radialGradient id="pb_wing" cx="50%" cy="50%" r="50%">
                    <stop offset="0%"   stopColor="#ff9a2a"/>
                    <stop offset="55%"  stopColor="#cc3300"/>
                    <stop offset="100%" stopColor="#5a0e00"/>
                </radialGradient>
                <linearGradient id="pb_flame" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%"   stopColor="#ffee88"/>
                    <stop offset="35%"  stopColor="#ffaa22"/>
                    <stop offset="70%"  stopColor="#ff4400"/>
                    <stop offset="100%" stopColor="#cc1100" stopOpacity="0"/>
                </linearGradient>
                <linearGradient id="pb_flame2" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%"   stopColor="#ffcc44"/>
                    <stop offset="50%"  stopColor="#ff6600"/>
                    <stop offset="100%" stopColor="#aa2200" stopOpacity="0"/>
                </linearGradient>
                <filter id="pb_glow" x="-35%" y="-35%" width="170%" height="170%">
                    <feGaussianBlur stdDeviation="10" result="b"/>
                    <feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
                </filter>
                <filter id="pb_glow_soft" x="-50%" y="-50%" width="200%" height="200%">
                    <feGaussianBlur stdDeviation="22"/>
                </filter>
            </defs>

            {/* Ambient aura behind bird */}
            <ellipse cx="350" cy="290" rx="230" ry="180" fill="#ff5500" opacity="0.18" filter="url(#pb_glow_soft)"/>

            {/* ── LEFT WING ── */}
            {/* Upper lobe */}
            <path d="
                M 320 255
                C 285 238 230 218 165 208
                C 108 200 55 206 18 218
                C 12 220 8 224 12 227
                C 48 216 100 212 160 220
                C 220 228 278 248 312 268
                Z
            " fill="url(#pb_wing)" filter="url(#pb_glow)"/>
            {/* Lower lobe */}
            <path d="
                M 315 270
                C 275 260 215 255 150 260
                C 95 264 45 278 12 292
                C 8 294 6 298 10 300
                C 44 286 94 274 148 272
                C 210 268 272 274 312 285
                Z
            " fill="url(#pb_wing)" opacity="0.88"/>
            {/* Feather tips left */}
            <path d="M 18 218 C 4 210 -6 196 4 186 C 8 202 14 215 22 224 Z" fill="#ff8800" opacity="0.85"/>
            <path d="M 32 208 C 20 197 17 182 28 174 C 30 190 32 202 38 212 Z" fill="#ffaa00" opacity="0.75"/>
            <path d="M 12 292 C -2 286 -8 272 4 264 C 6 278 10 290 16 298 Z" fill="#ff6600" opacity="0.80"/>

            {/* ── RIGHT WING ── */}
            {/* Upper lobe */}
            <path d="
                M 380 255
                C 415 238 470 218 535 208
                C 592 200 645 206 682 218
                C 688 220 692 224 688 227
                C 652 216 600 212 540 220
                C 480 228 422 248 388 268
                Z
            " fill="url(#pb_wing)" filter="url(#pb_glow)"/>
            {/* Lower lobe */}
            <path d="
                M 385 270
                C 425 260 485 255 550 260
                C 605 264 655 278 688 292
                C 692 294 694 298 690 300
                C 656 286 606 274 552 272
                C 490 268 428 274 388 285
                Z
            " fill="url(#pb_wing)" opacity="0.88"/>
            {/* Feather tips right */}
            <path d="M 682 218 C 696 210 706 196 696 186 C 692 202 686 215 678 224 Z" fill="#ff8800" opacity="0.85"/>
            <path d="M 668 208 C 680 197 683 182 672 174 C 670 190 668 202 662 212 Z" fill="#ffaa00" opacity="0.75"/>
            <path d="M 688 292 C 702 286 708 272 696 264 C 694 278 690 290 684 298 Z" fill="#ff6600" opacity="0.80"/>

            {/* ── BODY ── */}
            <path d="
                M 350 190
                C 323 190 305 212 302 248
                C 298 292 308 348 324 388
                C 333 410 342 428 350 440
                C 358 428 367 410 376 388
                C 392 348 402 292 398 248
                C 395 212 377 190 350 190
            " fill="url(#pb_body)" filter="url(#pb_glow)"/>

            {/* ── HEAD ── */}
            <circle cx="364" cy="158" r="42" fill="url(#pb_body)" filter="url(#pb_glow)"/>

            {/* Beak */}
            <path d="M 393 146 L 432 162 L 393 174 Z" fill="#ff9900"/>
            <path d="M 393 162 L 432 162 L 393 174 Z" fill="#bb4400"/>

            {/* Eye */}
            <circle cx="380" cy="150" r="12" fill="#0a0300"/>
            <circle cx="380" cy="150" r="7"  fill="#cc6600"/>
            <circle cx="380" cy="150" r="3.5" fill="#060100"/>
            <circle cx="377" cy="147" r="3"   fill="rgba(255,255,255,0.65)"/>
            <circle cx="380" cy="150" r="12"  fill="none" stroke="rgba(255,180,40,0.5)" strokeWidth="1.5"/>

            {/* ── HEAD CREST ── */}
            <path d="M 358 120 C 352 98 344 76 336 52 C 340 72 347 92 355 116 Z" fill="#ffcc00" opacity="0.95"/>
            <path d="M 370 115 C 368 91 370 68 376 44 C 374 66 372 88 370 112 Z" fill="#ff9900" opacity="0.90"/>
            <path d="M 345 125 C 336 105 326 85 314 62 C 320 82 328 102 342 122 Z" fill="#ff7700" opacity="0.85"/>
            <path d="M 383 120 C 386 97 392 75 400 54 C 396 74 390 94 382 118 Z" fill="#ff8800" opacity="0.80"/>

            {/* ── TAIL FLAMES ── */}
            <path d="M 330 420 C 314 458 295 498 278 542 C 284 530 296 506 308 476 C 320 448 330 428 333 418 Z"
                fill="url(#pb_flame)" opacity="0.92"/>
            <path d="M 342 438 C 332 474 326 510 322 548 C 326 532 334 502 342 470 C 349 444 352 430 347 436 Z"
                fill="url(#pb_flame2)" opacity="0.95"/>
            <path d="M 350 448 C 348 482 348 516 350 555 C 352 516 352 482 350 448 Z"
                fill="#ffe566" opacity="1.0"/>
            <path d="M 358 438 C 368 474 374 510 378 548 C 374 532 366 502 358 470 C 351 444 348 430 353 436 Z"
                fill="url(#pb_flame2)" opacity="0.95"/>
            <path d="M 370 420 C 386 458 405 498 422 542 C 416 530 404 506 392 476 C 380 448 370 428 367 418 Z"
                fill="url(#pb_flame)" opacity="0.92"/>
            {/* outer flame wisps */}
            <path d="M 320 428 C 302 466 285 505 270 548 C 278 534 292 500 305 466 C 315 440 322 426 322 424 Z"
                fill="#ff6600" opacity="0.55"/>
            <path d="M 380 428 C 398 466 415 505 430 548 C 422 534 408 500 395 466 C 385 440 378 426 378 424 Z"
                fill="#ff6600" opacity="0.55"/>
        </svg>
    );
}

/* ── Phoenix Eye SVG ─────────────────────────────────────── */
function PhoenixEye() {
    const lines = Array.from({ length: 48 }, (_, i) => {
        const angle = (i / 48) * Math.PI * 2;
        const x1 = 400 + Math.cos(angle) * 88;
        const y1 = 400 + Math.sin(angle) * 88;
        const x2 = 400 + Math.cos(angle) * 265;
        const y2 = 400 + Math.sin(angle) * 265;
        return { x1, y1, x2, y2, i };
    });

    return (
        <svg viewBox="0 0 800 800" xmlns="http://www.w3.org/2000/svg"
            style={{ width: "100%", height: "100%", display: "block" }}>
            <defs>
                <radialGradient id="pe_iris" cx="50%" cy="50%" r="50%">
                    <stop offset="0%"   stopColor="#120600"/>
                    <stop offset="18%"  stopColor="#3d1200"/>
                    <stop offset="38%"  stopColor="#994400"/>
                    <stop offset="58%"  stopColor="#dd7700"/>
                    <stop offset="74%"  stopColor="#ff9922"/>
                    <stop offset="86%"  stopColor="#bb4400"/>
                    <stop offset="100%" stopColor="#1a0500"/>
                </radialGradient>
                <radialGradient id="pe_pupil" cx="44%" cy="40%" r="55%">
                    <stop offset="0%"   stopColor="#1a0800"/>
                    <stop offset="55%"  stopColor="#040100"/>
                    <stop offset="100%" stopColor="#000000"/>
                </radialGradient>
                <radialGradient id="pe_glow" cx="50%" cy="50%" r="50%">
                    <stop offset="0%"   stopColor="#ff8800" stopOpacity="0.70"/>
                    <stop offset="40%"  stopColor="#cc4400" stopOpacity="0.35"/>
                    <stop offset="70%"  stopColor="#660000" stopOpacity="0.15"/>
                    <stop offset="100%" stopColor="#000000" stopOpacity="0"/>
                </radialGradient>
                <radialGradient id="pe_bg" cx="50%" cy="50%" r="50%">
                    <stop offset="0%"   stopColor="#1a0500"/>
                    <stop offset="60%"  stopColor="#080200"/>
                    <stop offset="100%" stopColor="#000000"/>
                </radialGradient>
                <filter id="pe_blur">
                    <feGaussianBlur stdDeviation="10" result="b"/>
                    <feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
                </filter>
            </defs>

            {/* Outer dark surround (feather bg) */}
            <circle cx="400" cy="400" r="400" fill="url(#pe_bg)"/>

            {/* Outer amber glow */}
            <circle cx="400" cy="400" r="360" fill="url(#pe_glow)"/>

            {/* Feather texture ring */}
            <circle cx="400" cy="400" r="300" fill="#0a0200"/>

            {/* Iris */}
            <circle cx="400" cy="400" r="275" fill="url(#pe_iris)" filter="url(#pe_blur)"/>

            {/* Iris texture lines radiating from pupil */}
            {lines.map(({ x1, y1, x2, y2, i }) => (
                <line key={i} x1={x1} y1={y1} x2={x2} y2={y2}
                    stroke={`rgba(255,${130 + (i % 8) * 10},0,0.14)`}
                    strokeWidth="1.8"/>
            ))}

            {/* Bright ring at pupil edge */}
            <circle cx="400" cy="400" r="90" fill="none"
                stroke="rgba(255,160,0,0.45)" strokeWidth="4"/>

            {/* Pupil */}
            <circle cx="400" cy="400" r="85" fill="url(#pe_pupil)"/>

            {/* Pupil depth — tiny forest/tree silhouette reflection (phoenix lore) */}
            <ellipse cx="400" cy="408" rx="28" ry="18" fill="rgba(255,140,0,0.07)"/>

            {/* Specular reflections */}
            <ellipse cx="368" cy="368" rx="20" ry="28"
                fill="rgba(255,255,255,0.52)" transform="rotate(-25,368,368)"/>
            <ellipse cx="428" cy="436" rx="7"  ry="11"
                fill="rgba(255,255,255,0.18)" transform="rotate(-25,428,436)"/>

            {/* Outer iris ring */}
            <circle cx="400" cy="400" r="273" fill="none"
                stroke="rgba(80,20,0,0.9)" strokeWidth="5"/>

            {/* Inner soft glow ring */}
            <circle cx="400" cy="400" r="200" fill="none"
                stroke="rgba(255,120,0,0.12)" strokeWidth="40"/>
        </svg>
    );
}

/* ── Main component ────────────────────────────────────────── */
export default function HeroPhoenixSlider() {
    const [slide,         setSlide]         = useState(0);   // 0=intro 2=eye
    const [birdScale,     setBirdScale]     = useState(1);
    const [birdOpacity,   setBirdOpacity]   = useState(1);
    const [birdTransition,setBirdTransition]= useState(true);
    const [eyeScale,      setEyeScale]      = useState(0.05);
    const [eyeOpacity,    setEyeOpacity]    = useState(0);
    const [slide1Vis,     setSlide1Vis]     = useState(true);
    const [slide2Vis,     setSlide2Vis]     = useState(false);
    const bgRef   = useRef(null);
    const stRef   = useRef({ raf: null });

    /* atmospheric canvas */
    useEffect(() => {
        const canvas = bgRef.current;
        if (!canvas) return;
        const ctx = canvas.getContext("2d");
        const s   = stRef.current;
        let t     = 0;

        const resize = () => {
            canvas.width  = canvas.offsetWidth  * (window.devicePixelRatio || 1);
            canvas.height = canvas.offsetHeight * (window.devicePixelRatio || 1);
        };
        resize();
        window.addEventListener("resize", resize);

        const loop = () => {
            t += 0.008;
            const W = canvas.width, H = canvas.height;
            const dpr = window.devicePixelRatio || 1;

            ctx.fillStyle = "#060302";
            ctx.fillRect(0, 0, W, H);

            // Pulsing glow behind bird (right-center)
            const p1 = 0.10 + Math.sin(t) * 0.025;
            const g1 = ctx.createRadialGradient(W*0.66/dpr, H*0.48/dpr, 0, W*0.66/dpr, H*0.48/dpr, W*0.38/dpr);
            g1.addColorStop(0,   `rgba(220,70,15,${p1})`);
            g1.addColorStop(0.5, `rgba(140,30,0,${p1*0.4})`);
            g1.addColorStop(1,   "rgba(0,0,0,0)");
            ctx.fillStyle = g1;
            ctx.fillRect(0, 0, W/dpr, H/dpr);

            // Subtle bottom warmth
            const p2 = 0.06 + Math.sin(t * 0.7 + 1) * 0.015;
            const g2 = ctx.createRadialGradient(W*0.5/dpr, H/dpr, 0, W*0.5/dpr, H/dpr, W*0.55/dpr);
            g2.addColorStop(0,   `rgba(180,50,0,${p2})`);
            g2.addColorStop(1,   "rgba(0,0,0,0)");
            ctx.fillStyle = g2;
            ctx.fillRect(0, 0, W/dpr, H/dpr);

            s.raf = requestAnimationFrame(loop);
        };
        loop();
        return () => {
            cancelAnimationFrame(s.raf);
            window.removeEventListener("resize", resize);
        };
    }, []);

    /* Slide 0 → 2 */
    const handleSeeWhatFollows = () => {
        setSlide1Vis(false);
        // Bird flies toward viewer
        setBirdScale(14);
        setBirdOpacity(0);

        // Eye iris opens from center
        setTimeout(() => {
            setSlide(2);
            setEyeScale(1);
            setEyeOpacity(1);
        }, 700);

        setTimeout(() => setSlide2Vis(true), 1300);
    };

    /* Slide 2 → 0 */
    const handleStartOver = () => {
        setSlide2Vis(false);
        setEyeOpacity(0);
        setEyeScale(0.05);

        setTimeout(() => {
            setBirdTransition(false);
            setBirdScale(1);
            setBirdOpacity(0);
            setSlide(0);
        }, 500);

        setTimeout(() => {
            setBirdTransition(true);
            setBirdOpacity(1);
            setSlide1Vis(true);
        }, 650);
    };

    return (
        <div style={{
            position: "relative", width: "100%", height: "100vh",
            minHeight: "600px", overflow: "hidden", background: "#060302",
        }}>
            {/* Atmospheric bg */}
            <canvas ref={bgRef}
                style={{ position: "absolute", inset: 0, width: "100%", height: "100%", display: "block" }}
            />

            {/* Film grain */}
            <div style={{
                position: "absolute", inset: 0, zIndex: 1, pointerEvents: "none",
                opacity: 0.05, mixBlendMode: "overlay",
                backgroundImage: `url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='256' height='256'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/><feColorMatrix type='saturate' values='0'/></filter><rect width='256' height='256' filter='url(%23n)'/></svg>")`,
                backgroundSize: "200px 200px",
            }}/>

            {/* ── PHOENIX BIRD ── */}
            <div style={{
                position: "absolute",
                right: "-2%", top: "50%",
                width: "58%", maxWidth: "700px",
                transform: `translateY(-54%) scale(${birdScale})`,
                transformOrigin: "60% 45%",
                opacity: birdOpacity,
                transition: birdTransition
                    ? "transform 1.1s cubic-bezier(0.22,1,0.36,1), opacity 0.75s ease"
                    : "none",
                pointerEvents: "none",
                zIndex: 2,
            }}>
                <PhoenixBird />
            </div>

            {/* ── EYE REVEAL ── */}
            <div style={{
                position: "absolute",
                top: "50%", left: "50%",
                width: "min(82vh, 82vw)",
                transform: `translate(-50%, -50%) scale(${eyeScale})`,
                opacity: eyeOpacity,
                transition: "transform 0.95s cubic-bezier(0.22,1,0.36,1), opacity 0.65s ease",
                pointerEvents: "none",
                zIndex: 3,
            }}>
                <PhoenixEye />
            </div>

            {/* ── SLIDE 1 CONTENT ── */}
            <div style={{
                position: "absolute", inset: 0, zIndex: 4,
                display: "flex", alignItems: "center",
                pointerEvents: "none",
                opacity: slide1Vis ? 1 : 0,
                transition: "opacity 0.38s ease",
            }}>
                <div style={{ width: "100%", maxWidth: "1200px", margin: "0 auto", padding: "0 52px" }}>
                    <div style={{ maxWidth: "500px", pointerEvents: "auto" }}>

                        <div style={{
                            fontSize: "0.68rem", letterSpacing: "0.22em",
                            color: "rgba(255,140,50,0.80)", textTransform: "uppercase",
                            fontWeight: 700, marginBottom: "18px",
                        }}>
                            /ˈfiː.nɪks/ &nbsp;·&nbsp; A Unique Hero Experience
                        </div>

                        <h1 style={{
                            fontSize: "clamp(3rem, 5.5vw, 5.2rem)",
                            fontWeight: 900, lineHeight: 0.97,
                            color: "#fff", margin: "0 0 22px",
                            letterSpacing: "-0.03em",
                            textShadow: "0 2px 48px rgba(0,0,0,0.7)",
                        }}>
                            Own the<br />First<br />Impression
                        </h1>

                        <div style={{
                            width: "52px", height: "3px", borderRadius: "2px",
                            background: "linear-gradient(90deg,#e8431a,#ff6b3d)",
                            marginBottom: "22px",
                        }}/>

                        <p style={{
                            fontSize: "1rem", color: "rgba(255,255,255,0.52)",
                            margin: "0 0 42px", lineHeight: 1.65, maxWidth: "380px",
                        }}>
                            A whistleblowing platform built to create instant trust through transparency, security, and integrity.
                        </p>

                        <style>{`
                            @keyframes ph_pulse {
                                0%,100% { box-shadow: 0 6px 26px rgba(232,67,26,0.48), 0 0 0 0 rgba(232,67,26,0.28); }
                                50%     { box-shadow: 0 6px 26px rgba(232,67,26,0.48), 0 0 0 12px rgba(232,67,26,0); }
                            }
                        `}</style>

                        <button onClick={handleSeeWhatFollows} style={{
                            padding: "14px 38px", borderRadius: "50px",
                            background: "#e8431a", color: "#fff",
                            fontSize: "0.88rem", fontWeight: 800, border: "none",
                            cursor: "pointer", letterSpacing: "0.04em",
                            animation: "ph_pulse 2.6s ease-in-out infinite",
                            transition: "background 0.2s, transform 0.2s",
                        }}
                            onMouseEnter={e => {
                                e.currentTarget.style.animation = "none";
                                e.currentTarget.style.background = "#ff6b3d";
                                e.currentTarget.style.transform = "translateY(-2px)";
                            }}
                            onMouseLeave={e => {
                                e.currentTarget.style.animation = "ph_pulse 2.6s ease-in-out infinite";
                                e.currentTarget.style.background = "#e8431a";
                                e.currentTarget.style.transform = "translateY(0)";
                            }}
                        >
                            See What Follows
                        </button>
                    </div>
                </div>
            </div>

            {/* Bottom info bar — slide 1 */}
            <div style={{
                position: "absolute", bottom: 0, left: 0, right: 0, zIndex: 4,
                display: "flex",
                borderTop: "1px solid rgba(255,255,255,0.07)",
                opacity: slide1Vis ? 1 : 0,
                transition: "opacity 0.38s ease",
                pointerEvents: "none",
            }}>
                {[
                    { icon: "🛡", label: "SECURE REPORTING",  sub: "End-to-end encrypted channels" },
                    { icon: "⚡", label: "INSTANT ALERTS",    sub: "Real-time notification system"  },
                    { icon: "✓",  label: "COMPLIANCE READY",  sub: "EU Whistleblower Directive"     },
                ].map((item, i) => (
                    <div key={i} style={{
                        flex: 1, padding: "16px 40px",
                        borderRight: i < 2 ? "1px solid rgba(255,255,255,0.07)" : "none",
                    }}>
                        <div style={{ fontSize: "0.6rem", letterSpacing: "0.14em", color: "rgba(255,140,50,0.7)", textTransform: "uppercase", marginBottom: "3px" }}>
                            {item.icon}&nbsp; {item.label}
                        </div>
                        <div style={{ fontSize: "0.72rem", color: "rgba(255,255,255,0.38)" }}>
                            {item.sub}
                        </div>
                    </div>
                ))}
            </div>

            {/* ── SLIDE 2 CONTENT ── */}
            <div style={{
                position: "absolute", inset: 0, zIndex: 5,
                display: "flex", alignItems: "center", justifyContent: "flex-end",
                pointerEvents: "none",
                opacity: slide2Vis ? 1 : 0,
                transition: "opacity 0.55s ease",
            }}>
                <div style={{
                    padding: "0 60px 0 0", maxWidth: "420px",
                    pointerEvents: slide2Vis ? "auto" : "none",
                }}>
                    <div style={{
                        fontSize: "0.68rem", letterSpacing: "0.22em",
                        color: "rgba(255,140,50,0.80)", textTransform: "uppercase",
                        fontWeight: 700, marginBottom: "16px",
                    }}>
                        /ˈæk.ʃən/ &nbsp;·&nbsp; ACTION
                    </div>

                    <h2 style={{
                        fontSize: "clamp(2.2rem, 3.8vw, 3.6rem)",
                        fontWeight: 900, lineHeight: 1.02,
                        color: "#fff", margin: "0 0 18px",
                        letterSpacing: "-0.025em",
                        textShadow: "0 2px 40px rgba(0,0,0,0.8)",
                    }}>
                        Turn integrity<br />into action
                    </h2>

                    <div style={{
                        width: "52px", height: "3px", borderRadius: "2px",
                        background: "linear-gradient(90deg,#e8431a,#ff6b3d)",
                        marginBottom: "20px",
                    }}/>

                    <p style={{
                        fontSize: "0.92rem", color: "rgba(255,255,255,0.48)",
                        marginBottom: "36px", lineHeight: 1.65,
                    }}>
                        Phoenix transforms whistleblower protection into a confident, clear call to action — for every organization.
                    </p>

                    <div style={{ display: "flex", gap: "12px", flexWrap: "wrap" }}>
                        <a href="/get-started" style={{
                            padding: "13px 30px", borderRadius: "50px",
                            background: "#e8431a", color: "#fff",
                            fontSize: "0.83rem", fontWeight: 800,
                            textDecoration: "none", letterSpacing: "0.04em",
                            boxShadow: "0 6px 24px rgba(232,67,26,0.52)",
                            transition: "background 0.2s, transform 0.2s",
                        }}
                            onMouseEnter={e => { e.currentTarget.style.background = "#ff6b3d"; e.currentTarget.style.transform = "translateY(-2px)"; }}
                            onMouseLeave={e => { e.currentTarget.style.background = "#e8431a"; e.currentTarget.style.transform = "translateY(0)"; }}
                        >
                            Get Started for Free
                        </a>

                        <button onClick={handleStartOver} style={{
                            padding: "13px 30px", borderRadius: "50px",
                            background: "transparent", color: "rgba(255,255,255,0.72)",
                            fontSize: "0.83rem", fontWeight: 800,
                            border: "1.5px solid rgba(255,255,255,0.22)",
                            cursor: "pointer", letterSpacing: "0.04em",
                            transition: "border-color 0.2s, color 0.2s, transform 0.2s",
                        }}
                            onMouseEnter={e => { e.currentTarget.style.borderColor = "rgba(232,67,26,0.65)"; e.currentTarget.style.color = "#fff"; e.currentTarget.style.transform = "translateY(-2px)"; }}
                            onMouseLeave={e => { e.currentTarget.style.borderColor = "rgba(255,255,255,0.22)"; e.currentTarget.style.color = "rgba(255,255,255,0.72)"; e.currentTarget.style.transform = "translateY(0)"; }}
                        >
                            Start Over
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
}
