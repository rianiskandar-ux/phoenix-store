"use client";

import Link from "next/link";
import Image from "next/image";
import { useState, useCallback } from "react";

const waveKeyframes = `
@keyframes puzzleDrop {
    from { opacity: 0; transform: translateY(-22px) scale(0.88); }
    65%  { opacity: 1; transform: translateY(3px) scale(1.03); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes mapFadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}
`;

export default function HeroSection() {
    const [mouse, setMouse] = useState({ x: 0, y: 0 });

    const handleMouseMove = useCallback((e) => {
        const rect = e.currentTarget.getBoundingClientRect();
        // Normalize -1 to 1
        const x = ((e.clientX - rect.left) / rect.width - 0.5) * 2;
        const y = ((e.clientY - rect.top) / rect.height - 0.5) * 2;
        setMouse({ x, y });
    }, []);

    // Helper: compute parallax translate for a layer
    const p = (strength) => ({
        transform: `translate(${mouse.x * strength}px, ${mouse.y * strength}px)`,
        transition: "transform 0.12s ease-out",
    });

    return (
        <div
            onMouseMove={handleMouseMove}
            style={{
                position: "relative", width: "100%",
                background: "#ffffff",
                overflow: "hidden",
            }}>
            <style>{waveKeyframes}</style>

            {/* Subtle background tint */}
            <div style={{
                position: "absolute", top: "-100px", right: "-100px",
                width: "600px", height: "600px",
                background: "radial-gradient(circle, rgba(232,67,26,0.06) 0%, transparent 65%)",
                pointerEvents: "none",
            }} />
            {/* line.png — near title, rotated for energy — slow layer */}
            <div style={{ position: "absolute", top: "18%", left: "3%", pointerEvents: "none", opacity: 0.45, ...p(6), transform: `rotate(-15deg) translate(${mouse.x * 6}px, ${mouse.y * 6}px)` }}>
                <Image src="/assets/line.png" alt="" width={72} height={36} style={{ objectFit: "contain" }} />
            </div>
            {/* ornamen black and orange — bottom left — medium layer */}
            <div style={{ position: "absolute", bottom: "60px", left: "-28px", pointerEvents: "none", opacity: 0.15, ...p(10), transform: `rotate(12deg) scale(1.1) translate(${mouse.x * 10}px, ${mouse.y * 10}px)` }}>
                <Image src="/assets/ornamen black and orange.png" alt="" width={110} height={110} style={{ objectFit: "contain" }} />
            </div>
            {/* ORNAMEN 3 peach — top left — slowest, largest */}
            <div style={{ position: "absolute", top: "-60px", left: "-60px", pointerEvents: "none", opacity: 0.2, ...p(4) }}>
                <Image src="/assets/ORNAMEN 3.png" alt="" width={340} height={210} style={{ objectFit: "contain" }} />
            </div>
            {/* dot grid mid-left subtle — static */}
            <div style={{
                position: "absolute", top: "30%", left: "0",
                width: "180px", height: "240px",
                backgroundImage: "radial-gradient(circle, #e8431a 1px, transparent 1px)",
                backgroundSize: "18px 18px",
                opacity: 0.04, pointerEvents: "none",
            }} />
            {/* Bottom wave */}
            <div style={{ position: "absolute", bottom: 0, left: 0, right: 0, lineHeight: 0, pointerEvents: "none" }}>
                <svg viewBox="0 0 1440 60" preserveAspectRatio="none" style={{ width: "100%", height: "60px" }}>
                    <path d="M0,30 C360,60 1080,0 1440,30 L1440,60 L0,60 Z" fill="#f8f9fb" />
                </svg>
            </div>

            <div style={{ maxWidth: "1200px", margin: "0 auto", padding: "0 32px" }}>
                <div style={{
                    display: "flex", justifyContent: "space-between",
                    alignItems: "center", gap: "60px", flexWrap: "wrap",
                    minHeight: "calc(100vh - 60px)",
                    paddingTop: "60px", paddingBottom: "80px",
                }}>
                    {/* Left */}
                    <div style={{ maxWidth: "480px", zIndex: 10, position: "relative" }}>
                        {/* Badge */}
                        <div style={{
                            display: "inline-flex", alignItems: "center", gap: "8px",
                            background: "#fff5f2", border: "1px solid rgba(232,67,26,0.2)",
                            borderRadius: "100px", padding: "5px 14px", marginBottom: "24px",
                        }}>
                            <span style={{ width: "6px", height: "6px", borderRadius: "50%", background: "#e8431a", display: "inline-block" }} />
                            <span style={{ color: "#e8431a", fontSize: "0.72rem", fontWeight: 700, letterSpacing: "0.07em" }}>No credit card required. No hidden fees.</span>
                        </div>

                        <h1 style={{
                            fontSize: "clamp(3rem, 5.5vw, 5rem)", fontWeight: 900,
                            color: "#e8431a", lineHeight: 1, margin: "0 0 4px",
                            letterSpacing: "-0.02em", textTransform: "uppercase",
                        }}>
                            PHOENIX
                        </h1>
                        <h2 style={{
                            fontSize: "clamp(1.3rem, 2.2vw, 1.9rem)", fontWeight: 700,
                            color: "#111", margin: "0 0 20px", lineHeight: 1.2,
                        }}>
                            Whistleblowing Software
                        </h2>
                        <p style={{ color: "#777", fontSize: "1rem", margin: "0 0 36px", lineHeight: 1.7 }}>
                            Inspiring Integrity, Guiding Growth
                        </p>

                        <div style={{ display: "flex", gap: "12px", flexWrap: "wrap" }}>
                            <Link href="/get-started?plan=free" style={{
                                display: "inline-block", padding: "15px 32px",
                                background: "#e8431a", color: "#fff",
                                fontWeight: 700, fontSize: "0.875rem",
                                borderRadius: "8px", textDecoration: "none",
                                boxShadow: "0 6px 24px rgba(232,67,26,0.35)",
                                letterSpacing: "0.02em",
                            }}>
                                Get Started Free
                            </Link>
                            <Link href="/features" style={{
                                display: "inline-block", padding: "15px 32px",
                                background: "transparent", color: "#333",
                                fontWeight: 600, fontSize: "0.875rem",
                                borderRadius: "8px", textDecoration: "none",
                                border: "1.5px solid #e8e8e8",
                                letterSpacing: "0.02em",
                            }}>
                                View Features
                            </Link>
                        </div>

                        {/* Stats */}
                        <div style={{ display: "flex", gap: "32px", marginTop: "48px", paddingTop: "28px", borderTop: "1px solid #f0f0f0" }}>
                            {[
                                { val: "50+", label: "Languages" },
                                { val: "99.9%", label: "Uptime SLA" },
                                { val: "2 hrs", label: "Setup time" },
                            ].map(s => (
                                <div key={s.val}>
                                    <div style={{ fontSize: "1.5rem", fontWeight: 900, color: "#111" }}>{s.val}</div>
                                    <div style={{ fontSize: "0.72rem", color: "#aaa", marginTop: "2px", letterSpacing: "0.05em", textTransform: "uppercase" }}>{s.label}</div>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Right: Europe puzzle map */}
                    <div style={{ flex: "1", minWidth: "300px", maxWidth: "600px", position: "relative", ...p(-5) }}>
                        <EuropePuzzle />
                    </div>
                </div>
            </div>
        </div>
    );
}

// European country puzzle pieces
// ViewBox 0 0 450 520  |  x = (lon + 12) * 10  |  y = (72 - lat) * 14
// Each piece: id, name, warm wood color, stagger delay, label center, SVG path
const COUNTRIES = [
    { id:"norway",   name:"Norway",      color:"#dfc08a", delay:0.1, lx:168, ly:128, path:"M 82,196 L 168,196 L 220,189 L 262,91 L 262,14 L 202,14 L 156,42 L 110,85 Z" },
    { id:"sweden",   name:"Sweden",      color:"#c8a870", delay:0.3, lx:280, ly:165, path:"M 220,196 L 302,210 L 302,168 L 360,56 L 262,14 L 262,91 Z" },
    { id:"finland",  name:"Finland",     color:"#b8956e", delay:0.5, lx:372, ly:130, path:"M 302,168 L 402,168 L 428,126 L 410,28 L 380,28 L 360,56 Z" },
    { id:"denmark",  name:"Denmark",     color:"#e0c898", delay:0.7, lx:208, ly:218, path:"M 196,217 L 220,196 L 238,210 L 236,224 L 212,231 L 196,224 Z" },
    { id:"uk",       name:"UK",          color:"#d4b880", delay:0.9, lx:89,  ly:258, path:"M 62,308 L 118,285 L 130,280 L 108,238 L 68,196 L 60,224 L 64,273 Z" },
    { id:"ireland",  name:"Ireland",     color:"#c8a070", delay:1.1, lx:42,  ly:266, path:"M 36,252 L 55,238 L 58,266 L 50,287 L 30,280 Z" },
    { id:"neth",     name:"Netherlands", color:"#dfc090", delay:1.3, lx:175, ly:277, path:"M 158,280 L 173,266 L 191,259 L 191,280 L 178,294 L 158,287 Z" },
    { id:"belgium",  name:"Belgium",     color:"#b88c60", delay:1.5, lx:162, ly:304, path:"M 148,301 L 158,287 L 178,294 L 180,308 L 170,315 L 157,315 Z" },
    { id:"france",   name:"France",      color:"#d4a860", delay:1.7, lx:130, ly:353, path:"M 76,329 L 138,294 L 178,294 L 196,326 L 194,392 L 170,400 L 106,401 L 100,357 Z" },
    { id:"portugal", name:"Portugal",    color:"#c89060", delay:1.9, lx:43,  ly:446, path:"M 30,418 L 57,418 L 56,474 L 26,474 Z" },
    { id:"spain",    name:"Spain",       color:"#e0c070", delay:2.1, lx:90,  ly:444, path:"M 32,418 L 106,401 L 153,413 L 122,471 L 64,502 L 26,474 L 57,418 Z" },
    { id:"germany",  name:"Germany",     color:"#c49062", delay:2.3, lx:226, ly:291, path:"M 178,287 L 214,238 L 265,252 L 265,294 L 250,329 L 220,341 L 194,341 L 178,315 Z" },
    { id:"swiss",    name:"Switzerland", color:"#a88060", delay:2.5, lx:200, ly:354, path:"M 180,357 L 199,343 L 225,345 L 221,363 L 188,366 Z" },
    { id:"austria",  name:"Austria",     color:"#d0a868", delay:2.7, lx:268, ly:347, path:"M 221,345 L 250,339 L 290,336 L 285,357 L 250,359 L 221,353 Z" },
    { id:"italy",    name:"Italy",       color:"#b87840", delay:2.9, lx:228, ly:416, path:"M 194,392 L 255,371 L 270,392 L 282,448 L 270,469 L 255,455 L 244,420 L 220,407 Z" },
    { id:"czech",    name:"Czech Rep.",  color:"#d8b880", delay:3.1, lx:270, ly:309, path:"M 244,294 L 265,294 L 305,308 L 299,322 L 244,322 Z" },
    { id:"poland",   name:"Poland",      color:"#c8a060", delay:3.3, lx:300, ly:270, path:"M 265,252 L 305,245 L 345,245 L 355,280 L 339,308 L 299,308 L 265,294 Z" },
    { id:"hungary",  name:"Hungary",     color:"#b89868", delay:3.5, lx:310, ly:350, path:"M 285,343 L 299,329 L 345,336 L 345,357 L 305,371 L 285,365 Z" },
    { id:"balkans",  name:"Balkans",     color:"#c0a878", delay:3.7, lx:302, ly:398, path:"M 250,359 L 285,357 L 305,371 L 345,357 L 374,399 L 345,420 L 320,420 L 299,399 L 272,392 L 255,371 Z" },
    { id:"romania",  name:"Romania",     color:"#dfc8a0", delay:3.9, lx:376, ly:365, path:"M 345,336 L 360,336 L 390,343 L 408,378 L 404,392 L 374,399 L 345,392 L 345,357 Z" },
    { id:"greece",   name:"Greece",      color:"#d0b080", delay:4.1, lx:350, ly:452, path:"M 320,420 L 345,420 L 380,427 L 390,476 L 340,497 L 326,462 L 325,427 Z" },
];

function EuropePuzzle() {
    const [hovered, setHovered] = useState(null);

    return (
        <div style={{ position: "relative", width: "100%", animation: "mapFadeIn 0.8s ease forwards" }}>
            <div style={{
                background: "#f5f0e8",
                border: "1px solid #e8e0d0",
                borderRadius: "20px",
                padding: "20px 16px 16px",
                boxShadow: "0 12px 50px rgba(0,0,0,0.09)",
                overflow: "hidden",
            }}>
                {/* Top hint */}
                <div style={{ display: "flex", alignItems: "center", gap: "6px", marginBottom: "10px", paddingLeft: "4px" }}>
                    <div style={{ width: "5px", height: "5px", borderRadius: "50%", background: "#e8431a" }} />
                    <span style={{ fontSize: "0.62rem", fontWeight: 700, color: "#e8431a", letterSpacing: "0.08em" }}>
                        {hovered
                            ? COUNTRIES.find(c => c.id === hovered)?.name.toUpperCase()
                            : "HOVER A COUNTRY · EU COVERAGE"}
                    </span>
                </div>

                <svg viewBox="0 0 450 520" style={{ width: "100%", height: "auto", display: "block" }}>
                    <defs>
                        {/* Normal piece shadow */}
                        <filter id="ps" x="-10%" y="-10%" width="120%" height="120%">
                            <feDropShadow dx="1" dy="2.5" stdDeviation="2" floodOpacity="0.18" />
                        </filter>
                        {/* Hover piece shadow — orange glow */}
                        <filter id="ph" x="-20%" y="-20%" width="140%" height="140%">
                            <feDropShadow dx="0" dy="4" stdDeviation="5" floodColor="#e8431a" floodOpacity="0.45" />
                        </filter>
                    </defs>

                    {COUNTRIES.map((c) => {
                        const isHovered = hovered === c.id;
                        return (
                            <g
                                key={c.id}
                                filter={`url(#${isHovered ? "ph" : "ps"})`}
                                onMouseEnter={() => setHovered(c.id)}
                                onMouseLeave={() => setHovered(null)}
                                style={{
                                    cursor: "pointer",
                                    transformBox: "fill-box",
                                    transformOrigin: "center",
                                    animation: `puzzleDrop 0.55s ${c.delay}s cubic-bezier(0.34,1.56,0.64,1) both`,
                                    transform: isHovered ? "translateY(-5px)" : undefined,
                                    transition: "transform 0.18s ease",
                                }}
                            >
                                {/* Piece body */}
                                <path
                                    d={c.path}
                                    fill={isHovered ? "#e8431a" : c.color}
                                    stroke="#f5f0e8"
                                    strokeWidth="1.8"
                                    strokeLinejoin="round"
                                    style={{ transition: "fill 0.15s ease" }}
                                />
                                {/* Inner edge highlight (3D top-left bevel) */}
                                <path
                                    d={c.path}
                                    fill="none"
                                    stroke={isHovered ? "rgba(255,255,255,0.3)" : "rgba(255,255,255,0.55)"}
                                    strokeWidth="0.8"
                                    strokeLinejoin="round"
                                    style={{ pointerEvents: "none", transition: "stroke 0.15s" }}
                                />
                                {/* Country label */}
                                <text
                                    x={c.lx} y={c.ly}
                                    textAnchor="middle"
                                    fill={isHovered ? "#fff" : "rgba(60,35,10,0.55)"}
                                    fontSize="7"
                                    fontWeight="700"
                                    style={{ pointerEvents: "none", transition: "fill 0.15s", userSelect: "none" }}
                                >{c.name}</text>
                            </g>
                        );
                    })}
                </svg>
            </div>
        </div>
    );
}
