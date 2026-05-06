"use client";

import Link from "next/link";
import React, { useState } from "react";

const plans = [
    {
        key: "starter",
        name: "Starter",
        price: "Free",
        period: null,
        priceNote: "Begin for Free – Upgrade Anytime",
        saving: null,
        cta: "Get Started Free",
        ctaLink: "/get-started?plan=free",
        popular: false,
        color: "#6b7280",
        features: [
            "1 Dedicated website",
            "Webform only",
            "Choice between 3 questionnaires",
            "1 account as Manager",
            "1 language only",
            "Default theme (logo can be added)",
            "Choice of server",
            "Choice of Phoenix web domains",
            "Self-serve knowledge base",
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
        color: "#3b82f6",
        features: [
            "1 Dedicated website",
            "1 Email Address",
            "1 Phone Number",
            "1 Instant Messaging",
            "1 Postal Address",
            "Choice between 3 questionnaires",
            "1 account as Manager",
            "2 languages",
            "Access to 3 themes only",
            "Choice of server",
            "Choice of Phoenix web domains",
            "Self-serve knowledge base",
            "Ticketing (3-day response time)",
            "Add-ons available",
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
        color: "#e8431a",
        features: [
            "1 Dedicated website",
            "1 Email Address",
            "1 Phone Number",
            "1 Instant Messaging",
            "1 Postal Address",
            "1 Online Chat room",
            "Customisable questionnaire",
            "1 account as Manager",
            "1 account as Operator",
            "1 account as Agent",
            "2 languages",
            "Access to theme library",
            "Choice of server",
            "Choice of Phoenix web domains",
            "Custom domain theme",
            "Ticketing (3-day response time)",
            "Add-ons available",
        ],
    },
    {
        key: "enterprise",
        name: "Enterprise",
        price: "Custom",
        period: null,
        priceNote: "Tailored for your organisation",
        saving: null,
        cta: "Contact Us",
        ctaLink: "/contact",
        popular: false,
        color: "#2d2d2d",
        features: [
            "Customizable Reporting Channels",
            "Advanced User Accounts",
            "Many Language Options",
            "Fully Customisable Themes",
            "High-End Server & Security",
            "Premium Assistance & Support",
            "Flexible Payment Terms",
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
            { feature: "White-label branding", starter: false, basic: false, premium: false, enterprise: true },
            { feature: "Advanced Customisation", starter: false, basic: false, premium: false, enterprise: true },
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
];

const planKeys = ["starter", "basic", "premium", "enterprise"];

function IconCheck() {
    return (
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" style={{flexShrink:0}}>
            <circle cx="8" cy="8" r="8" fill="#22c55e" fillOpacity="0.12"/>
            <path d="M5 8l2 2 4-4" stroke="#16a34a" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round"/>
        </svg>
    );
}
function IconCross() {
    return (
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" style={{flexShrink:0}}>
            <circle cx="8" cy="8" r="8" fill="#f3f4f6"/>
            <path d="M5.5 10.5l5-5M10.5 10.5l-5-5" stroke="#d1d5db" strokeWidth="1.8" strokeLinecap="round"/>
        </svg>
    );
}
function IconPartial() {
    return (
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" style={{flexShrink:0}}>
            <circle cx="8" cy="8" r="8" fill="#fb923c" fillOpacity="0.12"/>
            <path d="M5 8h6" stroke="#ea580c" strokeWidth="2" strokeLinecap="round"/>
        </svg>
    );
}

function FeatureValue({ value }) {
    if (value === "partial") return <IconPartial />;
    if (value === true) return <IconCheck />;
    return <IconCross />;
}

export default function PricingPage() {
    const [hoveredCol, setHoveredCol] = useState(null);

    return (
        <div style={{background:"#f9fafb", minHeight:"100vh"}}>

            {/* Hero */}
            <div style={{background:"linear-gradient(135deg,#fde8e2 0%,#f3e8f8 100%)", padding:"60px 24px 48px", textAlign:"center"}}>
                <p style={{fontSize:"12px", fontWeight:700, color:"#e8431a", textTransform:"uppercase", letterSpacing:"2px", marginBottom:"12px"}}>Pricing</p>
                <h1 style={{fontSize:"clamp(28px,5vw,42px)", fontWeight:800, color:"#1a1a1a", marginBottom:"14px", lineHeight:1.2}}>
                    Simple, Transparent Pricing
                </h1>
                <p style={{fontSize:"15px", color:"#666", maxWidth:"480px", margin:"0 auto"}}>
                    Choose the plan that fits your organisation. Upgrade or cancel anytime.
                </p>
            </div>

            <div style={{maxWidth:"1200px", margin:"0 auto", padding:"0 24px 80px"}}>

                {/* Pricing Cards */}
                <div style={{display:"grid", gridTemplateColumns:"repeat(auto-fit,minmax(230px,1fr))", gap:"20px", marginTop:"-32px", marginBottom:"80px"}}>
                    {plans.map((plan) => (
                        <div key={plan.name} style={{
                            position:"relative",
                            background: plan.popular ? "linear-gradient(145deg, #fff4f1 0%, #fde8f0 100%)" : "white",
                            borderRadius:"16px",
                            padding:"28px 24px",
                            border: plan.popular ? "2px solid #e8431a" : "1px solid #e5e7eb",
                            boxShadow: plan.popular ? "0 16px 48px rgba(232,67,26,0.15)" : "0 2px 12px rgba(0,0,0,0.06)",
                            display:"flex",
                            flexDirection:"column",
                            transform: plan.popular ? "translateY(-8px)" : "none",
                        }}>
                            {/* Popular badge */}
                            {plan.popular && (
                                <div style={{position:"absolute", top:"-12px", left:"50%", transform:"translateX(-50%)", background:"#e8431a", color:"white", fontSize:"10px", fontWeight:800, padding:"4px 14px", borderRadius:"99px", textTransform:"uppercase", letterSpacing:"1px", whiteSpace:"nowrap"}}>
                                    Most Popular
                                </div>
                            )}

                            {/* Plan name */}
                            <div style={{display:"flex", alignItems:"center", gap:"8px", marginBottom:"20px"}}>
                                <div style={{width:"8px", height:"8px", borderRadius:"99px", background: plan.popular ? "rgba(0,0,0,0.25)" : plan.color, flexShrink:0}}></div>
                                <span style={{fontSize:"13px", fontWeight:700, color: plan.popular ? "#1a1a1a" : "#333", textTransform:"uppercase", letterSpacing:"1px"}}>{plan.name}</span>
                            </div>

                            {/* Price */}
                            <div style={{marginBottom:"6px"}}>
                                <span style={{fontSize:"36px", fontWeight:800, color: plan.popular ? "#1a1a1a" : "#1a1a1a", lineHeight:1}}>{plan.price}</span>
                                {plan.period && <span style={{fontSize:"13px", color: plan.popular ? "#444" : "#999", marginLeft:"3px"}}>{plan.period}</span>}
                            </div>
                            <p style={{fontSize:"11px", color: plan.popular ? "#555" : "#aaa", marginBottom:"6px"}}>{plan.priceNote}</p>
                            {plan.saving && (
                                <div style={{display:"inline-flex", alignItems:"center", gap:"6px", background: "rgba(232,67,26,0.08)", borderRadius:"6px", padding:"4px 8px", marginBottom:"8px", width:"fit-content"}}>
                                    <span style={{fontSize:"11px", fontWeight:700, color: plan.popular ? "#1a1a1a" : "#e8431a"}}>{plan.saving}</span>
                                    <span style={{fontSize:"11px", color: plan.popular ? "#333" : "#888"}}>{plan.savingNote}</span>
                                </div>
                            )}

                            {/* CTA */}
                            <Link href={plan.ctaLink} style={{
                                display:"block", textAlign:"center", borderRadius:"10px", padding:"11px 16px",
                                fontSize:"13px", fontWeight:700, textDecoration:"none", marginTop:"16px", marginBottom:"20px",
                                background: plan.popular ? "#1a1a1a" : "transparent",
                                color: plan.popular ? "white" : "#e8431a",
                                border: plan.popular ? "none" : "2px solid #e8431a",
                                transition:"all 0.15s",
                            }}>
                                {plan.cta}
                            </Link>

                            <div style={{borderTop: plan.popular ? "1px solid rgba(0,0,0,0.1)" : "1px solid #f0f0f0", paddingTop:"18px"}}>
                                <ul style={{listStyle:"none", padding:0, margin:0, display:"flex", flexDirection:"column", gap:"9px"}}>
                                    {plan.features.map((f, i) => (
                                        <li key={i} style={{display:"flex", alignItems:"flex-start", gap:"8px", fontSize:"12px", color: plan.popular ? "#333" : "#555", lineHeight:1.4}}>
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" style={{flexShrink:0, marginTop:"1px"}}>
                                                <circle cx="7" cy="7" r="7" fill="rgba(34,197,94,0.15)"/>
                                                <path d="M4.5 7l2 2 3-3" stroke="#16a34a" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round"/>
                                            </svg>
                                            {f}
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        </div>
                    ))}
                </div>

                {/* Compare Table */}
                <div style={{marginBottom:"16px"}}>
                    <h2 style={{fontSize:"24px", fontWeight:800, color:"#1a1a1a", textAlign:"center", marginBottom:"8px"}}>Compare all Features</h2>
                    <p style={{fontSize:"13px", color:"#888", textAlign:"center", marginBottom:"32px"}}>Hover over a plan column to highlight it</p>

                    <p style={{fontSize:"11px", color:"#bbb", textAlign:"center", marginBottom:"12px", display:"block"}} className="md:hidden">← Scroll to see all plans →</p>

                    <div style={{overflowX:"auto", borderRadius:"16px", border:"1px solid #e5e7eb", boxShadow:"0 2px 12px rgba(0,0,0,0.04)"}}>
                        <table style={{width:"100%", borderCollapse:"collapse", minWidth:"620px"}}>
                            <thead>
                                <tr style={{background:"#f9fafb", borderBottom:"2px solid #e5e7eb"}}>
                                    <th style={{textAlign:"left", padding:"16px 20px", fontSize:"12px", fontWeight:700, color:"#888", textTransform:"uppercase", letterSpacing:"0.5px", width:"38%"}}>
                                        Features
                                    </th>
                                    {plans.map((plan) => (
                                        <th key={plan.key}
                                            onMouseEnter={() => setHoveredCol(plan.key)}
                                            onMouseLeave={() => setHoveredCol(null)}
                                            style={{
                                                textAlign:"center", padding:"16px 12px", cursor:"default",
                                                transition:"background 0.15s",
                                                background: plan.popular
                                                    ? "linear-gradient(145deg, #e8431a, #e8688a)"
                                                    : hoveredCol === plan.key ? "#fff7f5" : "transparent",
                                            }}
                                        >
                                            <div style={{fontSize:"13px", fontWeight:700, color: plan.popular ? "#fff" : "#333", marginBottom:"2px"}}>{plan.name}</div>
                                            <div style={{fontSize:"11px", color: plan.popular ? "rgba(255,255,255,0.8)" : "#999", fontWeight:500}}>
                                                {plan.price}{plan.period || ""}
                                            </div>
                                        </th>
                                    ))}
                                </tr>
                            </thead>
                            <tbody>
                                {compareFeatures.map((group) => (
                                    <React.Fragment key={group.category}>
                                        <tr style={{background:"#f3f4f6"}}>
                                            <td colSpan={5} style={{padding:"10px 20px", fontSize:"11px", fontWeight:800, color:"#555", textTransform:"uppercase", letterSpacing:"1px"}}>
                                                {group.category}
                                            </td>
                                        </tr>
                                        {group.rows.map((row, i) => (
                                            <tr key={i} style={{borderBottom:"1px solid #f0f0f0", background: i % 2 === 0 ? "white" : "#fafafa"}}>
                                                <td style={{padding:"12px 20px", fontSize:"13px", color:"#444"}}>{row.feature}</td>
                                                {planKeys.map((key) => (
                                                    <td key={key}
                                                        onMouseEnter={() => setHoveredCol(key)}
                                                        onMouseLeave={() => setHoveredCol(null)}
                                                        style={{
                                                            textAlign:"center", padding:"12px",
                                                            transition:"background 0.15s",
                                                            background: hoveredCol === key
                                                                ? (key === "premium" ? "rgba(232,67,26,0.06)" : "rgba(0,0,0,0.03)")
                                                                : "transparent",
                                                        }}
                                                    >
                                                        <div style={{display:"flex", justifyContent:"center"}}>
                                                            <FeatureValue value={row[key]} />
                                                        </div>
                                                    </td>
                                                ))}
                                            </tr>
                                        ))}
                                    </React.Fragment>
                                ))}
                                {/* CTA row at bottom of table */}
                                <tr style={{background:"#f9fafb", borderTop:"2px solid #e5e7eb"}}>
                                    <td style={{padding:"16px 20px", fontSize:"12px", color:"#888", fontStyle:"italic"}}>
                                        Add-ons available to extend any plan
                                    </td>
                                    {plans.map((plan) => (
                                        <td key={plan.key} style={{textAlign:"center", padding:"12px 10px"}}>
                                            <Link href={plan.ctaLink} style={{
                                                display:"inline-block", padding:"8px 16px", borderRadius:"8px", fontSize:"12px", fontWeight:700, textDecoration:"none",
                                                background: plan.popular ? "#e8431a" : "transparent",
                                                color: plan.popular ? "white" : "#e8431a",
                                                border: plan.popular ? "none" : "1.5px solid #e8431a",
                                                whiteSpace:"nowrap",
                                            }}>
                                                {plan.cta}
                                            </Link>
                                        </td>
                                    ))}
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p style={{fontSize:"12px", color:"#aaa", textAlign:"center", marginTop:"20px", padding:"0 16px"}}>
                        Whatever package is chosen, you can always add languages, channels and other features at unit prices.
                    </p>
                </div>

            </div>
        </div>
    );
}
