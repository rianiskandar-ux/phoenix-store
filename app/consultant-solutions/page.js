"use client";

import Link from "next/link";
import Image from "next/image";
import { useReveal, revealStyle } from "@/hooks/useReveal";

export default function ConsultantSolutionsPage() {
    return (
        <div>
            <HeroSection />
            <AboutSection />
            <BenefitsSection />
            <KeyFeaturesSection />
            <CtaSection />
        </div>
    );
}

function HeroSection() {
    const content = useReveal();
    return (
        <section style={{
            background: "linear-gradient(135deg, #f0e6ff 0%, #fce4ec 60%, #fff3e0 100%)",
            padding: "80px 32px 0",
            position: "relative",
            overflow: "hidden",
            textAlign: "center",
        }}>
            {/* Ornament top-right */}
            <div style={{ position: "absolute", top: "-20px", right: "-20px", opacity: 0.35, pointerEvents: "none" }}>
                <Image src="/assets/ORNAMEN 3.png" alt="" width={200} height={120} style={{ objectFit: "contain", transform: "rotate(180deg)" }} />
            </div>
            {/* Ornament top-left */}
            <div style={{ position: "absolute", top: "40px", left: "40px", opacity: 0.6, pointerEvents: "none" }}>
                <Image src="/assets/line.png" alt="" width={64} height={32} style={{ objectFit: "contain" }} />
            </div>

            <div ref={content.ref} style={{ maxWidth: "680px", margin: "0 auto", position: "relative", zIndex: 1, ...revealStyle(content.visible, { direction: "up" }) }}>
                <div style={{
                    display: "inline-flex", alignItems: "center", gap: "8px",
                    background: "rgba(255,255,255,0.7)", border: "1px solid rgba(232,67,26,0.2)",
                    borderRadius: "100px", padding: "5px 14px", marginBottom: "24px",
                }}>
                    <span style={{ width: "6px", height: "6px", borderRadius: "50%", background: "#e8431a", display: "inline-block" }} />
                    <span style={{ color: "#e8431a", fontSize: "0.72rem", fontWeight: 700, letterSpacing: "0.07em" }}>SIMPLE & TRANSPARENT PRICING</span>
                </div>
                <h1 style={{ fontSize: "clamp(2rem, 4vw, 3rem)", fontWeight: 900, color: "#111", margin: "0 0 12px", lineHeight: 1.2 }}>
                    Consultant <span style={{ color: "#e8431a" }}>Solutions</span>
                </h1>
                {/* Orange divider */}
                <div style={{ display: "flex", alignItems: "center", justifyContent: "center", gap: "8px", margin: "16px 0 24px" }}>
                    <div style={{ width: "40px", height: "2px", background: "#e8431a", borderRadius: "2px" }} />
                    <div style={{ width: "6px", height: "6px", borderRadius: "50%", background: "#e8431a" }} />
                    <div style={{ width: "40px", height: "2px", background: "#e8431a", borderRadius: "2px" }} />
                </div>
                <p style={{ color: "#555", fontSize: "1rem", lineHeight: 1.75, margin: "0 0 40px" }}>
                    Empower your consulting practice with Phoenix — the enterprise-grade whistleblowing platform built for compliance, trust, and growth.
                </p>
            </div>

            {/* Wave bottom */}
            <div style={{ lineHeight: 0, marginTop: "40px" }}>
                <svg viewBox="0 0 1440 80" preserveAspectRatio="none" style={{ width: "100%", height: "80px", display: "block" }}>
                    <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" fill="#ffffff" />
                </svg>
            </div>
        </section>
    );
}

function AboutSection() {
    const left = useReveal();
    const right = useReveal();
    return (
        <section style={{ background: "#fff", padding: "80px 32px" }}>
            <div style={{ maxWidth: "1200px", margin: "0 auto", display: "flex", gap: "64px", alignItems: "center", flexWrap: "wrap" }}>
                {/* Left text */}
                <div ref={left.ref} style={{ flex: "1", minWidth: "300px", ...revealStyle(left.visible, { direction: "left" }) }}>
                    <div style={{ display: "flex", alignItems: "center", gap: "10px", marginBottom: "16px" }}>
                        <div style={{ width: "3px", height: "20px", background: "#e8431a", borderRadius: "2px" }} />
                        <span style={{ fontSize: "0.72rem", fontWeight: 700, color: "#e8431a", letterSpacing: "0.08em" }}>FOR CONSULTANTS</span>
                    </div>
                    <h2 style={{ fontSize: "clamp(1.8rem, 3vw, 2.5rem)", fontWeight: 900, color: "#111", margin: "0 0 20px", lineHeight: 1.2 }}>
                        As A <span style={{ color: "#e8431a" }}>Consultant</span>
                    </h2>
                    <p style={{ color: "#555", fontSize: "0.95rem", lineHeight: 1.8, margin: "0 0 16px" }}>
                        Enhance your consulting portfolio with cutting-edge whistleblowing services for your clients. Introducing Phoenix Whistleblowing Software, a powerful SaaS solution designed to streamline whistleblowing channels for multiple clients effortlessly.
                    </p>
                    <p style={{ color: "#555", fontSize: "0.95rem", lineHeight: 1.8, margin: "0 0 32px" }}>
                        Stay ahead of the competition and empower your business with Phoenix Whistleblowing Software.
                    </p>
                    <Link href="/get-started" style={{
                        display: "inline-block", padding: "14px 32px",
                        background: "#e8431a", color: "#fff",
                        fontWeight: 700, fontSize: "0.875rem",
                        borderRadius: "8px", textDecoration: "none",
                        boxShadow: "0 6px 24px rgba(232,67,26,0.35)",
                    }}>
                        Subscribe Now
                    </Link>
                </div>

                {/* Right image */}
                <div ref={right.ref} style={{ flex: "1", minWidth: "280px", maxWidth: "460px", ...revealStyle(right.visible, { direction: "right" }) }}>
                    <div style={{
                        borderRadius: "20px", overflow: "hidden",
                        boxShadow: "0 20px 60px rgba(0,0,0,0.12)",
                        position: "relative",
                    }}>
                        <Image
                            src="/assets/image easey steps.png"
                            alt="Phoenix Platform"
                            width={460} height={320}
                            style={{ width: "100%", height: "auto", display: "block" }}
                        />
                        {/* Badge overlay */}
                        <div style={{
                            position: "absolute", bottom: "16px", left: "16px",
                            background: "rgba(255,255,255,0.95)", borderRadius: "10px",
                            padding: "8px 14px", display: "flex", alignItems: "center", gap: "8px",
                            boxShadow: "0 4px 16px rgba(0,0,0,0.1)",
                        }}>
                            <div style={{ width: "8px", height: "8px", borderRadius: "50%", background: "#e8431a" }} />
                            <span style={{ fontSize: "0.75rem", fontWeight: 700, color: "#111" }}>Phoenix Platform</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}

function BenefitsSection() {
    const title = useReveal();
    const cards = useReveal();
    const benefits = [
        {
            title: "New Revenue Stream",
            icon: "💰",
            desc: "Expand with a profitable new service: charge clients for the service of establishing and operating their whistleblowing channels, managing and reporting the incoming disclosures, by utilizing Phoenix whistleblowing software as your infrastructure.",
        },
        {
            title: "Drive Deeper Engagement",
            icon: "🤝",
            desc: "Operate Whistleblowing Channels for clients and unleash a surge in investigative opportunities.",
        },
        {
            title: "Differentiation",
            icon: "⭐",
            desc: "Drive growth through Whistleblowing services.",
        },
    ];

    return (
        <section style={{ background: "#f8f9fb", padding: "80px 32px" }}>
            <div style={{ maxWidth: "1200px", margin: "0 auto" }}>
                <div ref={title.ref} style={{ textAlign: "center", marginBottom: "56px", ...revealStyle(title.visible, { direction: "up" }) }}>
                    <h2 style={{ fontSize: "clamp(1.8rem, 3vw, 2.5rem)", fontWeight: 900, color: "#111", margin: "0 0 16px" }}>
                        What&apos;s In It For You?
                    </h2>
                    <p style={{ color: "#888", fontSize: "0.95rem", maxWidth: "520px", margin: "0 auto", lineHeight: 1.7 }}>
                        Phoenix opens a new chapter for consultants — from one-time engagements to sustainable, scalable compliance partnerships.
                    </p>
                </div>

                <div ref={cards.ref} style={{ display: "grid", gridTemplateColumns: "repeat(3, 1fr)", gap: "24px", ...revealStyle(cards.visible, { direction: "up", delay: 100 }) }}>
                    {benefits.map((b, i) => (
                        <div key={i} style={{
                            background: "#fff", borderRadius: "16px",
                            padding: "0", overflow: "hidden",
                            boxShadow: "0 2px 12px rgba(0,0,0,0.06)",
                            border: "1px solid #f0f0f0",
                        }}>
                            {/* Orange header bar */}
                            <div style={{
                                background: "#e8431a", padding: "16px 20px",
                                display: "flex", alignItems: "center", justifyContent: "space-between",
                            }}>
                                <span style={{ color: "#fff", fontWeight: 700, fontSize: "0.875rem" }}>{b.title}</span>
                                <span style={{ fontSize: "1.1rem" }}>{b.icon}</span>
                            </div>
                            <div style={{ padding: "20px" }}>
                                <p style={{ color: "#555", fontSize: "0.875rem", lineHeight: 1.75, margin: 0 }}>{b.desc}</p>
                            </div>
                        </div>
                    ))}
                </div>

                <div style={{ textAlign: "center", marginTop: "48px" }}>
                    <Link href="/get-started" style={{
                        display: "inline-block", padding: "14px 36px",
                        background: "#e8431a", color: "#fff",
                        fontWeight: 700, fontSize: "0.875rem",
                        borderRadius: "8px", textDecoration: "none",
                        boxShadow: "0 6px 24px rgba(232,67,26,0.35)",
                    }}>
                        Subscribe Now
                    </Link>
                </div>
            </div>
        </section>
    );
}

function KeyFeaturesSection() {
    const title = useReveal();
    const cards = useReveal();
    const features = [
        {
            icon: "📡",
            title: "Software as a Service",
            desc: "Experience the benefits of a cloud-based SaaS solution, offering your clients the advantage of cutting-edge technology without the burden of costly infrastructure and ongoing maintenance.",
        },
        {
            icon: "👥",
            title: "Multiple Clients",
            desc: "As a consultant representing your clients, you juggle multiple accounts while ensuring strict data segregation for each.",
        },
        {
            icon: "🎨",
            title: "Customizable",
            desc: "Tailor Phoenix to your client preferences. Brand the site, choose the domain.",
        },
        {
            icon: "🌐",
            title: "Multiple Language",
            desc: "Select the relevant languages, enabling you to serve multinational companies.",
        },
    ];

    return (
        <section style={{ background: "#fff", padding: "80px 32px" }}>
            <div style={{ maxWidth: "1200px", margin: "0 auto" }}>
                <div ref={title.ref} style={{ marginBottom: "48px", ...revealStyle(title.visible, { direction: "up" }) }}>
                    <div style={{ display: "flex", alignItems: "center", gap: "10px", marginBottom: "12px" }}>
                        <div style={{ width: "3px", height: "20px", background: "#e8431a", borderRadius: "2px" }} />
                        <span style={{ fontSize: "0.72rem", fontWeight: 700, color: "#e8431a", letterSpacing: "0.08em" }}>PLATFORM CAPABILITIES</span>
                    </div>
                    <h2 style={{ fontSize: "clamp(1.8rem, 3vw, 2.4rem)", fontWeight: 900, color: "#111", margin: 0 }}>
                        Key Features
                    </h2>
                </div>

                <div ref={cards.ref} style={{ display: "grid", gridTemplateColumns: "repeat(4, 1fr)", gap: "20px", ...revealStyle(cards.visible, { direction: "up", delay: 100 }) }}>
                    {features.map((f, i) => (
                        <div key={i} style={{
                            background: "#fff8f5", borderRadius: "16px",
                            padding: "28px 22px",
                            border: "1px solid #f0e8e0",
                        }}>
                            <div style={{
                                width: "44px", height: "44px", borderRadius: "12px",
                                background: "#fff", border: "1px solid #f0e0d8",
                                display: "flex", alignItems: "center", justifyContent: "center",
                                fontSize: "1.3rem", marginBottom: "16px",
                                boxShadow: "0 2px 8px rgba(232,67,26,0.08)",
                            }}>{f.icon}</div>
                            <h3 style={{ fontSize: "0.95rem", fontWeight: 800, color: "#111", margin: "0 0 10px" }}>{f.title}</h3>
                            <p style={{ fontSize: "0.82rem", color: "#777", lineHeight: 1.7, margin: 0 }}>{f.desc}</p>
                        </div>
                    ))}
                </div>

                <div style={{ textAlign: "center", marginTop: "48px" }}>
                    <Link href="/features" style={{
                        display: "inline-block", padding: "13px 32px",
                        background: "transparent", color: "#e8431a",
                        fontWeight: 700, fontSize: "0.875rem",
                        borderRadius: "8px", textDecoration: "none",
                        border: "1.5px solid #e8431a",
                    }}>
                        See All Features
                    </Link>
                </div>
            </div>
        </section>
    );
}

function CtaSection() {
    const card = useReveal();
    return (
        <section style={{ background: "#f8f9fb", padding: "60px 32px 80px" }}>
            <div style={{ maxWidth: "1200px", margin: "0 auto" }}>
                <div ref={card.ref} style={{
                    background: "linear-gradient(143deg, rgb(10,10,10) 0%, rgb(232,67,26) 100%)",
                    borderRadius: "24px", padding: "56px 64px",
                    display: "flex", alignItems: "center", justifyContent: "space-between",
                    gap: "32px", flexWrap: "wrap", position: "relative", overflow: "hidden",
                    ...revealStyle(card.visible, { direction: "up" }),
                }}>
                    <div style={{ position: "absolute", top: "24px", right: "24px", opacity: 0.4, pointerEvents: "none" }}>
                        <Image src="/assets/line.png" alt="" width={70} height={36} style={{ objectFit: "contain" }} />
                    </div>
                    <div style={{ position: "absolute", bottom: "-10px", right: "180px", opacity: 0.15, pointerEvents: "none" }}>
                        <Image src="/assets/ornamen black and orange.png" alt="" width={100} height={100} style={{ objectFit: "contain" }} />
                    </div>
                    <div style={{ zIndex: 1 }}>
                        <h2 style={{ fontSize: "clamp(1.4rem, 2.5vw, 2rem)", fontWeight: 900, color: "#fff", margin: "0 0 8px", lineHeight: 1.2 }}>
                            Ready to grow your consulting practice?
                        </h2>
                        <p style={{ color: "rgba(255,255,255,0.6)", fontSize: "0.95rem", margin: 0 }}>
                            Join consultants across Europe using Phoenix to deliver compliance services at scale.
                        </p>
                    </div>
                    <Link href="/get-started" style={{
                        display: "inline-block", padding: "15px 36px",
                        background: "#fff", color: "#e8431a",
                        fontWeight: 800, fontSize: "0.875rem",
                        borderRadius: "8px", textDecoration: "none",
                        flexShrink: 0, zIndex: 1,
                        boxShadow: "0 6px 24px rgba(0,0,0,0.15)",
                    }}>
                        Subscribe Now
                    </Link>
                </div>
            </div>
        </section>
    );
}
