"use client";

import Link from "next/link";
import Image from "next/image";
import React, { useState } from "react";
import { useReveal, revealStyle } from "@/hooks/useReveal";

const plans = [
    {
        key: "starter",
        name: "Starter",
        price: "Free",
        period: null,
        priceNote: "Begin for Free – Upgrade Anytime",
        subNote: "Best for Small Businesses",
        saving: null,
        cta: "Get Started Free",
        ctaLink: "/get-started?plan=free",
        popular: false,
        desc: "Best for a locally-owned and operated business with a limited number of employees looking to foster transparency and accountability.",
        note: "No credit card required. No hidden fees.",
        groups: [
            { label: "1 Dedicated website", items: [] },
            { label: "Channels", items: ["Webform only"] },
            { label: "Web Form", items: ["Choice between 3 questionnaires"] },
            { label: "User Accounts", items: ["1 account as Manager"] },
            { label: "Languages & Localisation", items: ["1 language only"] },
            { label: "Themes", items: ["Default theme (logo can be added)"] },
            { label: "Server", items: ["Choice of server"] },
            { label: "Domain", items: ["Choice of Phoenix web domains"] },
            { label: "Assistance", items: ["Self-serve knowledge base"] },
        ],
    },
    {
        key: "basic",
        name: "Basic",
        price: "$65",
        period: "/month",
        priceNote: "or $650/year (pay upfront)",
        saving: "Save 17%",
        savingNote: "Equivalent to $55/month",
        cta: "Get Started",
        ctaLink: "/get-started?plan=basic",
        popular: false,
        desc: "Designed for small companies dependent on multiple vendors and subcontractors and potentially leading to unethical practices.",
        groups: [
            { label: "1 Dedicated website", items: [] },
            { label: "Channels", items: ["1 Email Address", "1 Phone Number", "1 Instant Messaging", "1 Postal Address"] },
            { label: "Web Form", items: ["Choice between 3 questionnaires"] },
            { label: "User Accounts", items: ["1 account as Manager"] },
            { label: "Languages & Localisation", items: ["2 languages"] },
            { label: "Themes", items: ["Access to 3 themes only"] },
            { label: "Server", items: ["Choice of server"] },
            { label: "Domain", items: ["Choice of Phoenix web domains"] },
            { label: "Assistance", items: ["Self-serve knowledge base", "Ticketing (3-day response time)"] },
            { label: "Add-ons", items: ["Available"] },
        ],
    },
    {
        key: "premium",
        name: "Premium",
        price: "$110",
        period: "/month",
        priceNote: "or $995/year (pay upfront)",
        saving: "Save 25%",
        savingNote: "Equivalent to $83/month",
        cta: "Get Started",
        ctaLink: "/get-started?plan=premium",
        popular: true,
        desc: "Most popular plan for multinational corporations concerned by misconduct, discrimination, and potential intellectual property breaches.",
        groups: [
            { label: "1 Dedicated website", items: [] },
            { label: "Channels", items: ["1 Email Address", "1 Phone Number", "1 Instant Messaging", "1 Postal Address", "1 Online Chat room"] },
            { label: "Web Form", items: ["Choice between 3 questionnaires", "Customizable questionnaire (choice of questions and sequence)"] },
            { label: "User Accounts", items: ["1 account as Manager", "1 account as Operator", "1 account as Agent"] },
            { label: "Language & Localisation", items: ["2 languages"] },
            { label: "Themes", items: ["Access to theme library"] },
            { label: "Server", items: ["Choice of server"] },
            { label: "Domain", items: ["Choice of Phoenix web domains", "Custom domain name"] },
            { label: "Assistance", items: ["Self-serve knowledge base", "Ticketing (3-day response time)"] },
            { label: "Add-ons", items: ["Available"] },
        ],
    },
    {
        key: "enterprise",
        name: "Enterprise",
        price: "Talk to Us",
        period: null,
        priceNote: "Get a special price for enterprise usage",
        saving: null,
        cta: "Contact Us",
        ctaLink: "/contact",
        popular: false,
        desc: "Enhance your compliance program with Phoenix Whistleblowing Software, a powerful SaaS solution with a Dedicated Account Manager for seamless support.",
        groups: [
            { label: "Customizable Reporting Channels", items: [] },
            { label: "Advanced User Accounts", items: [] },
            { label: "Many Language Options", items: [] },
            { label: "Fully Customizable Themes", items: [] },
            { label: "High-End Server & Security", items: [] },
            { label: "Premium Assistance & Support", items: [] },
            { label: "Flexible Payment Terms", items: [] },
        ],
    },
];

const compareFeatures = [
    {
        category: "Reporting Channels",
        rows: [
            { feature: "Web form", starter: "partial", basic: true, premium: true, enterprise: true },
            { feature: "Email address", starter: false, basic: true, premium: true, enterprise: true },
            { feature: "Phone number display", starter: false, basic: true, premium: true, enterprise: true },
            { feature: "Instant messaging display", starter: false, basic: true, premium: true, enterprise: true },
            { feature: "Postal address display", starter: false, basic: true, premium: true, enterprise: true },
            { feature: "Chat Room", starter: false, basic: false, premium: true, enterprise: true },
        ],
    },
    {
        category: "User Accounts & Case Management",
        rows: [
            { feature: "Single Account", starter: true, basic: true, premium: true, enterprise: true },
            { feature: "Multi-User Accounts", starter: false, basic: false, premium: true, enterprise: true },
            { feature: "Case Management Dashboard", starter: false, basic: true, premium: true, enterprise: true },
        ],
    },
    {
        category: "Language & Localisation",
        rows: [
            { feature: "Single Language", starter: true, basic: true, premium: true, enterprise: true },
            { feature: "Multi-Languages", starter: false, basic: false, premium: true, enterprise: true },
        ],
    },
    {
        category: "Themes & Customisation",
        rows: [
            { feature: "Phoenix Web Domain", starter: true, basic: true, premium: true, enterprise: true },
            { feature: "Custom Domain", starter: false, basic: false, premium: true, enterprise: true },
            { feature: "Theme Selection", starter: false, basic: true, premium: true, enterprise: true },
            { feature: "White-Label Branding", starter: false, basic: false, premium: false, enterprise: true },
            { feature: "Advanced Customization", starter: false, basic: false, premium: false, enterprise: true },
        ],
    },
    {
        category: "Server & Security",
        rows: [
            { feature: "Anonymous Reporting", starter: true, basic: true, premium: true, enterprise: true },
            { feature: "Data Encryption & Security Compliance", starter: true, basic: true, premium: true, enterprise: true },
            { feature: "Standard Server Location", starter: true, basic: true, premium: true, enterprise: true },
            { feature: "Custom Server Location", starter: false, basic: false, premium: false, enterprise: true },
        ],
    },
    {
        category: "Assistance",
        rows: [
            { feature: "Self-serve knowledge base", starter: true, basic: true, premium: true, enterprise: true },
            { feature: "Ticketing Support", starter: false, basic: true, premium: true, enterprise: true },
            { feature: "Priority Customer Support", starter: false, basic: false, premium: false, enterprise: true },
            { feature: "Dedicated Account Manager", starter: false, basic: false, premium: false, enterprise: true },
        ],
    },
    {
        category: "Add-ons",
        rows: [
            { feature: "Available", starter: false, basic: true, premium: true, enterprise: true },
        ],
    },
    {
        category: "Payment",
        rows: [
            { feature: "Choice of Currency", starter: "CHF, USD, EUR", basic: "CHF, USD, EUR", premium: "CHF, USD, EUR", enterprise: "Various Currencies" },
            { feature: "Payment Terms", starter: "By Credit Card", basic: "By Credit Card", premium: "By Credit Card", enterprise: "Various Billing Options" },
        ],
    },
];

const planKeys = ["starter", "basic", "premium", "enterprise"];

function IconCheck() {
    return (
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" style={{ flexShrink: 0 }}>
            <circle cx="9" cy="9" r="9" fill="rgba(34,197,94,0.12)" />
            <path d="M5.5 9l2.5 2.5 4.5-5" stroke="#16a34a" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" />
        </svg>
    );
}
function IconCross() {
    return (
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" style={{ flexShrink: 0 }}>
            <circle cx="9" cy="9" r="9" fill="#f3f4f6" />
            <path d="M6 12l6-6M12 12L6 6" stroke="#d1d5db" strokeWidth="1.8" strokeLinecap="round" />
        </svg>
    );
}
function IconPartial() {
    return (
        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" style={{ flexShrink: 0 }}>
            <circle cx="9" cy="9" r="9" fill="rgba(232,67,26,0.1)" />
            <path d="M5.5 9h7" stroke="#e8431a" strokeWidth="2" strokeLinecap="round" />
        </svg>
    );
}

function FeatureValue({ value }) {
    if (value === "partial") return <IconPartial />;
    if (value === true) return <IconCheck />;
    if (typeof value === "string") return <span style={{ fontSize: "0.7rem", color: "#555", fontWeight: 600, textAlign: "center", display: "block" }}>{value}</span>;
    return <IconCross />;
}

export default function PricingPage() {
    const [hoveredCol, setHoveredCol] = useState(null);
    const hero = useReveal();
    const cards = useReveal();
    const table = useReveal();

    return (
        <div style={{ background: "#f8f9fb", minHeight: "100vh" }}>

            {/* Hero */}
            <div style={{
                background: "linear-gradient(135deg, #fde8e2 0%, #f3e8f8 60%, #fff3e0 100%)",
                padding: "70px 32px 0",
                textAlign: "center",
                position: "relative",
                overflow: "hidden",
            }}>
                {/* Ornaments */}
                <div style={{ position: "absolute", top: "-20px", right: "-20px", opacity: 0.35, pointerEvents: "none" }}>
                    <Image src="/assets/ORNAMEN 3.png" alt="" width={200} height={120} style={{ objectFit: "contain", transform: "rotate(180deg)" }} />
                </div>
                <div style={{ position: "absolute", top: "40px", left: "40px", opacity: 0.6, pointerEvents: "none" }}>
                    <Image src="/assets/line.png" alt="" width={64} height={32} style={{ objectFit: "contain" }} />
                </div>
                <div style={{ position: "absolute", bottom: "30px", left: "-20px", opacity: 0.25, pointerEvents: "none" }}>
                    <Image src="/assets/ORNAMEN 2.png" alt="" width={160} height={100} style={{ objectFit: "contain", transform: "rotate(180deg)" }} />
                </div>

                <div ref={hero.ref} style={{ position: "relative", zIndex: 1, ...revealStyle(hero.visible, { direction: "up" }) }}>
                    <div style={{
                        display: "inline-flex", alignItems: "center", gap: "8px",
                        background: "rgba(255,255,255,0.7)", border: "1px solid rgba(232,67,26,0.2)",
                        borderRadius: "100px", padding: "5px 14px", marginBottom: "20px",
                    }}>
                        <span style={{ width: "6px", height: "6px", borderRadius: "50%", background: "#e8431a", display: "inline-block" }} />
                        <span style={{ color: "#e8431a", fontSize: "0.72rem", fontWeight: 700, letterSpacing: "0.07em" }}>PRICING PLAN</span>
                    </div>
                    <h1 style={{ fontSize: "clamp(2rem, 4vw, 3rem)", fontWeight: 900, color: "#111", margin: "0 0 12px", lineHeight: 1.2 }}>
                        Choose <span style={{ color: "#e8431a" }}>affordable prices</span>
                    </h1>
                    <p style={{ color: "#666", fontSize: "1rem", maxWidth: "460px", margin: "0 auto 48px", lineHeight: 1.7 }}>
                        Upgrade or cancel anytime. No hidden fees.
                    </p>
                </div>

                {/* Wave */}
                <div style={{ lineHeight: 0, marginTop: "0" }}>
                    <svg viewBox="0 0 1440 60" preserveAspectRatio="none" style={{ width: "100%", height: "60px", display: "block" }}>
                        <path d="M0,30 C360,60 1080,0 1440,30 L1440,60 L0,60 Z" fill="#f8f9fb" />
                    </svg>
                </div>
            </div>

            <div style={{ maxWidth: "1240px", margin: "0 auto", padding: "0 24px 80px" }}>

                {/* Pricing Cards */}
                <div ref={cards.ref} style={{
                    display: "grid",
                    gridTemplateColumns: "repeat(4, 1fr)",
                    gap: "20px",
                    marginTop: "-8px",
                    marginBottom: "80px",
                    alignItems: "start",
                    ...revealStyle(cards.visible, { direction: "up" }),
                }}>
                    {plans.map((plan) => (
                        <div key={plan.key} style={{
                            position: "relative",
                            background: plan.popular ? "linear-gradient(145deg, #fff4f1 0%, #fde8f0 100%)" : "#fff",
                            borderRadius: "20px",
                            padding: "28px 22px",
                            border: plan.popular ? "2px solid #e8431a" : "1px solid #ebebeb",
                            boxShadow: plan.popular ? "0 20px 60px rgba(232,67,26,0.15)" : "0 2px 16px rgba(0,0,0,0.06)",
                            display: "flex", flexDirection: "column",
                            transform: plan.popular ? "translateY(-12px)" : "none",
                        }}>
                            {plan.popular && (
                                <div style={{
                                    position: "absolute", top: "-13px", left: "50%", transform: "translateX(-50%)",
                                    background: "#e8431a", color: "#fff",
                                    fontSize: "0.65rem", fontWeight: 800, padding: "4px 16px",
                                    borderRadius: "100px", letterSpacing: "0.07em", whiteSpace: "nowrap",
                                }}>Most Popular</div>
                            )}

                            {/* Plan name */}
                            <div style={{ fontSize: "0.7rem", fontWeight: 800, color: plan.popular ? "#e8431a" : "#aaa", letterSpacing: "0.08em", textTransform: "uppercase", marginBottom: "4px" }}>
                                {plan.name}
                            </div>
                            {plan.subNote && <div style={{ fontSize: "0.72rem", color: "#888", marginBottom: "12px" }}>{plan.subNote}</div>}

                            {/* Price */}
                            <div style={{ marginBottom: "4px" }}>
                                <span style={{ fontSize: "2.2rem", fontWeight: 900, color: "#111", lineHeight: 1 }}>{plan.price}</span>
                                {plan.period && <span style={{ fontSize: "0.85rem", color: "#999", marginLeft: "3px" }}>{plan.period}</span>}
                            </div>
                            <p style={{ fontSize: "0.72rem", color: "#aaa", margin: "0 0 6px", lineHeight: 1.5 }}>{plan.priceNote}</p>
                            {plan.saving && (
                                <div style={{
                                    display: "inline-flex", alignItems: "center", gap: "6px",
                                    background: "rgba(232,67,26,0.08)", borderRadius: "6px",
                                    padding: "4px 10px", marginBottom: "8px", width: "fit-content",
                                }}>
                                    <span style={{ fontSize: "0.72rem", fontWeight: 700, color: "#e8431a" }}>{plan.saving}</span>
                                    <span style={{ fontSize: "0.72rem", color: "#888" }}>{plan.savingNote}</span>
                                </div>
                            )}

                            <p style={{ fontSize: "0.78rem", color: "#666", lineHeight: 1.6, margin: "10px 0 16px" }}>{plan.desc}</p>
                            {plan.note && <p style={{ fontSize: "0.72rem", color: "#aaa", margin: "0 0 12px", fontStyle: "italic" }}>{plan.note}</p>}

                            <Link href={plan.ctaLink} style={{
                                display: "block", textAlign: "center",
                                borderRadius: "8px", padding: "12px 16px",
                                fontSize: "0.82rem", fontWeight: 700, textDecoration: "none",
                                marginBottom: "20px",
                                background: plan.popular ? "#e8431a" : "transparent",
                                color: plan.popular ? "#fff" : "#e8431a",
                                border: plan.popular ? "none" : "1.5px solid #e8431a",
                                boxShadow: plan.popular ? "0 6px 20px rgba(232,67,26,0.35)" : "none",
                            }}>{plan.cta}</Link>

                            <div style={{ borderTop: "1px solid #f0f0f0", paddingTop: "16px", display: "flex", flexDirection: "column", gap: "12px" }}>
                                {plan.groups.map((g, gi) => (
                                    <div key={gi}>
                                        <div style={{ fontSize: "0.68rem", fontWeight: 700, color: "#aaa", textTransform: "uppercase", letterSpacing: "0.05em", marginBottom: g.items.length ? "6px" : "0" }}>
                                            {g.label}
                                        </div>
                                        {g.items.map((item, ii) => (
                                            <div key={ii} style={{ display: "flex", alignItems: "flex-start", gap: "7px", marginTop: "4px" }}>
                                                <IconCheck />
                                                <span style={{ fontSize: "0.78rem", color: "#555", lineHeight: 1.45 }}>{item}</span>
                                            </div>
                                        ))}
                                    </div>
                                ))}
                            </div>
                        </div>
                    ))}
                </div>

                {/* Compare Table */}
                <div ref={table.ref} style={{ ...revealStyle(table.visible, { direction: "up", delay: 100 }) }}>
                    <h2 style={{ fontSize: "clamp(1.5rem, 3vw, 2rem)", fontWeight: 900, color: "#111", textAlign: "center", marginBottom: "8px" }}>
                        Compare all Features
                    </h2>
                    <p style={{ fontSize: "0.85rem", color: "#aaa", textAlign: "center", marginBottom: "32px" }}>
                        Hover over a plan column to highlight it
                    </p>

                    <div style={{ overflowX: "auto", borderRadius: "16px", border: "1px solid #ebebeb", boxShadow: "0 2px 16px rgba(0,0,0,0.05)" }}>
                        <table style={{ width: "100%", borderCollapse: "collapse", minWidth: "640px" }}>
                            <thead>
                                <tr style={{ background: "#f8f9fb", borderBottom: "2px solid #ebebeb" }}>
                                    <th style={{ textAlign: "left", padding: "16px 20px", fontSize: "0.72rem", fontWeight: 700, color: "#aaa", textTransform: "uppercase", letterSpacing: "0.05em", width: "36%" }}>
                                        Features
                                    </th>
                                    {plans.map((plan) => (
                                        <th key={plan.key}
                                            onMouseEnter={() => setHoveredCol(plan.key)}
                                            onMouseLeave={() => setHoveredCol(null)}
                                            style={{
                                                textAlign: "center", padding: "16px 12px", cursor: "default",
                                                transition: "background 0.15s",
                                                background: plan.popular
                                                    ? "#e8431a"
                                                    : hoveredCol === plan.key ? "#fff5f2" : "transparent",
                                            }}
                                        >
                                            <div style={{ fontSize: "0.82rem", fontWeight: 800, color: plan.popular ? "#fff" : "#333", marginBottom: "2px" }}>{plan.name}</div>
                                            <div style={{ fontSize: "0.7rem", color: plan.popular ? "rgba(255,255,255,0.75)" : "#aaa" }}>
                                                {plan.price}{plan.period || ""}
                                            </div>
                                        </th>
                                    ))}
                                </tr>
                            </thead>
                            <tbody>
                                {compareFeatures.map((group) => (
                                    <React.Fragment key={group.category}>
                                        <tr style={{ background: "#f4f4f4" }}>
                                            <td colSpan={5} style={{ padding: "10px 20px", fontSize: "0.68rem", fontWeight: 800, color: "#666", textTransform: "uppercase", letterSpacing: "0.07em" }}>
                                                {group.category}
                                            </td>
                                        </tr>
                                        {group.rows.map((row, i) => (
                                            <tr key={i} style={{ borderBottom: "1px solid #f4f4f4", background: i % 2 === 0 ? "#fff" : "#fafafa" }}>
                                                <td style={{ padding: "12px 20px", fontSize: "0.82rem", color: "#444" }}>{row.feature}</td>
                                                {planKeys.map((key) => (
                                                    <td key={key}
                                                        onMouseEnter={() => setHoveredCol(key)}
                                                        onMouseLeave={() => setHoveredCol(null)}
                                                        style={{
                                                            textAlign: "center", padding: "12px",
                                                            transition: "background 0.15s",
                                                            background: hoveredCol === key
                                                                ? (key === "premium" ? "rgba(232,67,26,0.05)" : "rgba(0,0,0,0.02)")
                                                                : "transparent",
                                                        }}
                                                    >
                                                        <div style={{ display: "flex", justifyContent: "center" }}>
                                                            <FeatureValue value={row[key]} />
                                                        </div>
                                                    </td>
                                                ))}
                                            </tr>
                                        ))}
                                    </React.Fragment>
                                ))}
                                <tr style={{ background: "#f8f9fb", borderTop: "2px solid #ebebeb" }}>
                                    <td style={{ padding: "16px 20px", fontSize: "0.78rem", color: "#aaa", fontStyle: "italic" }}>
                                        Add-ons available to extend any plan
                                    </td>
                                    {plans.map((plan) => (
                                        <td key={plan.key} style={{ textAlign: "center", padding: "12px 10px" }}>
                                            <Link href={plan.ctaLink} style={{
                                                display: "inline-block", padding: "8px 16px", borderRadius: "8px",
                                                fontSize: "0.75rem", fontWeight: 700, textDecoration: "none",
                                                background: plan.popular ? "#e8431a" : "transparent",
                                                color: plan.popular ? "#fff" : "#e8431a",
                                                border: plan.popular ? "none" : "1.5px solid #e8431a",
                                            }}>{plan.cta}</Link>
                                        </td>
                                    ))}
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p style={{ fontSize: "0.78rem", color: "#bbb", textAlign: "center", marginTop: "20px", lineHeight: 1.7, padding: "0 16px" }}>
                        Whatever package is chosen, you will always have the possibility to add languages, channels and other features at unit prices whenever you require these. Also, it will be possible to upgrade to a higher package whenever required.
                    </p>
                </div>
            </div>
        </div>
    );
}
