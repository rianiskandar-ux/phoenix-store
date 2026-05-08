"use client";

import Link from "next/link";
import Image from "next/image";
import { useState, useCallback, useRef } from "react";
import { ComposableMap, Geographies, Geography } from "react-simple-maps";

const waveKeyframes = `
@keyframes mapFadeIn {
    from { opacity: 0; transform: scale(0.98); }
    to   { opacity: 1; transform: scale(1); }
}
@keyframes floatUp {
    0%   { transform: translateY(0) scale(1); filter: drop-shadow(0 2px 4px rgba(0,0,0,0.12)); }
    50%  { transform: translateY(-4px) scale(1.06); filter: drop-shadow(0 8px 16px rgba(232,67,26,0.4)); }
    100% { transform: translateY(-3px) scale(1.05); filter: drop-shadow(0 6px 12px rgba(232,67,26,0.35)); }
}
@keyframes tooltipIn {
    from { opacity: 0; transform: translateY(4px) scale(0.95); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
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

                    {/* Right: interactive world map */}
                    <div style={{ flex: "1", minWidth: "300px", maxWidth: "600px", position: "relative", ...p(-5) }}>
                        <WorldMapInteractive />
                    </div>
                </div>
            </div>
        </div>
    );
}

// World TopoJSON from CDN — loaded client-side
const GEO_URL = "https://cdn.jsdelivr.net/npm/world-atlas@2/countries-110m.json";

// ISO numeric → country name mapping for tooltip
const COUNTRY_NAMES = {
    "4":"Afghanistan","8":"Albania","12":"Algeria","24":"Angola","32":"Argentina","36":"Australia",
    "40":"Austria","50":"Bangladesh","56":"Belgium","64":"Bhutan","68":"Bolivia","76":"Brazil",
    "100":"Bulgaria","116":"Cambodia","120":"Cameroon","124":"Canada","144":"Sri Lanka","152":"Chile",
    "156":"China","170":"Colombia","180":"DR Congo","188":"Costa Rica","191":"Croatia","192":"Cuba",
    "203":"Czech Republic","208":"Denmark","218":"Ecuador","818":"Egypt","231":"Ethiopia",
    "246":"Finland","250":"France","276":"Germany","288":"Ghana","300":"Greece","320":"Guatemala",
    "332":"Haiti","340":"Honduras","348":"Hungary","356":"India","360":"Indonesia","364":"Iran",
    "368":"Iraq","372":"Ireland","376":"Israel","380":"Italy","388":"Jamaica","392":"Japan",
    "400":"Jordan","398":"Kazakhstan","404":"Kenya","408":"North Korea","410":"South Korea",
    "414":"Kuwait","418":"Laos","422":"Lebanon","430":"Liberia","434":"Libya","484":"Mexico",
    "504":"Morocco","508":"Mozambique","516":"Namibia","524":"Nepal","528":"Netherlands",
    "540":"New Caledonia","554":"New Zealand","558":"Nicaragua","566":"Nigeria","578":"Norway",
    "586":"Pakistan","591":"Panama","600":"Paraguay","604":"Peru","608":"Philippines","616":"Poland",
    "620":"Portugal","630":"Puerto Rico","642":"Romania","643":"Russia","646":"Rwanda",
    "682":"Saudi Arabia","686":"Senegal","694":"Sierra Leone","703":"Slovakia","706":"Somalia",
    "710":"South Africa","724":"Spain","729":"Sudan","752":"Sweden","756":"Switzerland",
    "760":"Syria","764":"Thailand","768":"Togo","792":"Turkey","800":"Uganda","804":"Ukraine",
    "784":"United Arab Emirates","826":"United Kingdom","840":"United States","858":"Uruguay",
    "862":"Venezuela","704":"Vietnam","887":"Yemen","894":"Zambia","716":"Zimbabwe",
};

function WorldMapInteractive() {
    const [hovered, setHovered] = useState(null);
    const [tooltipPos, setTooltipPos] = useState({ x: 0, y: 0 });
    const wrapRef = useRef(null);

    const handleMouseMove = useCallback((e) => {
        if (!wrapRef.current) return;
        const rect = wrapRef.current.getBoundingClientRect();
        setTooltipPos({ x: e.clientX - rect.left, y: e.clientY - rect.top });
    }, []);

    return (
        <div
            ref={wrapRef}
            onMouseMove={handleMouseMove}
            style={{
                position: "relative",
                width: "100%",
                animation: "mapFadeIn 1s ease forwards",
                background: "#f8f9fb",
                border: "1px solid #f0f0f0",
                borderRadius: "20px",
                boxShadow: "0 8px 40px rgba(0,0,0,0.06)",
                overflow: "hidden",
                padding: "12px 8px 8px",
            }}
        >
            {/* Header bar */}
            <div style={{ display: "flex", alignItems: "center", gap: "6px", marginBottom: "4px", paddingLeft: "8px" }}>
                <div style={{ width: "5px", height: "5px", borderRadius: "50%", background: "#e8431a" }} />
                <span style={{ fontSize: "0.62rem", fontWeight: 700, color: "#e8431a", letterSpacing: "0.08em" }}>
                    GLOBAL REACH · 50+ LANGUAGES
                </span>
            </div>

            <ComposableMap
                projectionConfig={{ scale: 120, center: [10, 20] }}
                style={{ width: "100%", height: "auto" }}
            >
                <Geographies geography={GEO_URL}>
                    {({ geographies }) =>
                        geographies.map((geo) => {
                            const id = geo.id;
                            return (
                                <Geography
                                    key={geo.rsmKey}
                                    geography={geo}
                                    onMouseEnter={() => setHovered(id)}
                                    onMouseLeave={() => setHovered(null)}
                                    style={{
                                        default: {
                                            fill: "#D6D9E0",
                                            stroke: "#fff",
                                            strokeWidth: 0.5,
                                            outline: "none",
                                            transition: "fill 0.18s ease, transform 0.22s cubic-bezier(0.34,1.56,0.64,1), filter 0.22s ease",
                                            transformBox: "fill-box",
                                            transformOrigin: "center",
                                        },
                                        hover: {
                                            fill: "#e8431a",
                                            stroke: "#fff",
                                            strokeWidth: 0.6,
                                            outline: "none",
                                            transform: "scale(1.06) translateY(-2px)",
                                            filter: "drop-shadow(0 4px 10px rgba(232,67,26,0.5))",
                                            transformBox: "fill-box",
                                            transformOrigin: "center",
                                            cursor: "pointer",
                                            zIndex: 10,
                                        },
                                        pressed: {
                                            fill: "#c0361a",
                                            outline: "none",
                                            transformBox: "fill-box",
                                            transformOrigin: "center",
                                        },
                                    }}
                                />
                            );
                        })
                    }
                </Geographies>
            </ComposableMap>

            {/* Floating tooltip */}
            {hovered && COUNTRY_NAMES[hovered] && (
                <div style={{
                    position: "absolute",
                    left: tooltipPos.x + 12,
                    top: tooltipPos.y - 36,
                    background: "#fff",
                    border: "1px solid #f0f0f0",
                    borderRadius: "8px",
                    padding: "5px 10px",
                    fontSize: "0.72rem",
                    fontWeight: 700,
                    color: "#111",
                    boxShadow: "0 4px 16px rgba(0,0,0,0.12)",
                    pointerEvents: "none",
                    whiteSpace: "nowrap",
                    animation: "tooltipIn 0.15s ease forwards",
                    zIndex: 20,
                }}>
                    <span style={{ color: "#e8431a", marginRight: "4px" }}>●</span>
                    {COUNTRY_NAMES[hovered]}
                </div>
            )}
        </div>
    );
}
