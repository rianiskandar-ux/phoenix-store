"use client";

import Link from "next/link";
import Image from "next/image";
import { useReveal, revealStyle } from "@/hooks/useReveal";

export default function CtaSection() {
    const card = useReveal();
    return (
        <section style={{ background: "#f8f9fb", padding: "80px 32px" }}>
            <div style={{ maxWidth: "1200px", margin: "0 auto" }}>
                <div ref={card.ref} style={{
                    background: "linear-gradient(143deg, rgb(10,10,10) 0%, rgb(232,67,26) 100%)",
                    borderRadius: "24px",
                    padding: "64px",
                    display: "flex",
                    alignItems: "center",
                    justifyContent: "space-between",
                    gap: "48px",
                    flexWrap: "wrap",
                    position: "relative",
                    overflow: "hidden",
                    ...revealStyle(card.visible, { direction: "up" }),
                }}>
                    {/* ornamen black and orange — bottom right */}
                    <div style={{ position: "absolute", bottom: "-10px", right: "220px", pointerEvents: "none", opacity: 0.18, zIndex: 0 }}>
                        <Image src="/assets/ornamen black and orange.png" alt="" width={100} height={100} style={{ objectFit: "contain" }} />
                    </div>
                    {/* line.png — orange lines top right */}
                    <div style={{ position: "absolute", top: "24px", right: "24px", pointerEvents: "none", opacity: 0.5, zIndex: 0 }}>
                        <Image src="/assets/line.png" alt="" width={70} height={36} style={{ objectFit: "contain" }} />
                    </div>
                    {/* Top-left accent line */}
                    <div style={{
                        position: "absolute", top: 0, left: "64px",
                        width: "2px", height: "40px",
                        background: "#e8431a", borderRadius: "2px",
                        opacity: 0.8,
                    }} />

                    {/* Left: text + CTA */}
                    <div style={{ maxWidth: "520px", zIndex: 1 }}>
                        <div style={{
                            display: "inline-flex", alignItems: "center", gap: "8px",
                            background: "rgba(232,67,26,0.15)", border: "1px solid rgba(232,67,26,0.3)",
                            borderRadius: "100px", padding: "5px 14px", marginBottom: "20px",
                        }}>
                            <span style={{ width: "6px", height: "6px", borderRadius: "50%", background: "#e8431a", display: "inline-block" }} />
                            <span style={{ color: "#e8431a", fontSize: "0.72rem", fontWeight: 700, letterSpacing: "0.07em" }}>GET STARTED TODAY</span>
                        </div>
                        <h2 style={{
                            fontSize: "clamp(1.6rem, 3vw, 2.4rem)", fontWeight: 800,
                            color: "#fff", margin: "0 0 12px", lineHeight: 1.2,
                        }}>
                            Start using Phoenix<br />
                            Whistleblowing Software{" "}
                            <span style={{ color: "#e8431a" }}>&ldquo;Today&rdquo;</span>
                        </h2>
                        <p style={{ color: "rgba(255,255,255,0.5)", fontSize: "0.95rem", margin: "0 0 32px", lineHeight: 1.6 }}>
                            Letting Integrity Steer Your Success
                        </p>
                        <div style={{ display: "flex", gap: "12px", flexWrap: "wrap" }}>
                            <Link href="/get-started?plan=free" style={{
                                display: "inline-block", padding: "14px 32px",
                                background: "#e8431a", color: "#fff",
                                fontWeight: 700, fontSize: "0.875rem",
                                borderRadius: "8px", textDecoration: "none",
                                boxShadow: "0 6px 24px rgba(232,67,26,0.4)",
                                letterSpacing: "0.02em",
                            }}>
                                Get Started Free
                            </Link>
                            <Link href="/get-started?plan=basic" style={{
                                display: "inline-block", padding: "14px 32px",
                                background: "transparent", color: "rgba(255,255,255,0.85)",
                                fontWeight: 600, fontSize: "0.875rem",
                                borderRadius: "8px", textDecoration: "none",
                                border: "1.5px solid rgba(255,255,255,0.18)",
                                letterSpacing: "0.02em",
                            }}>
                                View Pricing
                            </Link>
                        </div>
                    </div>

                    {/* Right: trust stats */}
                    <div style={{
                        display: "grid", gridTemplateColumns: "1fr 1fr",
                        gap: "16px", zIndex: 1, flexShrink: 0,
                    }}>
                        {[
                            { icon: "🌐", value: "50+", label: "Languages supported" },
                            { icon: "🔒", value: "256-bit", label: "AES encryption" },
                            { icon: "⚖️", value: "GDPR", label: "& FADP compliant" },
                            { icon: "⚡", value: "2 hrs", label: "Average setup time" },
                        ].map((s, i) => (
                            <div key={i} style={{
                                background: "rgba(255,255,255,0.06)",
                                border: "1px solid rgba(255,255,255,0.1)",
                                borderRadius: "14px",
                                padding: "20px 22px",
                                minWidth: "140px",
                            }}>
                                <div style={{ fontSize: "1.3rem", marginBottom: "8px" }}>{s.icon}</div>
                                <div style={{ fontSize: "1.25rem", fontWeight: 800, color: "#fff", lineHeight: 1 }}>{s.value}</div>
                                <div style={{ fontSize: "0.72rem", color: "rgba(255,255,255,0.45)", marginTop: "4px", letterSpacing: "0.03em" }}>{s.label}</div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </section>
    );
}
