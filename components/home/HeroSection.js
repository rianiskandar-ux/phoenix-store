"use client";

import Link from "next/link";
import Image from "next/image";
import { useState, useCallback, useRef } from "react";
import { ComposableMap, Geographies, Geography, ZoomableGroup } from "react-simple-maps";
import { geoCentroid, geoBounds } from "d3-geo";

const waveKeyframes = `
@keyframes mapFadeIn {
    from { opacity: 0; transform: scale(0.98); }
    to   { opacity: 1; transform: scale(1); }
}
@keyframes tooltipIn {
    from { opacity: 0; transform: translateY(4px) scale(0.95); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes geoFloat {
    0%   { transform: scale(1.05) translateY(-2px); }
    35%  { transform: scale(1.08) translateY(-6px); }
    70%  { transform: scale(1.06) translateY(-4px); }
    100% { transform: scale(1.05) translateY(-2px); }
}
.rsm-geo {
    transform-box: fill-box;
    transform-origin: center;
    cursor: pointer;
    transition: fill 0.18s ease, filter 0.22s ease;
}
.rsm-geo:hover {
    animation: geoFloat 1.8s cubic-bezier(0.45, 0.05, 0.55, 0.95) infinite;
    filter: drop-shadow(0 6px 16px rgba(232, 67, 26, 0.55));
}
`;

export default function HeroSection() {
    const [mouse, setMouse] = useState({ x: 0, y: 0 });

    const handleMouseMove = useCallback((e) => {
        const rect = e.currentTarget.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width - 0.5) * 2;
        const y = ((e.clientY - rect.top) / rect.height - 0.5) * 2;
        setMouse({ x, y });
    }, []);

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
            {/* line.png */}
            <div style={{ position: "absolute", top: "18%", left: "3%", pointerEvents: "none", opacity: 0.45, transform: `rotate(-15deg) translate(${mouse.x * 6}px, ${mouse.y * 6}px)` }}>
                <Image src="/assets/line.png" alt="" width={72} height={36} style={{ objectFit: "contain" }} />
            </div>
            {/* ornamen black and orange */}
            <div style={{ position: "absolute", bottom: "60px", left: "-28px", pointerEvents: "none", opacity: 0.15, transform: `rotate(12deg) scale(1.1) translate(${mouse.x * 10}px, ${mouse.y * 10}px)` }}>
                <Image src="/assets/ornamen black and orange.png" alt="" width={110} height={110} style={{ objectFit: "contain" }} />
            </div>
            {/* ORNAMEN 3 peach */}
            <div style={{ position: "absolute", top: "-60px", left: "-60px", pointerEvents: "none", opacity: 0.2, ...p(4) }}>
                <Image src="/assets/ORNAMEN 3.png" alt="" width={340} height={210} style={{ objectFit: "contain" }} />
            </div>
            {/* dot grid */}
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

            <div style={{ maxWidth: "1280px", margin: "0 auto", padding: "0 32px" }}>
                <div style={{
                    display: "flex", justifyContent: "space-between",
                    alignItems: "center", gap: "48px", flexWrap: "wrap",
                    minHeight: "calc(100vh - 60px)",
                    paddingTop: "60px", paddingBottom: "80px",
                }}>
                    {/* Left */}
                    <div style={{ maxWidth: "420px", zIndex: 10, position: "relative", flexShrink: 0 }}>
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

                    {/* Right: interactive world map — takes remaining space */}
                    <div style={{ flex: "1", minWidth: "340px", position: "relative", ...p(-5) }}>
                        <WorldMapInteractive />
                    </div>
                </div>
            </div>
        </div>
    );
}

// World TopoJSON from CDN — loaded client-side
const GEO_URL = "https://cdn.jsdelivr.net/npm/world-atlas@2/countries-110m.json";

// ISO numeric → country name
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

// Compute zoom level based on bounding-box area — smaller country → higher zoom
function getZoomForGeo(geo) {
    try {
        const [[x0, y0], [x1, y1]] = geoBounds(geo);
        const area = (x1 - x0) * (y1 - y0);
        if (area < 1)   return 12;
        if (area < 5)   return 8;
        if (area < 20)  return 6;
        if (area < 80)  return 4;
        if (area < 300) return 2.5;
        return 1.6; // Russia, Canada, etc.
    } catch {
        return 4;
    }
}

function WorldMapInteractive() {
    const [hovered, setHovered] = useState(null);
    const [selectedId, setSelectedId] = useState(null);
    const [tooltipPos, setTooltipPos] = useState({ x: 0, y: 0 });
    const [mapCenter, setMapCenter] = useState([10, 20]);
    const [mapZoom, setMapZoom] = useState(1);
    const wrapRef = useRef(null);

    const handleMouseMove = useCallback((e) => {
        if (!wrapRef.current) return;
        const rect = wrapRef.current.getBoundingClientRect();
        setTooltipPos({ x: e.clientX - rect.left, y: e.clientY - rect.top });
    }, []);

    const handleCountryClick = useCallback((geo) => {
        try {
            const centroid = geoCentroid(geo);
            const targetZoom = getZoomForGeo(geo);
            setMapCenter(centroid);
            setMapZoom(targetZoom);
            setSelectedId(geo.id);
        } catch {
            // ignore unprojectable geographies
        }
    }, []);

    const handleReset = useCallback(() => {
        setMapCenter([10, 20]);
        setMapZoom(1);
        setSelectedId(null);
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
                userSelect: "none",
            }}
        >
            {/* Header bar */}
            <div style={{
                display: "flex", alignItems: "center", justifyContent: "space-between",
                padding: "10px 14px 6px",
            }}>
                <div style={{ display: "flex", alignItems: "center", gap: "6px" }}>
                    <div style={{ width: "5px", height: "5px", borderRadius: "50%", background: "#e8431a" }} />
                    <span style={{ fontSize: "0.62rem", fontWeight: 700, color: "#e8431a", letterSpacing: "0.08em" }}>
                        GLOBAL REACH · 50+ LANGUAGES
                    </span>
                </div>
                {selectedId && (
                    <button
                        onClick={handleReset}
                        style={{
                            fontSize: "0.6rem", color: "#888", background: "none",
                            border: "1px solid #e0e0e0", borderRadius: "4px",
                            padding: "2px 8px", cursor: "pointer", letterSpacing: "0.04em",
                        }}
                    >
                        ← Reset
                    </button>
                )}
            </div>

            {/* Hint */}
            {!selectedId && (
                <div style={{ textAlign: "center", paddingBottom: "2px" }}>
                    <span style={{ fontSize: "0.58rem", color: "#bbb", letterSpacing: "0.04em" }}>Click a country to zoom · Drag to pan</span>
                </div>
            )}
            {selectedId && COUNTRY_NAMES[selectedId] && (
                <div style={{ textAlign: "center", paddingBottom: "2px" }}>
                    <span style={{ fontSize: "0.65rem", color: "#e8431a", fontWeight: 700, letterSpacing: "0.04em" }}>
                        {COUNTRY_NAMES[selectedId]}
                    </span>
                </div>
            )}

            <ComposableMap
                projectionConfig={{ scale: 155, center: [10, 20] }}
                style={{ width: "100%", height: "auto" }}
            >
                <ZoomableGroup
                    zoom={mapZoom}
                    center={mapCenter}
                    onMoveEnd={({ coordinates, zoom }) => {
                        setMapCenter(coordinates);
                        setMapZoom(zoom);
                    }}
                >
                    <Geographies geography={GEO_URL}>
                        {({ geographies }) =>
                            geographies.map((geo) => {
                                const id = geo.id;
                                const isSelected = selectedId === id;
                                return (
                                    <Geography
                                        key={geo.rsmKey}
                                        geography={geo}
                                        className="rsm-geo"
                                        onMouseEnter={() => setHovered(id)}
                                        onMouseLeave={() => setHovered(null)}
                                        onClick={() => handleCountryClick(geo)}
                                        style={{
                                            default: {
                                                fill: isSelected ? "#c0361a" : "#D6D9E0",
                                                stroke: "#fff",
                                                strokeWidth: 0.5,
                                                outline: "none",
                                            },
                                            hover: {
                                                fill: "#e8431a",
                                                stroke: "#fff",
                                                strokeWidth: 0.6,
                                                outline: "none",
                                            },
                                            pressed: {
                                                fill: "#a02d15",
                                                outline: "none",
                                            },
                                        }}
                                    />
                                );
                            })
                        }
                    </Geographies>
                </ZoomableGroup>
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
