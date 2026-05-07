"use client";

import { useState } from "react";
import { useReveal, revealStyle } from "@/hooks/useReveal";

const YOUTUBE_VIDEO_ID = "placeholder"; // TODO: replace with real YouTube video ID

export default function VideoSection() {
    const [playing, setPlaying] = useState(false);
    const content = useReveal();

    return (
        <section style={{ background: "#fdf6f0", position: "relative", paddingBottom: "100px" }}>
            {/* Wave top */}
            <div style={{ lineHeight: 0, marginTop: "-1px" }}>
                <svg viewBox="0 0 1440 80" preserveAspectRatio="none" style={{ width: "100%", height: "80px", display: "block" }}>
                    <path d="M0,40 C240,80 480,0 720,40 C960,80 1200,0 1440,40 L1440,0 L0,0 Z" fill="#f8f9fb" />
                </svg>
            </div>

            {/* Decorative double-slash accent top-right */}
            <div style={{ position: "absolute", top: "90px", right: "calc((100% - 860px)/2 - 20px)", zIndex: 1 }}>
                <svg width="44" height="32" viewBox="0 0 44 32" fill="none">
                    <line x1="6" y1="28" x2="20" y2="4" stroke="#e8431a" strokeWidth="2.5" strokeLinecap="round"/>
                    <line x1="18" y1="28" x2="32" y2="4" stroke="#e8431a" strokeWidth="2.5" strokeLinecap="round"/>
                </svg>
            </div>

            {/* Curly arrow pointing down to video */}
            <div style={{ textAlign: "center", marginBottom: "-4px", position: "relative", zIndex: 1 }}>
                <svg width="80" height="50" viewBox="0 0 80 50" fill="none">
                    <path d="M20 8 Q30 8 35 20 Q40 32 50 38 Q56 42 60 40" stroke="#c8a882" strokeWidth="1.8" strokeLinecap="round" fill="none"/>
                    <path d="M54 35 L60 40 L55 45" stroke="#c8a882" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" fill="none"/>
                </svg>
            </div>

            <div ref={content.ref} style={{ maxWidth: "860px", margin: "0 auto", padding: "0 32px", textAlign: "center", position: "relative", zIndex: 1, ...revealStyle(content.visible, { direction: "up" }) }}>
                <h2 style={{ fontSize: "clamp(1.5rem, 3vw, 2.2rem)", fontWeight: 900, color: "#111", margin: "16px 0 8px", lineHeight: 1.25 }}>
                    Phoenix Whistleblowing Software
                </h2>
                <p style={{ color: "#555", fontSize: "1.1rem", marginBottom: "48px", fontWeight: 400 }}>
                    A multi-lingual and multi-channel{" "}
                    <span style={{ color: "#e8431a", fontWeight: 700 }}>SaaS Solution.</span>
                </p>

                {/* Video container */}
                <div style={{
                    position: "relative",
                    paddingBottom: "56.25%",
                    height: 0,
                    borderRadius: "20px",
                    overflow: "hidden",
                    boxShadow: "0 24px 80px rgba(0,0,0,0.14)",
                }}>
                    {!playing ? (
                        <div
                            onClick={() => setPlaying(true)}
                            style={{
                                position: "absolute", inset: 0,
                                display: "flex", flexDirection: "column",
                                alignItems: "center", justifyContent: "center",
                                cursor: "pointer",
                                background: "linear-gradient(160deg, #1a1a2e 0%, #2d1810 50%, #1a1a1a 100%)",
                            }}
                        >
                            {/* Simulated office meeting scene */}
                            <div style={{
                                position: "absolute", inset: 0,
                                background: "linear-gradient(160deg, rgba(15,25,50,0.95) 0%, rgba(40,20,10,0.9) 60%, rgba(10,10,10,0.95) 100%)",
                            }} />
                            <div style={{ position: "relative", zIndex: 1, display: "flex", flexDirection: "column", alignItems: "center" }}>
                                <div style={{
                                    width: "72px", height: "72px", borderRadius: "50%",
                                    background: "rgba(255,255,255,0.95)",
                                    display: "flex", alignItems: "center", justifyContent: "center",
                                    boxShadow: "0 8px 40px rgba(0,0,0,0.4)",
                                    transition: "transform 0.2s",
                                }}
                                    onMouseEnter={e => e.currentTarget.style.transform = "scale(1.08)"}
                                    onMouseLeave={e => e.currentTarget.style.transform = "scale(1)"}
                                >
                                    <svg width="26" height="26" viewBox="0 0 24 24" fill="#e8431a">
                                        <polygon points="6 3 20 12 6 21 6 3" />
                                    </svg>
                                </div>
                                <p style={{ color: "rgba(255,255,255,0.7)", marginTop: "14px", fontSize: "0.85rem", letterSpacing: "0.04em" }}>
                                    Watch the demo
                                </p>
                            </div>
                        </div>
                    ) : (
                        <iframe
                            style={{ position: "absolute", inset: 0, width: "100%", height: "100%", border: "none" }}
                            src={`https://www.youtube.com/embed/${YOUTUBE_VIDEO_ID}?autoplay=1`}
                            title="Phoenix Whistleblowing Software Demo"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowFullScreen
                        />
                    )}
                </div>

                <div style={{ marginTop: "24px" }}>
                    <a
                        href="https://www.youtube.com/channel/UCIYXEO_W_OqhMnBFc8Tabmw"
                        target="_blank" rel="noopener noreferrer"
                        style={{ color: "#aaa", fontSize: "0.85rem", textDecoration: "none", display: "inline-flex", alignItems: "center", gap: "6px" }}
                    >
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#e8431a" strokeWidth="2">
                            <circle cx="12" cy="12" r="10" />
                            <polygon points="10 8 16 12 10 16 10 8" fill="#e8431a" stroke="none" />
                        </svg>
                        More videos on our YouTube channel
                    </a>
                </div>
            </div>
        </section>
    );
}
