"use client";

import Link from "next/link";
import { useState } from "react";

const FEATURES = [
    {
        key: "multi-organisation",
        label: "Multi-organisation",
        icon: "🏢",
        category: "MULTI-ORGANISATION",
        heading: "Multiple Organisations, One Compliance Solution",
        tagline: "Manage every entity under one roof — effortlessly.",
        body: [
            "Experience unparalleled adaptability with Phoenix Whistleblowing Software. Whether you're a conglomerate with a diverse portfolio of companies or a compliance consultant managing multiple clients, Phoenix is designed to meet your needs.",
            "Our software empowers you to create and manage distinct whistleblowing systems for each entity under your care. Each organisation within your account maintains its own independent whistleblowing channel, branding, and administrator access.",
            "With Phoenix, you receive one powerful platform that lets you build as many customised whistleblowing systems as your business demands.",
        ],
        highlights: [
            "Unlimited subsidiary management", "Isolated data environments per organisation",
            "Single login, multiple workspaces", "Centralised billing and admin",
        ],
        mockTitle: "Organisations",
        mockSub: "Active workspaces",
        mockItems: [
            { initial: "B", name: "Basic Company", plan: "Basic", status: "Active" },
            { initial: "S", name: "Standard Company", plan: "Standard", status: "Active" },
            { initial: "E", name: "Enhanced Company", plan: "Premium", status: "Active" },
        ],
    },
    {
        key: "multi-languages",
        label: "Multi languages",
        icon: "🌐",
        category: "MULTI-LANGUAGES",
        heading: "Across Borders, Beyond Barriers",
        tagline: "Whistleblowing in 50+ languages, for a global workforce.",
        body: [
            "Phoenix supports over 50 languages, enabling organisations worldwide to communicate whistleblowing policies effectively across all regions and cultures.",
            "Our multilingual interface ensures reporters can submit in their native language while managers review in theirs — removing language as a barrier to reporting misconduct.",
        ],
        highlights: [
            "50+ supported languages", "Per-organisation language settings",
            "Native-language reporting forms", "Automatic language detection",
        ],
        mockTitle: "Languages",
        mockSub: "Active language packs",
        mockItems: [
            { initial: "EN", name: "English", plan: "Active", status: "Active" },
            { initial: "FR", name: "French", plan: "Active", status: "Active" },
            { initial: "DE", name: "German", plan: "Active", status: "Active" },
        ],
    },
    {
        key: "multiple-channels",
        label: "Multiple channels",
        icon: "📡",
        category: "MULTIPLE CHANNELS",
        heading: "Multiple Channels for Reporting",
        tagline: "Speak Up, However You Choose.",
        body: [
            "Give your stakeholders the flexibility to report through webform, email, phone, postal address, instant messaging, or live online chat — whichever channel they feel safest using.",
            "Each channel is configured independently, allowing you to activate only what your compliance programme requires.",
        ],
        highlights: [
            "Web form reporting", "Email & phone channels",
            "Instant messaging support", "Online chat room",
        ],
        mockTitle: "Channels",
        mockSub: "Configured channels",
        mockItems: [
            { initial: "W", name: "Web Form", plan: "Active", status: "Active" },
            { initial: "E", name: "Email Channel", plan: "Active", status: "Active" },
            { initial: "C", name: "Chat Room", plan: "Premium", status: "Active" },
        ],
    },
    {
        key: "multiple-templates",
        label: "Multiple templates",
        icon: "🎨",
        category: "MULTIPLE TEMPLATES",
        heading: "Express Your Ethics in Your Aesthetic",
        tagline: "Wide-ranging whistleblowing templates for every brand.",
        body: [
            "Choose from a library of professionally designed templates to match your brand identity and communicate your values through your compliance interface.",
            "Every template is fully responsive, accessible, and customisable — so your reporting portal looks like it belongs to your organisation.",
        ],
        highlights: [
            "Professional template library", "Brand colour customisation",
            "Logo and identity integration", "Mobile-responsive layouts",
        ],
        mockTitle: "Templates",
        mockSub: "Available designs",
        mockItems: [
            { initial: "D", name: "Default Theme", plan: "Free", status: "Active" },
            { initial: "C", name: "Corporate Theme", plan: "Basic", status: "Active" },
            { initial: "P", name: "Premium Theme", plan: "Premium", status: "Active" },
        ],
    },
    {
        key: "flexibility",
        label: "Flexibility",
        icon: "⚙️",
        category: "FLEXIBILITY",
        heading: "Your Compliance, Your Way",
        tagline: "Configure every aspect to fit your exact requirements.",
        body: [
            "Customise questionnaire structure, domain names, branding, and user roles to fit your exact organisational requirements.",
            "Phoenix adapts to your compliance programme — not the other way around.",
        ],
        highlights: [
            "Custom questionnaire builder", "Role-based access control",
            "Domain name selection", "Configurable workflows",
        ],
        mockTitle: "Configuration",
        mockSub: "Active settings",
        mockItems: [
            { initial: "Q", name: "Questionnaire", plan: "Custom", status: "Active" },
            { initial: "R", name: "Roles & Access", plan: "Active", status: "Active" },
            { initial: "D", name: "Domain Settings", plan: "Active", status: "Active" },
        ],
    },
    {
        key: "affordability",
        label: "Affordability",
        icon: "💰",
        category: "AFFORDABILITY",
        heading: "Your Business, Your Budget, Your Choice",
        tagline: "Transparent pricing for every stage of your growth.",
        body: [
            "Phoenix offers tiered pricing plans to suit businesses of all sizes — from small local companies to large multinationals — with no hidden fees and no long-term lock-in.",
            "Start free and upgrade as your compliance needs evolve.",
        ],
        highlights: [
            "Free Starter plan", "Monthly or annual billing",
            "No hidden fees", "Upgrade or cancel anytime",
        ],
        mockTitle: "Plans",
        mockSub: "Pricing options",
        mockItems: [
            { initial: "S", name: "Starter", plan: "Free", status: "Active" },
            { initial: "B", name: "Basic", plan: "$65/mo", status: "Active" },
            { initial: "P", name: "Premium", plan: "$110/mo", status: "Active" },
        ],
    },
    {
        key: "ease-speed",
        label: "Ease and speed to set up",
        icon: "⚡",
        category: "EASE & SPEED",
        heading: "Power Up Your Compliance in Two Hours",
        tagline: "No technical expertise required.",
        body: [
            "Get your whistleblowing system live in as little as two hours. Our intuitive setup wizard guides you through every step — from branding to channel configuration.",
            "No developers, no delays. Just a compliant, branded reporting system ready for your organisation.",
        ],
        highlights: [
            "Guided setup wizard", "Live in under 2 hours",
            "No technical expertise needed", "24/7 onboarding support",
        ],
        mockTitle: "Setup Progress",
        mockSub: "Onboarding steps",
        mockItems: [
            { initial: "1", name: "Organisation Created", plan: "Done", status: "Active" },
            { initial: "2", name: "Branding Configured", plan: "Done", status: "Active" },
            { initial: "3", name: "Channels Activated", plan: "Done", status: "Active" },
        ],
    },
    {
        key: "case-management",
        label: "Case management",
        icon: "📋",
        category: "CASE MANAGEMENT",
        heading: "Comprehensive Case Management Tools",
        tagline: "Track, assign, and resolve reports with full audit trail.",
        body: [
            "Manage, track, and resolve reports efficiently with our built-in case management dashboard. Assign cases to operators, set statuses, and maintain a complete audit log.",
            "Designed for compliance managers, operators, and agents — with role-based visibility and workflow controls.",
        ],
        highlights: [
            "Full audit trail", "Multi-role case assignment",
            "Status tracking & escalation", "Secure internal communication",
        ],
        mockTitle: "Cases",
        mockSub: "Open cases",
        mockItems: [
            { initial: "C", name: "Case #1042", plan: "Open", status: "Active" },
            { initial: "C", name: "Case #1041", plan: "In Review", status: "Active" },
            { initial: "C", name: "Case #1040", plan: "Resolved", status: "Active" },
        ],
    },
    {
        key: "security",
        label: "Security",
        icon: "🔒",
        category: "SECURITY",
        heading: "Swiss Security, Global Trust",
        tagline: "Enterprise-grade protection for sensitive disclosures.",
        body: [
            "Servers located in Switzerland, end-to-end encryption, strict data protection compliance, and minimal third-party exposure ensure the highest level of data security.",
            "Phoenix is engineered so that even administrators cannot identify anonymous reporters.",
        ],
        highlights: [
            "Swiss-hosted servers", "End-to-end encryption",
            "True anonymity guarantee", "FADP & GDPR compliant",
        ],
        mockTitle: "Security",
        mockSub: "Active protections",
        mockItems: [
            { initial: "E", name: "E2E Encryption", plan: "Active", status: "Active" },
            { initial: "A", name: "Anonymisation", plan: "Active", status: "Active" },
            { initial: "S", name: "Swiss Hosting", plan: "Active", status: "Active" },
        ],
    },
    {
        key: "confidentiality",
        label: "Confidentiality",
        icon: "🔏",
        category: "CONFIDENTIALITY",
        heading: "Ensuring Privacy, Empowering Whistleblowers",
        tagline: "Reporter identity is protected at every layer.",
        body: [
            "Anonymous and confidential reporting options give whistleblowers the confidence to come forward, backed by robust technical safeguards.",
            "Phoenix never stores identifying metadata for anonymous submissions — not even IP addresses.",
        ],
        highlights: [
            "Zero metadata logging", "Anonymous submission mode",
            "Secure two-way messaging", "Reporter-controlled identity",
        ],
        mockTitle: "Privacy",
        mockSub: "Protections enabled",
        mockItems: [
            { initial: "M", name: "No Metadata", plan: "Active", status: "Active" },
            { initial: "A", name: "Anonymous Mode", plan: "Active", status: "Active" },
            { initial: "S", name: "Secure Messaging", plan: "Active", status: "Active" },
        ],
    },
    {
        key: "fadp-gdpr",
        label: "FADP / GDPR compliant",
        icon: "✅",
        category: "FADP / GDPR",
        heading: "Anonymous, Secure, and Fully Compliant",
        tagline: "Built for European and international data protection laws.",
        body: [
            "Phoenix is fully compliant with GDPR and the Swiss Federal Act on Data Protection (FADP), ensuring your organisation meets all legal obligations for whistleblowing systems.",
            "We also support compliance with EU Directive 2019/1937 on the protection of persons who report breaches of Union law.",
        ],
        highlights: [
            "GDPR Article 5 compliant", "FADP certified",
            "EU Directive 2019/1937 ready", "ISO 27001 aligned",
        ],
        mockTitle: "Compliance",
        mockSub: "Active certifications",
        mockItems: [
            { initial: "G", name: "GDPR", plan: "Certified", status: "Active" },
            { initial: "F", name: "FADP", plan: "Certified", status: "Active" },
            { initial: "I", name: "ISO 27001", plan: "Aligned", status: "Active" },
        ],
    },
];

const PLAN_COLORS = {
    "Free": "#16a34a", "Active": "#16a34a", "Done": "#16a34a", "Resolved": "#16a34a",
    "Basic": "#2563eb", "Standard": "#7c3aed", "Custom": "#7c3aed",
    "Premium": "#e8431a", "In Review": "#e8431a", "Open": "#f59e0b",
    "Certified": "#16a34a", "Aligned": "#2563eb",
};

export default function FeaturesPage() {
    const [active, setActive] = useState(FEATURES[0]);

    return (
        <div style={{ background: "#f8f9fb", minHeight: "100vh" }}>

            {/* Hero */}
            <div style={{
                background: "linear-gradient(135deg, #fde8e2 0%, #f3e8f8 60%, #fff3e0 100%)",
                padding: "70px 48px 0",
                position: "relative",
                overflow: "hidden",
            }}>
                <div style={{ position: "absolute", top: "-20px", right: "-20px", opacity: 0.35, pointerEvents: "none" }}>
                    <img src="/assets/ORNAMEN 3.png" alt="" width={200} style={{ objectFit: "contain", transform: "rotate(180deg)" }} />
                </div>
                <div style={{ position: "absolute", bottom: "30px", left: "-20px", opacity: 0.25, pointerEvents: "none" }}>
                    <img src="/assets/ORNAMEN 2.png" alt="" width={160} style={{ objectFit: "contain", transform: "rotate(180deg)" }} />
                </div>

                <div style={{ maxWidth: "1100px", margin: "0 auto", display: "grid", gridTemplateColumns: "1fr auto", gap: "48px", alignItems: "center", position: "relative", zIndex: 1 }}>
                    <div>
                        <div style={{
                            display: "inline-flex", alignItems: "center", gap: "8px",
                            background: "rgba(255,255,255,0.7)", border: "1px solid rgba(232,67,26,0.2)",
                            borderRadius: "100px", padding: "5px 14px", marginBottom: "20px",
                        }}>
                            <span style={{ width: "6px", height: "6px", borderRadius: "50%", background: "#e8431a", display: "inline-block" }} />
                            <span style={{ color: "#e8431a", fontSize: "0.72rem", fontWeight: 700, letterSpacing: "0.07em" }}>PLATFORM FEATURES</span>
                        </div>
                        <h1 style={{ fontSize: "clamp(2rem, 4vw, 3rem)", fontWeight: 900, color: "#111", margin: "0 0 16px", lineHeight: 1.15 }}>
                            Everything You Need for<br />
                            <span style={{ color: "#e8431a" }}>Enterprise Compliance</span>
                        </h1>
                        <p style={{ color: "#666", fontSize: "0.95rem", maxWidth: "420px", lineHeight: 1.7, margin: 0 }}>
                            Phoenix is built to handle the complexity of modern whistleblowing compliance — from multi-entity management to GDPR-ready architecture.
                        </p>
                    </div>

                    {/* CTA — right side like reference */}
                    <div style={{ display: "flex", flexDirection: "column", gap: "12px", flexShrink: 0 }}>
                        <Link href="/get-started" style={{
                            padding: "13px 28px", borderRadius: "8px",
                            background: "#e8431a", color: "#fff",
                            fontSize: "0.85rem", fontWeight: 700, textDecoration: "none",
                            boxShadow: "0 6px 20px rgba(232,67,26,0.35)",
                            textAlign: "center", whiteSpace: "nowrap",
                        }}>Get Started Free</Link>
                        <Link href="/pricing" style={{
                            padding: "13px 28px", borderRadius: "8px",
                            background: "#fff", color: "#333",
                            border: "1.5px solid #e0e0e0",
                            fontSize: "0.85rem", fontWeight: 600, textDecoration: "none",
                            textAlign: "center", whiteSpace: "nowrap",
                        }}>View Pricing</Link>
                    </div>
                </div>

                <div style={{ lineHeight: 0, marginTop: "48px" }}>
                    <svg viewBox="0 0 1440 60" preserveAspectRatio="none" style={{ width: "100%", height: "60px", display: "block" }}>
                        <path d="M0,30 C360,60 1080,0 1440,30 L1440,60 L0,60 Z" fill="#f8f9fb" />
                    </svg>
                </div>
            </div>

            {/* Feature explorer */}
            <div style={{ maxWidth: "1100px", margin: "0 auto", padding: "48px 24px" }}>
                <div style={{ display: "grid", gridTemplateColumns: "200px 1fr 260px", gap: "28px", alignItems: "start" }}>

                    {/* Left nav */}
                    <div style={{
                        background: "#fff", borderRadius: "14px",
                        border: "1px solid #ebebeb",
                        overflow: "hidden",
                        boxShadow: "0 2px 12px rgba(0,0,0,0.05)",
                        position: "sticky", top: "76px",
                    }}>
                        <div style={{ padding: "14px 16px 8px", fontSize: "0.6rem", fontWeight: 800, color: "#aaa", letterSpacing: "0.1em", textTransform: "uppercase" }}>
                            Our Features
                        </div>
                        {FEATURES.map((f) => {
                            const isActive = active.key === f.key;
                            return (
                                <button
                                    key={f.key}
                                    onClick={() => setActive(f)}
                                    style={{
                                        display: "flex", alignItems: "center", gap: "10px",
                                        width: "100%", padding: "10px 12px",
                                        background: isActive ? "rgba(232,67,26,0.07)" : "transparent",
                                        border: "none", borderBottom: "1px solid #f5f5f5",
                                        cursor: "pointer", textAlign: "left",
                                        transition: "background 0.12s",
                                    }}
                                    onMouseEnter={e => { if (!isActive) e.currentTarget.style.background = "#fafafa"; }}
                                    onMouseLeave={e => { if (!isActive) e.currentTarget.style.background = "transparent"; }}
                                >
                                    <span style={{
                                        width: "28px", height: "28px", borderRadius: "8px", flexShrink: 0,
                                        background: isActive ? "rgba(232,67,26,0.12)" : "#f5f5f5",
                                        display: "flex", alignItems: "center", justifyContent: "center",
                                        fontSize: "0.85rem",
                                    }}>{f.icon}</span>
                                    <span style={{
                                        fontSize: "0.78rem", fontWeight: isActive ? 700 : 400,
                                        color: isActive ? "#e8431a" : "#444", lineHeight: 1.3,
                                    }}>{f.label}</span>
                                </button>
                            );
                        })}
                    </div>

                    {/* Main content */}
                    <div key={active.key} style={{
                        background: "#fff", borderRadius: "14px",
                        border: "1px solid #ebebeb",
                        padding: "36px 40px",
                        boxShadow: "0 2px 12px rgba(0,0,0,0.05)",
                    }}>
                        <div style={{ fontSize: "0.65rem", fontWeight: 800, color: "#e8431a", letterSpacing: "0.12em", marginBottom: "12px" }}>
                            {active.category}
                        </div>
                        <h2 style={{ fontSize: "clamp(1.4rem, 2.5vw, 1.9rem)", fontWeight: 900, color: "#111", margin: "0 0 10px", lineHeight: 1.2 }}>
                            {active.heading}
                        </h2>
                        <p style={{ fontSize: "0.92rem", color: "#666", margin: "0 0 16px", lineHeight: 1.6 }}>
                            {active.tagline}
                        </p>
                        <div style={{ display: "flex", gap: "6px", marginBottom: "28px" }}>
                            <div style={{ width: "32px", height: "3px", borderRadius: "2px", background: "#e8431a" }} />
                            <div style={{ width: "16px", height: "3px", borderRadius: "2px", background: "rgba(232,67,26,0.3)" }} />
                        </div>

                        {active.body.map((p, i) => (
                            <p key={i} style={{ fontSize: "0.88rem", color: "#555", lineHeight: 1.8, margin: "0 0 16px" }}>{p}</p>
                        ))}

                        <div style={{ marginTop: "32px" }}>
                            <div style={{ fontSize: "0.7rem", fontWeight: 800, color: "#333", letterSpacing: "0.08em", textTransform: "uppercase", marginBottom: "16px" }}>
                                Key Highlights
                            </div>
                            <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: "10px" }}>
                                {active.highlights.map((h, i) => (
                                    <div key={i} style={{ display: "flex", alignItems: "center", gap: "8px" }}>
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" style={{ flexShrink: 0 }}>
                                            <circle cx="8" cy="8" r="8" fill="rgba(232,67,26,0.1)" />
                                            <path d="M4.5 8l2.5 2.5 4.5-5" stroke="#e8431a" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                        </svg>
                                        <span style={{ fontSize: "0.8rem", color: "#444" }}>{h}</span>
                                    </div>
                                ))}
                            </div>
                        </div>

                        <div style={{ display: "flex", gap: "12px", marginTop: "32px", paddingTop: "24px", borderTop: "1px solid #f0f0f0" }}>
                            <Link href="/get-started" style={{
                                padding: "10px 22px", borderRadius: "8px",
                                background: "#e8431a", color: "#fff",
                                fontSize: "0.82rem", fontWeight: 700, textDecoration: "none",
                                boxShadow: "0 4px 14px rgba(232,67,26,0.3)",
                            }}>Request a Demo</Link>
                            <Link href="/resources" style={{
                                padding: "10px 22px", borderRadius: "8px",
                                background: "transparent", color: "#444",
                                border: "1.5px solid #e0e0e0",
                                fontSize: "0.82rem", fontWeight: 600, textDecoration: "none",
                            }}>View Documentation</Link>
                        </div>
                    </div>

                    {/* Right mockup card */}
                    <div style={{
                        background: "#fff", borderRadius: "14px",
                        border: "1px solid #ebebeb",
                        overflow: "hidden",
                        boxShadow: "0 2px 16px rgba(0,0,0,0.07)",
                        position: "sticky", top: "76px",
                    }}>
                        <div style={{ padding: "16px 18px 12px", borderBottom: "1px solid #f0f0f0", display: "flex", justifyContent: "space-between", alignItems: "center" }}>
                            <div>
                                <div style={{ fontSize: "0.82rem", fontWeight: 700, color: "#111" }}>{active.mockTitle}</div>
                                <div style={{ fontSize: "0.7rem", color: "#aaa", marginTop: "2px" }}>{active.mockSub}</div>
                            </div>
                            <span style={{ fontSize: "0.7rem", fontWeight: 700, color: "#e8431a" }}>Multi-Org</span>
                        </div>

                        {active.mockItems.map((item, i) => (
                            <div key={i} style={{
                                padding: "14px 18px", borderBottom: "1px solid #f5f5f5",
                            }}>
                                <div style={{ display: "flex", alignItems: "center", gap: "10px", marginBottom: "10px" }}>
                                    <div style={{
                                        width: "32px", height: "32px", borderRadius: "8px", flexShrink: 0,
                                        background: "linear-gradient(135deg, #e8431a, #ff7043)",
                                        display: "flex", alignItems: "center", justifyContent: "center",
                                        fontSize: "0.7rem", fontWeight: 800, color: "#fff",
                                    }}>{item.initial}</div>
                                    <div style={{ flex: 1, minWidth: 0 }}>
                                        <div style={{ fontSize: "0.8rem", fontWeight: 600, color: "#111", whiteSpace: "nowrap", overflow: "hidden", textOverflow: "ellipsis" }}>{item.name}</div>
                                        <div style={{ display: "flex", alignItems: "center", gap: "4px", marginTop: "2px" }}>
                                            <span style={{ width: "6px", height: "6px", borderRadius: "50%", background: "#16a34a", flexShrink: 0 }} />
                                            <span style={{ fontSize: "0.68rem", color: "#16a34a", fontWeight: 600 }}>{item.status}</span>
                                        </div>
                                    </div>
                                    <span style={{ fontSize: "0.68rem", color: "#bbb" }}>···</span>
                                </div>
                                <div style={{ display: "flex", flexDirection: "column", gap: "5px" }}>
                                    <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center" }}>
                                        <span style={{ fontSize: "0.68rem", color: "#aaa" }}>Plan</span>
                                        <span style={{
                                            fontSize: "0.68rem", fontWeight: 700,
                                            color: PLAN_COLORS[item.plan] || "#555",
                                        }}>{item.plan}</span>
                                    </div>
                                </div>
                            </div>
                        ))}

                        <div style={{ padding: "14px 18px", display: "flex", alignItems: "center", justifyContent: "center", gap: "6px", cursor: "pointer", color: "#bbb" }}>
                            <span style={{ fontSize: "1.1rem", lineHeight: 1 }}>+</span>
                            <span style={{ fontSize: "0.75rem" }}>Add {active.mockTitle}</span>
                        </div>
                        <div style={{ padding: "10px 18px 14px", textAlign: "center" }}>
                            <p style={{ fontSize: "0.65rem", color: "#ccc", margin: 0, lineHeight: 1.5 }}>
                                Each {active.mockTitle.toLowerCase()} has its own isolated<br />environment & settings.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {/* CTA footer */}
            <div style={{ background: "#fff", borderTop: "1px solid #f0f0f0", padding: "64px 24px", textAlign: "center" }}>
                <div style={{ fontSize: "0.68rem", fontWeight: 800, color: "#e8431a", letterSpacing: "0.1em", textTransform: "uppercase", marginBottom: "16px" }}>
                    Ready to get started?
                </div>
                <h2 style={{ fontSize: "clamp(1.5rem, 3vw, 2.2rem)", fontWeight: 900, color: "#111", margin: "0 0 12px" }}>
                    Join thousands of organisations already using Phoenix
                </h2>
                <p style={{ fontSize: "0.9rem", color: "#888", maxWidth: "400px", margin: "0 auto 32px", lineHeight: 1.7 }}>
                    Start your compliance journey today. Full-featured free trial, no credit card required.
                </p>
                <div style={{ display: "flex", gap: "12px", justifyContent: "center", flexWrap: "wrap", marginBottom: "36px" }}>
                    <Link href="/get-started" style={{
                        padding: "13px 28px", borderRadius: "8px",
                        background: "#e8431a", color: "#fff",
                        fontSize: "0.9rem", fontWeight: 700, textDecoration: "none",
                        boxShadow: "0 6px 20px rgba(232,67,26,0.35)",
                    }}>Start Free Trial</Link>
                    <Link href="/contact" style={{
                        padding: "13px 28px", borderRadius: "8px",
                        background: "transparent", color: "#333",
                        border: "1.5px solid #e0e0e0",
                        fontSize: "0.9rem", fontWeight: 600, textDecoration: "none",
                    }}>Book a Demo</Link>
                </div>
                <div style={{ display: "flex", justifyContent: "center", gap: "32px", flexWrap: "wrap" }}>
                    {[
                        { icon: "🛡️", text: "GDPR & FADP Compliant" },
                        { icon: "🏅", text: "ISO 27001 Certified" },
                        { icon: "⚡", text: "Live in 24 Hours" },
                        { icon: "🌐", text: "25+ Languages" },
                    ].map((b) => (
                        <div key={b.text} style={{ display: "flex", alignItems: "center", gap: "6px" }}>
                            <span style={{ fontSize: "0.9rem" }}>{b.icon}</span>
                            <span style={{ fontSize: "0.75rem", color: "#888", fontWeight: 500 }}>{b.text}</span>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
}
