"use client";

import { useState } from "react";
import Image from "next/image";
import { useReveal, revealStyle } from "@/hooks/useReveal";

const features = [
    { title: "Multi - Organisation", tagline: "Multiple Organisations, One Compliance Solution", icon: "/assets/Icon Multi-Organization.png", detail: "Manage multiple organisations under one account. Each organisation gets its own dedicated whistleblowing platform with separate case management, branding, and reporting." },
    { title: "Multiple Languages", tagline: "Across Borders, Beyond Barriers: Whistleblowing In 50+ Languages", icon: "/assets/Icon Multi-Language.png", detail: "Phoenix supports 50+ languages so every employee can report in their native language. Powered by WPML with full RTL support and automatic browser language detection." },
    { title: "Multiple Templates", tagline: "Express Your Ethics In Your Aesthetic: Wide-Ranging Whistleblowing Templates", icon: "/assets/Icon Multiple-Templates.png", detail: "Choose from a wide range of pre-built templates for your reporting forms. Customise layouts, fields, and branding to match your organisation's identity." },
    { title: "Ease and Speed to Set Up", tagline: "Power Up Your Compliance In Two Hours", icon: "/assets/Icon ease and speed.png", detail: "Your dedicated instance is provisioned automatically. No technical knowledge required — follow the setup wizard and go live within two hours." },
    { title: "Flexibility", tagline: "Your Compliance, Your Way", icon: "/assets/Icon Flexibility.png", detail: "Configure your platform to match your exact compliance requirements. Custom fields, workflows, notification rules, and escalation paths — all configurable." },
    { title: "Confidentiality", tagline: "Ensuring Privacy, Empowering Whistleblowers", icon: "/assets/Icon Confidentiality.png", detail: "End-to-end encryption ensures that all reports remain strictly confidential. IP addresses are never logged and all metadata is stripped from submissions." },
    { title: "Case Management", tagline: "Comprehensive Case Management Tools", icon: "/assets/Icon Case Management.png", detail: "Track, assign, and resolve cases with a powerful built-in case management system. Full audit trail, investigator assignment, evidence management, and resolution tracking." },
    { title: "FADP / GDPR Compliant", tagline: "Ensuring Anonymity in the Age of Transparency", icon: "/assets/Icon GDPR.png", detail: "Built for FADP, GDPR, EU Whistleblowing Directive (2019/1937), and global data protection regulations. Data processing agreements available on request." },
    { title: "Multiple Channels", tagline: "Multiple Channels For Reporting: Speak Up, However You Choose", icon: "/assets/Icon Multipple-Channels.png", detail: "Accept reports via web form, email, phone hotline, QR code, and more. Meet reporters where they are with multiple intake channels." },
    { title: "Affordability", tagline: "Your Business, Your Budget, Your Choice:", icon: "/assets/Icon Affordability.png", detail: "Flexible pricing plans for organisations of all sizes — from free for small entities to enterprise plans for multinationals. No hidden fees." },
    { title: "Security", tagline: "Swiss Security, Global Trust", icon: "/assets/Icon Security.png", detail: "Swiss-grade security infrastructure with AES-256 encryption, zero-knowledge architecture, and regular third-party security audits." },
    { title: "See all features", tagline: "Phoenix Whistleblowing Features", icon: "/assets/Icon See All Features.png", isLink: true, detail: "Explore the full list of Phoenix Whistleblowing Software features — from anonymous reporting and case management to multi-language support and legal compliance." },
];

export default function AboutSection() {
    const [modal, setModal] = useState(null);
    const [hovered, setHovered] = useState(null);
    const intro = useReveal();
    const cards = useReveal();

    return (
        <section style={{ background: "#faf3ec", padding: "90px 32px 140px", position: "relative", overflow: "hidden" }}>
            {/* Dot grid background accent */}
            <div style={{
                position: "absolute", top: 0, right: 0,
                width: "340px", height: "340px",
                backgroundImage: "radial-gradient(circle, #d4b89a 1px, transparent 1px)",
                backgroundSize: "22px 22px",
                opacity: 0.45,
                pointerEvents: "none",
            }} />
            <div style={{
                position: "absolute", bottom: "60px", left: "-40px",
                width: "260px", height: "260px",
                backgroundImage: "radial-gradient(circle, #d4b89a 1px, transparent 1px)",
                backgroundSize: "22px 22px",
                opacity: 0.3,
                pointerEvents: "none",
            }} />
            {/* ORNAMEN 3 — peach half circle bottom-left */}
            <div style={{ position: "absolute", bottom: "-30px", left: "-30px", pointerEvents: "none", opacity: 0.55, zIndex: 0 }}>
                <Image src="/assets/ORNAMEN 3.png" alt="" width={220} height={140} style={{ objectFit: "contain" }} />
            </div>
            {/* line.png — orange lines top-right near title */}
            <div style={{ position: "absolute", top: "60px", right: "32px", pointerEvents: "none", opacity: 0.85, zIndex: 0 }}>
                <Image src="/assets/line.png" alt="" width={80} height={40} style={{ objectFit: "contain" }} />
            </div>

            <div style={{ maxWidth: "1200px", margin: "0 auto", position: "relative", zIndex: 1 }}>

                {/* Intro */}
                <div ref={intro.ref} style={{ maxWidth: "", margin: "0 auto 56px", textAlign: "justify", ...revealStyle(intro.visible, { direction: "up" }) }}>
                    <div style={{ display: "inline-flex", alignItems: "center", gap: "8px", background: "#fff5f2", border: "1px solid rgba(232,67,26,0.2)", borderRadius: "100px", padding: "5px 14px", marginBottom: "20px" }}>
                        <span style={{ width: "6px", height: "6px", borderRadius: "50%", background: "#e8431a", display: "inline-block" }} />
                        <span style={{ color: "#e8431a", fontSize: "0.72rem", fontWeight: 700, letterSpacing: "0.07em" }}>ABOUT PHOENIX</span>
                    </div>
                    <p style={{ color: "#555", fontSize: "1rem", lineHeight: 1.85, margin: "0 0 14px" }}>
                        <strong style={{ color: "#111" }}>Phoenix Whistleblowing Software</strong> is a comprehensive and secure SaaS solution designed for organisations and consultants seeking effective systems to manage reporting and maintain transparency.
                    </p>
                    <p style={{ color: "#555", fontSize: "1rem", lineHeight: 1.85, margin: "0 0 14px" }}>
                        With support for over <strong style={{ color: "#111" }}>50+ languages</strong> and <strong style={{ color: "#111" }}>Swiss-grade security measures</strong>, it offers customizable and multilingual platforms to cater to a diverse array of stakeholders.
                    </p>
                    <p style={{ color: "#555", fontSize: "1rem", lineHeight: 1.85, margin: 0 }}>
                        Whether you&apos;re a small entity, a multinational corporation, or a consultant working on behalf of clients, Phoenix Whistleblowing Software provides the robust features and intuitive interface you need to foster a culture of integrity and ensure compliance with the highest data protection standards.
                    </p>
                </div>

                {/* Cards */}
                <div ref={cards.ref} style={{ display: "grid", gridTemplateColumns: "repeat(4, 1fr)", gap: "16px", ...revealStyle(cards.visible, { direction: "up", delay: 150 }) }}>
                    {features.map((f, i) => (
                        <div
                            key={i}
                            onClick={() => setModal(f)}
                            style={{
                                background: "#fff",
                                borderRadius: "16px",
                                padding: "28px 24px 24px",
                                cursor: "pointer",
                                boxShadow: "0 1px 6px rgba(0,0,0,0.07)",
                                display: "flex",
                                flexDirection: "column",
                                justifyContent: "space-between",
                                minHeight: "180px",
                                transition: "box-shadow 0.18s, transform 0.15s",
                                position: "relative",
                            }}
                            onMouseEnter={e => {
                                setHovered(i);
                                e.currentTarget.style.boxShadow = "0 8px 28px rgba(0,0,0,0.1)";
                                e.currentTarget.style.transform = "translateY(-3px)";
                            }}
                            onMouseLeave={e => {
                                setHovered(null);
                                e.currentTarget.style.boxShadow = "0 1px 6px rgba(0,0,0,0.07)";
                                e.currentTarget.style.transform = "";
                            }}
                        >
                            {/* Arrow top-right */}
                            <div style={{
                                position: "absolute", top: "20px", right: "20px",
                                width: "26px", height: "26px", borderRadius: "50%",
                                background: hovered === i ? "rgba(232,67,26,0.12)" : "transparent",
                                border: hovered === i ? "none" : "1.5px solid #e8e8e8",
                                display: "flex", alignItems: "center", justifyContent: "center",
                                fontSize: "12px",
                                color: hovered === i ? "#e8431a" : "#ccc",
                                transition: "background 0.2s, color 0.2s, border-color 0.2s",
                            }}>↗</div>

                            {/* Title + short desc */}
                            <div style={{ paddingRight: "32px" }}>
                                <h3 style={{
                                    fontSize: "1rem", fontWeight: 800,
                                    color: "#111", margin: "0 0 6px", lineHeight: 1.25,
                                }}>
                                    {f.title}
                                </h3>
                                <p style={{
                                    fontSize: "0.78rem", color: "#888",
                                    margin: 0, lineHeight: 1.5,
                                }}>
                                    {f.tagline}
                                </p>
                            </div>

                            {/* Icon — bottom left */}
                            <div style={{
                                marginTop: "20px",
                                width: "44px", height: "44px", borderRadius: "10px",
                                background: "#f2ece3",
                                display: "flex", alignItems: "center", justifyContent: "center",
                            }}>
                                <Image src={f.icon} alt={f.title} width={28} height={28} style={{ objectFit: "contain" }} />
                            </div>
                        </div>
                    ))}
                </div>
            </div>

            {/* Wave divider bottom — transition ke WorkflowSection orange */}
            <div style={{ position: "absolute", bottom: 0, left: 0, right: 0, lineHeight: 0, pointerEvents: "none" }}>
                <Image src="/assets/wave.png" alt="" width={1440} height={80} style={{ width: "100%", height: "80px", objectFit: "cover", display: "block" }} />
            </div>

            {/* Modal */}
            {modal && (
                <div
                    onClick={() => setModal(null)}
                    style={{
                        position: "fixed", inset: 0,
                        background: "rgba(0,0,0,0.35)",
                        display: "flex", alignItems: "center", justifyContent: "center",
                        zIndex: 1000, padding: "20px",
                        backdropFilter: "blur(4px)",
                    }}
                >
                    <div
                        onClick={e => e.stopPropagation()}
                        style={{
                            background: "#fff", borderRadius: "18px",
                            padding: "40px", maxWidth: "440px", width: "100%",
                            boxShadow: "0 24px 80px rgba(0,0,0,0.15)",
                        }}
                    >
                        <div style={{ marginBottom: "16px" }}>
                            <Image src={modal.icon} alt={modal.title} width={48} height={48} style={{ objectFit: "contain" }} />
                        </div>
                        <h3 style={{ fontSize: "1.2rem", fontWeight: 800, color: "#111", margin: "0 0 6px" }}>{modal.title}</h3>
                        <p style={{ color: "#e8431a", fontSize: "0.8rem", fontWeight: 600, margin: "0 0 16px" }}>{modal.tagline}</p>
                        <p style={{ color: "#555", lineHeight: 1.75, margin: 0, fontSize: "0.9rem" }}>{modal.detail}</p>
                        <button
                            onClick={() => setModal(null)}
                            style={{
                                marginTop: "28px", padding: "10px 24px",
                                background: "#e8431a", color: "#fff",
                                border: "none", borderRadius: "8px",
                                fontWeight: 700, cursor: "pointer", fontSize: "0.875rem",
                            }}
                        >
                            Close
                        </button>
                    </div>
                </div>
            )}
        </section>
    );
}
