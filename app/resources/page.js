"use client";

import Image from "next/image";
import Link from "next/link";
import { useState, useEffect, Suspense } from "react";
import { useSearchParams } from "next/navigation";
import { useReveal, revealStyle } from "@/hooks/useReveal";

const CATEGORIES = [
    { label: "Overview",                     slug: "overview",                       icon: "🏠", count: 5 },
    { label: "Features and Functionality",   slug: "features-and-functionality",     icon: "⚙️", count: 8 },
    { label: "Setup and Customization",      slug: "setup-and-customization",        icon: "🎨", count: 6 },
    { label: "Use Cases",                    slug: "use-cases",                      icon: "💼", count: 4 },
    { label: "Compliance and Standards",     slug: "compliance-and-standards",       icon: "📋", count: 7 },
    { label: "Pricing and Support",          slug: "pricing-and-support",            icon: "💰", count: 5 },
    { label: "Software Updates",             slug: "software-updates-and-maintenance", icon: "🔄", count: 3 },
    { label: "Security",                     slug: "security",                       icon: "🔒", count: 6 },
    { label: "Training and Onboarding",      slug: "training-and-onboarding",        icon: "🎓", count: 4 },
    { label: "Reporting and Statistics",     slug: "reporting-and-statistics",       icon: "📊", count: 4 },
    { label: "Future Developments",          slug: "future-developments",            icon: "🚀", count: 2 },
];

const QUICK_FILTERS = ["Getting Started", "Security", "Compliance", "Reporting", "Pricing"];

const FAQS = [
    // Overview
    { category: "overview", q: "What is Phoenix?", a: "Phoenix is a secure, modern whistleblowing platform designed to help organizations receive and manage confidential reports safely. It provides a trusted channel for employees, partners, and stakeholders to raise concerns without fear of retaliation." },
    { category: "overview", q: "Who is Phoenix designed for?", a: "Phoenix is designed for organisations of all sizes — from small businesses to large multinationals and compliance consultants. Whether you are managing one entity or multiple organisations, Phoenix adapts to your needs." },
    { category: "overview", q: "How does Phoenix protect reporter identity?", a: "Phoenix uses end-to-end encryption, anonymisation technology, and Swiss-grade data protection. Reporters can submit reports fully anonymously, and the system never stores identifying metadata without consent." },
    { category: "overview", q: "Is Phoenix compliant with international standards?", a: "Yes. Phoenix is fully compliant with GDPR, the Swiss Federal Act on Data Protection (FADP), and EU Whistleblowing Directive 2019/1937. It meets the legal requirements for whistleblowing systems across Europe." },
    { category: "overview", q: "Why did we name it Phoenix?", a: "Just as the mythical Phoenix rises from the ashes, our platform aims to bring renewal, transparency, and positive change within organisations. It represents the opportunity for individuals to come forward, report wrongdoing, and initiate a process of regeneration and improvement." },
    // Features and Functionality
    { category: "features-and-functionality", q: "How many languages does Phoenix support?", a: "Phoenix Whistleblowing Software is adapted to over 50+ languages, allowing organisations to create a multilingual whistleblowing platform to cater to stakeholders worldwide." },
    { category: "features-and-functionality", q: "What reporting channels are available?", a: "Phoenix supports webform, email address, phone number display, instant messaging, postal address, and live online chat room — giving reporters maximum flexibility to choose how they speak up." },
    { category: "features-and-functionality", q: "Can I manage multiple organisations?", a: "Yes. With our multi-organisation feature, you can create and manage distinct whistleblowing systems for each entity. One platform, unlimited customised systems." },
    { category: "features-and-functionality", q: "What is the case management dashboard?", a: "The case management dashboard allows compliance officers to track, assign, and resolve reports in a structured, auditable workflow. It supports multi-user roles including Manager, Operator, and Agent." },
    // Setup and Customization
    { category: "setup-and-customization", q: "Can I customise my whistleblowing system?", a: "Absolutely! With Phoenix Whistleblowing Software, you have the flexibility to customise your system to fit your organisation's needs. Choose from different packages, add or upgrade features, select domain names, templates, colours, and even incorporate your own branding elements." },
    { category: "setup-and-customization", q: "How quickly can I get started?", a: "You can have your whistleblowing system live in as little as two hours. The setup process is intuitive and requires no technical expertise." },
    { category: "setup-and-customization", q: "Can I use a custom domain name?", a: "Yes, on the Premium plan and above you can use your own custom domain name, giving your reporting system a fully branded, professional appearance." },
    // Security
    { category: "security", q: "How secure is Phoenix Whistleblowing Software?", a: "Security is our top priority. Phoenix ensures Swiss-grade security with servers located in Switzerland, strict data protection regulations, limited third-party interactions, and robust encryption measures. Your whistleblowing data is safeguarded to maintain the utmost confidentiality." },
    { category: "security", q: "Where is data stored?", a: "All data is stored on servers located in Switzerland, one of the world's strictest data protection jurisdictions. You can also choose your preferred server location depending on your plan." },
    { category: "security", q: "Is anonymous reporting truly anonymous?", a: "Yes. Phoenix is engineered so that even system administrators cannot identify anonymous reporters. No IP addresses or device fingerprints are stored or logged for anonymous submissions." },
    // Compliance and Standards
    { category: "compliance-and-standards", q: "Is Phoenix GDPR compliant?", a: "Yes. Phoenix is fully GDPR compliant and designed to meet all EU data protection requirements, including lawful basis for processing, data minimisation, and the right to erasure." },
    { category: "compliance-and-standards", q: "Does Phoenix comply with the EU Whistleblowing Directive?", a: "Yes. Phoenix is built specifically to help organisations comply with EU Directive 2019/1937 on the protection of persons who report breaches of Union law (the EU Whistleblowing Directive)." },
    // Pricing and Support
    { category: "pricing-and-support", q: "What plans are available?", a: "Phoenix offers four plans: Starter (Free), Basic ($65/month), Premium ($110/month), and Enterprise (custom pricing). Each plan is designed for a different scale of organisation." },
    { category: "pricing-and-support", q: "Can I upgrade or cancel anytime?", a: "Yes. You can upgrade to a higher plan or cancel at any time. There are no long-term contracts or hidden cancellation fees." },
    { category: "pricing-and-support", q: "What support is included?", a: "All plans include self-serve knowledge base access. Basic and Premium include ticketing support (3-day response). Enterprise includes priority support and a dedicated account manager." },
];

function ChevronIcon({ open }) {
    return (
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
            style={{ flexShrink: 0, transition: "transform 0.2s", transform: open ? "rotate(180deg)" : "none" }}>
            <path d="M5 7.5L10 12.5L15 7.5" stroke={open ? "#e8431a" : "#999"} strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" />
        </svg>
    );
}

function ResourcesContent() {
    const searchParams = useSearchParams();
    const [activeSlug, setActiveSlug] = useState("overview");
    const [openFaq, setOpenFaq] = useState(0);
    const [helpful, setHelpful] = useState({});
    const [search, setSearch] = useState("");

    useEffect(() => {
        const cat = searchParams.get("cat");
        if (cat) {
            const found = CATEGORIES.find(c => c.label === cat || c.slug === cat);
            if (found) setActiveSlug(found.slug);
        }
    }, [searchParams]);

    const activeCategory = CATEGORIES.find(c => c.slug === activeSlug);

    const filtered = FAQS.filter(f => {
        const matchCat = f.category === activeSlug;
        const matchSearch = search.trim() === "" || f.q.toLowerCase().includes(search.toLowerCase()) || f.a.toLowerCase().includes(search.toLowerCase());
        return matchCat && matchSearch;
    });

    const allSearch = search.trim() !== "" ? FAQS.filter(f =>
        f.q.toLowerCase().includes(search.toLowerCase()) || f.a.toLowerCase().includes(search.toLowerCase())
    ) : null;

    const displayFaqs = allSearch ?? filtered;

    const hero = useReveal();

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
                <div style={{ position: "absolute", top: "-20px", right: "-20px", opacity: 0.35, pointerEvents: "none" }}>
                    <Image src="/assets/ORNAMEN 3.png" alt="" width={200} height={120} style={{ objectFit: "contain", transform: "rotate(180deg)" }} />
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
                        <span style={{ color: "#e8431a", fontSize: "0.72rem", fontWeight: 700, letterSpacing: "0.07em" }}>RESOURCES</span>
                    </div>
                    <h1 style={{ fontSize: "clamp(2rem, 4vw, 3rem)", fontWeight: 900, color: "#111", margin: "0 0 12px", lineHeight: 1.2 }}>
                        Frequently Asked <span style={{ color: "#e8431a" }}>Questions</span>
                    </h1>
                    <p style={{ color: "#666", fontSize: "1rem", maxWidth: "460px", margin: "0 auto 48px", lineHeight: 1.7 }}>
                        Everything you need to know about Phoenix Whistleblowing Software.
                    </p>
                </div>
                <div style={{ lineHeight: 0 }}>
                    <svg viewBox="0 0 1440 60" preserveAspectRatio="none" style={{ width: "100%", height: "60px", display: "block" }}>
                        <path d="M0,30 C360,60 1080,0 1440,30 L1440,60 L0,60 Z" fill="#fff" />
                    </svg>
                </div>
            </div>

            {/* Search header */}
            <div style={{
                background: "#fff",
                borderBottom: "1px solid #f0f0f0",
                padding: "48px 32px 32px",
                textAlign: "center",
            }}>
                <p style={{ fontSize: "0.75rem", fontWeight: 600, color: "#aaa", letterSpacing: "0.08em", textTransform: "uppercase", marginBottom: "20px" }}>
                    What are you looking for?
                </p>
                <div style={{
                    maxWidth: "560px", margin: "0 auto 20px",
                    display: "flex", alignItems: "center",
                    background: "#f8f9fb", borderRadius: "100px",
                    border: "1.5px solid #e8e8e8", overflow: "hidden",
                    boxShadow: "0 2px 12px rgba(0,0,0,0.06)",
                }}>
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" style={{ marginLeft: "18px", flexShrink: 0 }}>
                        <circle cx="7.5" cy="7.5" r="5.5" stroke="#bbb" strokeWidth="1.6" />
                        <path d="M11.5 11.5L15 15" stroke="#bbb" strokeWidth="1.6" strokeLinecap="round" />
                    </svg>
                    <input
                        type="text"
                        value={search}
                        onChange={e => { setSearch(e.target.value); setOpenFaq(null); }}
                        placeholder="Search for help, features, or guides..."
                        style={{
                            flex: 1, padding: "14px 12px", background: "none", border: "none",
                            fontSize: "0.9rem", color: "#333", outline: "none",
                        }}
                    />
                    <button style={{
                        width: "44px", height: "44px", margin: "4px",
                        background: "#e8431a", border: "none", borderRadius: "100px",
                        cursor: "pointer", display: "flex", alignItems: "center", justifyContent: "center",
                        flexShrink: 0,
                    }}>
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <circle cx="6.5" cy="6.5" r="4.5" stroke="#fff" strokeWidth="1.6" />
                            <path d="M10 10L13.5 13.5" stroke="#fff" strokeWidth="1.6" strokeLinecap="round" />
                        </svg>
                    </button>
                </div>

                {/* Quick filters */}
                <div style={{ display: "flex", justifyContent: "center", gap: "8px", flexWrap: "wrap" }}>
                    {QUICK_FILTERS.map(f => (
                        <button
                            key={f}
                            onClick={() => {
                                setSearch("");
                                const match = CATEGORIES.find(c => c.label.toLowerCase().includes(f.toLowerCase()));
                                if (match) { setActiveSlug(match.slug); setOpenFaq(0); }
                            }}
                            style={{
                                padding: "5px 16px", borderRadius: "100px",
                                border: "1.5px solid #e8e8e8", background: "#fff",
                                fontSize: "0.78rem", color: "#555", cursor: "pointer",
                                transition: "border-color 0.15s, color 0.15s",
                            }}
                            onMouseEnter={e => { e.currentTarget.style.borderColor = "#e8431a"; e.currentTarget.style.color = "#e8431a"; }}
                            onMouseLeave={e => { e.currentTarget.style.borderColor = "#e8e8e8"; e.currentTarget.style.color = "#555"; }}
                        >
                            {f}
                        </button>
                    ))}
                </div>
            </div>

            <div style={{ maxWidth: "1100px", margin: "0 auto", padding: "32px 24px 80px" }}>

                {/* Breadcrumb */}
                <div style={{ display: "flex", alignItems: "center", gap: "6px", marginBottom: "28px", fontSize: "0.78rem", color: "#aaa" }}>
                    <Link href="/" style={{ color: "#aaa", textDecoration: "none" }}
                        onMouseEnter={e => e.currentTarget.style.color = "#e8431a"}
                        onMouseLeave={e => e.currentTarget.style.color = "#aaa"}>Home</Link>
                    <span>›</span>
                    <Link href="/resources" style={{ color: "#e8431a", textDecoration: "none", fontWeight: 600 }}>Resources</Link>
                    {!search && <><span>›</span><span style={{ color: "#555" }}>{activeCategory?.label}</span></>}
                    {search && <><span>›</span><span style={{ color: "#555" }}>Search: "{search}"</span></>}
                </div>

                <div style={{ display: "grid", gridTemplateColumns: "240px 1fr", gap: "32px", alignItems: "start" }}>

                    {/* Sidebar */}
                    <div>
                        <div style={{
                            background: "#fff", borderRadius: "12px",
                            border: "1px solid #ebebeb", overflow: "hidden",
                            boxShadow: "0 2px 12px rgba(0,0,0,0.05)",
                            marginBottom: "16px",
                        }}>
                            <div style={{ padding: "14px 16px 10px", fontSize: "0.62rem", fontWeight: 800, color: "#aaa", letterSpacing: "0.1em", textTransform: "uppercase" }}>
                                Browse Topics
                            </div>
                            {CATEGORIES.map((cat) => {
                                const active = activeSlug === cat.slug && !search;
                                return (
                                    <button
                                        key={cat.slug}
                                        onClick={() => { setActiveSlug(cat.slug); setSearch(""); setOpenFaq(0); }}
                                        style={{
                                            display: "flex", alignItems: "center", justifyContent: "space-between",
                                            width: "100%", padding: "10px 16px", border: "none", borderBottom: "1px solid #f5f5f5",
                                            background: active ? "rgba(232,67,26,0.06)" : "transparent",
                                            cursor: "pointer", textAlign: "left",
                                            transition: "background 0.12s",
                                        }}
                                        onMouseEnter={e => { if (!active) e.currentTarget.style.background = "#fafafa"; }}
                                        onMouseLeave={e => { if (!active) e.currentTarget.style.background = "transparent"; }}
                                    >
                                        <div style={{ display: "flex", alignItems: "center", gap: "10px" }}>
                                            <span style={{ fontSize: "1rem", lineHeight: 1 }}>{cat.icon}</span>
                                            <span style={{ fontSize: "0.8rem", fontWeight: active ? 700 : 400, color: active ? "#e8431a" : "#444", lineHeight: 1.3 }}>
                                                {cat.label}
                                            </span>
                                        </div>
                                        <span style={{
                                            minWidth: "20px", height: "20px", borderRadius: "100px",
                                            background: active ? "#e8431a" : "#f0f0f0",
                                            color: active ? "#fff" : "#888",
                                            fontSize: "0.65rem", fontWeight: 700,
                                            display: "flex", alignItems: "center", justifyContent: "center",
                                            padding: "0 6px",
                                        }}>
                                            {cat.count}
                                        </span>
                                    </button>
                                );
                            })}
                        </div>

                        {/* Help card */}
                        <div style={{
                            background: "linear-gradient(135deg, #fff5f2, #fde8e2)",
                            borderRadius: "12px", border: "1px solid rgba(232,67,26,0.15)",
                            padding: "20px 16px",
                        }}>
                            <p style={{ fontSize: "0.82rem", fontWeight: 700, color: "#e8431a", margin: "0 0 4px" }}>Need direct help?</p>
                            <p style={{ fontSize: "0.75rem", color: "#888", margin: "0 0 14px", lineHeight: 1.5 }}>Our support team is available 24/7</p>
                            <Link href="/contact" style={{
                                display: "block", textAlign: "center", padding: "10px 16px",
                                background: "#e8431a", color: "#fff", borderRadius: "8px",
                                fontSize: "0.78rem", fontWeight: 700, textDecoration: "none",
                                boxShadow: "0 4px 12px rgba(232,67,26,0.3)",
                            }}>
                                Contact Support
                            </Link>
                        </div>
                    </div>

                    {/* FAQ content */}
                    <div>
                        {search ? (
                            <div style={{ marginBottom: "24px" }}>
                                <h2 style={{ fontSize: "1.4rem", fontWeight: 900, color: "#111", margin: "0 0 4px" }}>
                                    Search results for "{search}"
                                </h2>
                                <p style={{ fontSize: "0.82rem", color: "#aaa", margin: 0 }}>{displayFaqs.length} result{displayFaqs.length !== 1 ? "s" : ""} found</p>
                            </div>
                        ) : (
                            <div style={{ marginBottom: "24px" }}>
                                <h2 style={{ fontSize: "1.6rem", fontWeight: 900, color: "#111", margin: "0 0 6px" }}>
                                    {activeCategory?.label}
                                </h2>
                                <p style={{ fontSize: "0.82rem", color: "#aaa", margin: "0 0 12px" }}>
                                    {displayFaqs.length} article{displayFaqs.length !== 1 ? "s" : ""} in this section
                                </p>
                                <div style={{ width: "40px", height: "3px", background: "#e8431a", borderRadius: "2px" }} />
                            </div>
                        )}

                        {displayFaqs.length === 0 ? (
                            <div style={{
                                background: "#fff", borderRadius: "12px", border: "1px solid #ebebeb",
                                padding: "48px", textAlign: "center",
                            }}>
                                <p style={{ color: "#aaa", fontSize: "0.9rem" }}>No articles found.</p>
                            </div>
                        ) : (
                            <div style={{ display: "flex", flexDirection: "column", gap: "8px" }}>
                                {displayFaqs.map((faq, i) => (
                                    <div
                                        key={i}
                                        style={{
                                            background: "#fff", borderRadius: "10px",
                                            border: openFaq === i ? "1.5px solid rgba(232,67,26,0.25)" : "1px solid #ebebeb",
                                            overflow: "hidden",
                                            boxShadow: openFaq === i ? "0 4px 20px rgba(232,67,26,0.08)" : "0 1px 4px rgba(0,0,0,0.04)",
                                            transition: "border-color 0.15s, box-shadow 0.15s",
                                        }}
                                    >
                                        <button
                                            onClick={() => setOpenFaq(openFaq === i ? null : i)}
                                            style={{
                                                width: "100%", display: "flex", alignItems: "center",
                                                justifyContent: "space-between", gap: "16px",
                                                padding: "18px 20px", background: "none", border: "none",
                                                cursor: "pointer", textAlign: "left",
                                            }}
                                        >
                                            <span style={{ fontSize: "0.9rem", fontWeight: 600, color: "#111", lineHeight: 1.4 }}>
                                                {faq.q}
                                            </span>
                                            <ChevronIcon open={openFaq === i} />
                                        </button>

                                        {openFaq === i && (
                                            <div style={{ padding: "0 20px 20px" }}>
                                                <p style={{ fontSize: "0.88rem", color: "#555", lineHeight: 1.8, margin: "0 0 20px" }}>
                                                    {faq.a}
                                                </p>
                                                <div style={{
                                                    display: "flex", alignItems: "center", gap: "12px",
                                                    paddingTop: "14px", borderTop: "1px solid #f5f5f5",
                                                }}>
                                                    <span style={{ fontSize: "0.75rem", color: "#aaa" }}>Was this helpful?</span>
                                                    {helpful[i] ? (
                                                        <span style={{ fontSize: "0.75rem", color: "#e8431a", fontWeight: 600 }}>
                                                            {helpful[i] === "yes" ? "Thanks for your feedback!" : "We'll improve this article."}
                                                        </span>
                                                    ) : (
                                                        <>
                                                            <button
                                                                onClick={() => setHelpful({ ...helpful, [i]: "yes" })}
                                                                style={{ padding: "4px 14px", borderRadius: "6px", border: "1.5px solid #e8e8e8", background: "#fff", fontSize: "0.75rem", color: "#444", cursor: "pointer", transition: "border-color 0.12s" }}
                                                                onMouseEnter={e => e.currentTarget.style.borderColor = "#e8431a"}
                                                                onMouseLeave={e => e.currentTarget.style.borderColor = "#e8e8e8"}
                                                            >Yes</button>
                                                            <button
                                                                onClick={() => setHelpful({ ...helpful, [i]: "no" })}
                                                                style={{ padding: "4px 14px", borderRadius: "6px", border: "1.5px solid #e8e8e8", background: "#fff", fontSize: "0.75rem", color: "#444", cursor: "pointer", transition: "border-color 0.12s" }}
                                                                onMouseEnter={e => e.currentTarget.style.borderColor = "#e8431a"}
                                                                onMouseLeave={e => e.currentTarget.style.borderColor = "#e8e8e8"}
                                                            >No</button>
                                                        </>
                                                    )}
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
}

export default function ResourcesPage() {
    return (
        <Suspense fallback={<div style={{ minHeight: "100vh", background: "#f8f9fb" }} />}>
            <ResourcesContent />
        </Suspense>
    );
}
