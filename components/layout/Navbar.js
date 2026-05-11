"use client";

import { useState, useRef } from "react";
import Link from "next/link";
import Image from "next/image";
import { usePathname } from "next/navigation";

const FEATURES_MENU = [
    { label: "Multi-organisation",    sub: "Multiple Organisations, One Compliance Solution",        href: "/features#multi-organisation", icon: "🏢" },
    { label: "Multi languages",       sub: "Across Borders, Beyond Barriers: 50+ Languages",         href: "/features#multi-languages",    icon: "🌐" },
    { label: "Multiple channels",     sub: "Multiple channels for reporting: Speak Up",               href: "/features#multiple-channels",  icon: "📡" },
    { label: "Multiple templates",    sub: "Express Your Ethics in Your Aesthetic",                   href: "/features#multiple-templates", icon: "🎨" },
    { label: "Flexibility",           sub: "Your Compliance, Your Way",                               href: "/features#flexibility",        icon: "⚙️" },
    { label: "Affordability",         sub: "Your Business, Your Budget, Your Choice",                 href: "/features#affordability",      icon: "💰" },
    { label: "Ease and speed to set up", sub: "Power Up Your Compliance in Two Hours",               href: "/features#ease-speed",         icon: "⚡" },
    { label: "Case management",       sub: "Comprehensive case management tools",                     href: "/features#case-management",    icon: "📋" },
    { label: "Security",              sub: "Swiss Security, Global Trust",                            href: "/features#security",           icon: "🔒" },
    { label: "Confidentiality",       sub: "Ensuring Privacy, Empowering Whistleblowers",             href: "/features#confidentiality",    icon: "🔏" },
    { label: "FADP / GDPR compliant", sub: "Anonymous, Secure, and Fully GDPR Compliant",            href: "/features#fadp-gdpr",          icon: "✅" },
    { label: "See all features →",    sub: "Phoenix Whistleblowing Features",                         href: "/features", highlight: true },
];

const RESOURCES_MENU = [
    "Overview",
    "Features and Functionality",
    "Setup and Customization",
    "Use Cases",
    "Compliance and Standards",
    "Pricing and Support",
    "Software Updates and Maintenance",
    "Security",
    "Training and Onboarding",
    "Reporting and Statistics",
    "Future Developments",
];

export default function Navbar() {
    const [mobileOpen, setMobileOpen] = useState(false);
    const [megaOpen, setMegaOpen] = useState(null);
    const closeTimer = useRef(null);
    const pathname = usePathname();

    const openMega = (key) => {
        clearTimeout(closeTimer.current);
        setMegaOpen(key);
    };
    const closeMega = () => {
        closeTimer.current = setTimeout(() => setMegaOpen(null), 120);
    };

    const navItem = (href, label, megaKey) => {
        const active = pathname === href || pathname.startsWith(href + "/");
        const hasMega = !!megaKey;
        const isOpen = megaOpen === megaKey;
        return (
            <div
                key={href}
                style={{ position: "relative" }}
                onMouseEnter={() => hasMega ? openMega(megaKey) : setMegaOpen(null)}
                onMouseLeave={() => hasMega ? closeMega() : null}
            >
                <Link
                    href={href}
                    style={{
                        fontSize: "13.5px",
                        fontWeight: active ? 600 : 400,
                        color: active ? "#e8431a" : "#444",
                        textDecoration: "none",
                        whiteSpace: "nowrap",
                        display: "flex", alignItems: "center", gap: "4px",
                        transition: "color 0.15s",
                        padding: "4px 0",
                    }}
                    onMouseEnter={e => { if (!active) e.currentTarget.style.color = "#e8431a"; }}
                    onMouseLeave={e => { if (!active) e.currentTarget.style.color = "#444"; }}
                >
                    {label}
                    {hasMega && (
                        <svg width="11" height="11" viewBox="0 0 11 11" fill="none" style={{ opacity: 0.45, transition: "transform 0.15s", transform: isOpen ? "rotate(180deg)" : "none" }}>
                            <path d="M2 4L5.5 7.5L9 4" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" />
                        </svg>
                    )}
                </Link>

                {/* Compact dropdown */}
                {hasMega && isOpen && (
                    <div
                        onMouseEnter={() => openMega(megaKey)}
                        onMouseLeave={closeMega}
                        style={{
                            position: "absolute", top: "calc(100% + 10px)", left: "50%",
                            transform: "translateX(-50%)",
                            background: "#fff",
                            borderRadius: "12px",
                            border: "1px solid #f0f0f0",
                            boxShadow: "0 8px 32px rgba(0,0,0,0.12)",
                            padding: "8px",
                            minWidth: megaKey === "features" ? "560px" : "420px",
                            zIndex: 100,
                            animation: "dropFadeIn 0.13s ease-out",
                        }}
                    >
                        <style>{`@keyframes dropFadeIn { from { opacity:0; transform:translateX(-50%) translateY(-4px); } to { opacity:1; transform:translateX(-50%) translateY(0); } }`}</style>

                        {/* Arrow pointer */}
                        <div style={{
                            position: "absolute", top: "-5px", left: "50%", transform: "translateX(-50%)",
                            width: "10px", height: "10px", background: "#fff",
                            border: "1px solid #f0f0f0", borderBottom: "none", borderRight: "none",
                            rotate: "45deg",
                        }} />

                        <div style={{ fontSize: "0.6rem", fontWeight: 800, color: "#e8431a", letterSpacing: "0.1em", textTransform: "uppercase", padding: "4px 8px 8px" }}>
                            {megaKey === "features" ? "Our Features" : "Questions & Answers"}
                        </div>

                        {megaKey === "features" && (
                            <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: "2px" }}>
                                {FEATURES_MENU.map((item) => (
                                    <Link
                                        key={item.href}
                                        href={item.href}
                                        onClick={() => setMegaOpen(null)}
                                        style={{
                                            display: "flex", alignItems: "flex-start", gap: "8px",
                                            padding: "8px 10px", borderRadius: "8px",
                                            textDecoration: "none", transition: "background 0.1s",
                                        }}
                                        onMouseEnter={e => e.currentTarget.style.background = "#fff5f2"}
                                        onMouseLeave={e => e.currentTarget.style.background = "transparent"}
                                    >
                                        {!item.highlight && <span style={{ fontSize: "0.9rem", marginTop: "1px", flexShrink: 0 }}>{item.icon}</span>}
                                        <div>
                                            <div style={{ fontSize: "0.8rem", fontWeight: item.highlight ? 700 : 600, color: item.highlight ? "#e8431a" : "#222" }}>{item.label}</div>
                                            <div style={{ fontSize: "0.7rem", color: "#999", marginTop: "1px", lineHeight: 1.4 }}>{item.sub}</div>
                                        </div>
                                    </Link>
                                ))}
                            </div>
                        )}

                        {megaKey === "resources" && (
                            <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: "2px" }}>
                                {RESOURCES_MENU.map((cat) => (
                                    <Link
                                        key={cat}
                                        href={`/resources?cat=${encodeURIComponent(cat)}`}
                                        onClick={() => setMegaOpen(null)}
                                        style={{
                                            display: "block", padding: "8px 10px", borderRadius: "8px",
                                            fontSize: "0.8rem", fontWeight: 400, color: "#333",
                                            textDecoration: "none", transition: "background 0.1s",
                                        }}
                                        onMouseEnter={e => e.currentTarget.style.background = "#fff5f2"}
                                        onMouseLeave={e => e.currentTarget.style.background = "transparent"}
                                    >
                                        {cat}
                                    </Link>
                                ))}
                            </div>
                        )}
                    </div>
                )}
            </div>
        );
    };

    return (
        <>
        <style>{`
            .nav-desktop { display: flex; }
            .nav-hamburger { display: none; }
            @media (max-width: 767px) {
                .nav-desktop { display: none; }
                .nav-hamburger { display: flex; }
            }
        `}</style>
        <nav style={{ position: "fixed", top: 0, left: 0, right: 0, zIndex: 50, background: "white", borderBottom: "1px solid #f0f0f0", boxShadow: "0 1px 3px rgba(0,0,0,0.06)" }}>
            <div style={{ maxWidth: "1200px", margin: "0 auto", padding: "0 32px", height: "60px", display: "flex", alignItems: "center", justifyContent: "space-between" }}>

                {/* Logo */}
                <Link href="/" style={{ display: "flex", alignItems: "center", textDecoration: "none", flexShrink: 0 }}>
                    <Image src="/logo.png" alt="Phoenix Whistleblowing" width={130} height={40} priority style={{ objectFit: "contain" }} />
                </Link>

                {/* Desktop nav */}
                <div className="nav-desktop" style={{ alignItems: "center", gap: "28px" }}>
                    {navItem("/", "Home")}
                    {navItem("/features", "Features", "features")}
                    {navItem("/pricing", "Pricing")}
                    {navItem("/consultant-solutions", "Consultant Solutions")}
                    {navItem("/resources", "Resources", "resources")}
                    {navItem("/contact", "Contact")}
                </div>

                {/* Mobile hamburger */}
                <button
                    className="nav-hamburger"
                    onClick={() => setMobileOpen(!mobileOpen)}
                    style={{ flexDirection: "column", gap: "5px", padding: "8px", background: "none", border: "none", cursor: "pointer" }}
                >
                    <span style={{ width: "22px", height: "2px", background: "#333", display: "block", transition: "transform 0.2s, opacity 0.2s", transform: mobileOpen ? "rotate(45deg) translate(5px, 5px)" : "none" }} />
                    <span style={{ width: "22px", height: "2px", background: "#333", display: "block", opacity: mobileOpen ? 0 : 1, transition: "opacity 0.2s" }} />
                    <span style={{ width: "22px", height: "2px", background: "#333", display: "block", transition: "transform 0.2s", transform: mobileOpen ? "rotate(-45deg) translate(5px, -5px)" : "none" }} />
                </button>
            </div>


            {/* Mobile menu */}
            {mobileOpen && (
                <div style={{ background: "white", borderTop: "1px solid #f0f0f0", padding: "12px 24px 20px" }}>
                    {[
                        { href: "/", label: "Home" },
                        { href: "/features", label: "Features" },
                        { href: "/pricing", label: "Pricing" },
                        { href: "/consultant-solutions", label: "Consultant Solutions" },
                        { href: "/resources", label: "Resources" },
                        { href: "/contact", label: "Contact" },
                    ].map(({ href, label }) => (
                        <Link
                            key={href}
                            href={href}
                            onClick={() => setMobileOpen(false)}
                            style={{ display: "block", padding: "10px 0", fontSize: "14px", color: pathname === href ? "#e8431a" : "#444", fontWeight: pathname === href ? 600 : 400, textDecoration: "none", borderBottom: "1px solid #f5f5f5" }}
                        >
                            {label}
                        </Link>
                    ))}
                </div>
            )}
        </nav>
        </>
    );
}
