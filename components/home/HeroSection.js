"use client";

import Link from "next/link";

export default function HeroSection() {
    return (
        <div style={{
            position: "relative", width: "100%",
            background: "#ffffff",
            overflow: "hidden",
        }}>
            {/* Subtle background tint */}
            <div style={{
                position: "absolute", top: "-100px", right: "-100px",
                width: "600px", height: "600px",
                background: "radial-gradient(circle, rgba(232,67,26,0.06) 0%, transparent 65%)",
                pointerEvents: "none",
            }} />
            {/* Bottom wave */}
            <div style={{ position: "absolute", bottom: 0, left: 0, right: 0, lineHeight: 0, pointerEvents: "none" }}>
                <svg viewBox="0 0 1440 60" preserveAspectRatio="none" style={{ width: "100%", height: "60px" }}>
                    <path d="M0,30 C360,60 1080,0 1440,30 L1440,60 L0,60 Z" fill="#f8f9fb" />
                </svg>
            </div>

            <div style={{ maxWidth: "1200px", margin: "0 auto", padding: "0 32px" }}>
                <div style={{
                    display: "flex", justifyContent: "space-between",
                    alignItems: "center", gap: "60px", flexWrap: "wrap",
                    minHeight: "calc(100vh - 60px)",
                    paddingTop: "60px", paddingBottom: "80px",
                }}>
                    {/* Left */}
                    <div style={{ maxWidth: "480px", zIndex: 10, position: "relative" }}>
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

                    {/* Right: dashboard mockup */}
                    <div style={{ flex: "1", minWidth: "300px", maxWidth: "580px", position: "relative" }}>
                        <DashboardMockup />
                    </div>
                </div>
            </div>
        </div>
    );
}

function DashboardMockup() {
    return (
        <div style={{ position: "relative" }}>
            {/* Main dashboard card */}
            <div style={{
                background: "#fff",
                borderRadius: "20px",
                boxShadow: "0 24px 80px rgba(0,0,0,0.10)",
                border: "1px solid #f0f0f0",
                overflow: "hidden",
            }}>
                {/* Browser chrome */}
                <div style={{
                    background: "#f8f9fb",
                    borderBottom: "1px solid #efefef",
                    padding: "12px 16px",
                    display: "flex", alignItems: "center", gap: "8px",
                }}>
                    <div style={{ display: "flex", gap: "6px" }}>
                        <div style={{ width: "10px", height: "10px", borderRadius: "50%", background: "#e8431a", opacity: 0.5 }} />
                        <div style={{ width: "10px", height: "10px", borderRadius: "50%", background: "#f5a623", opacity: 0.5 }} />
                        <div style={{ width: "10px", height: "10px", borderRadius: "50%", background: "#27ae60", opacity: 0.5 }} />
                    </div>
                    <div style={{
                        flex: 1, background: "#efefef", borderRadius: "6px",
                        height: "22px", maxWidth: "260px", margin: "0 auto",
                        display: "flex", alignItems: "center", justifyContent: "center",
                    }}>
                        <span style={{ fontSize: "0.68rem", color: "#aaa", letterSpacing: "0.02em" }}>app.phoenix-whistleblowing.com</span>
                    </div>
                </div>

                {/* Dashboard content */}
                <div style={{ padding: "24px" }}>
                    {/* Header row */}
                    <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center", marginBottom: "20px" }}>
                        <div>
                            <div style={{ fontSize: "0.68rem", color: "#aaa", letterSpacing: "0.05em", textTransform: "uppercase", marginBottom: "2px" }}>Case Management</div>
                            <div style={{ fontSize: "1rem", fontWeight: 800, color: "#111" }}>Dashboard Overview</div>
                        </div>
                        <div style={{
                            background: "#e8431a", color: "#fff", fontSize: "0.7rem",
                            fontWeight: 700, padding: "6px 14px", borderRadius: "6px",
                            letterSpacing: "0.03em",
                        }}>+ New Report</div>
                    </div>

                    {/* Stats row */}
                    <div style={{ display: "grid", gridTemplateColumns: "repeat(3,1fr)", gap: "12px", marginBottom: "20px" }}>
                        {[
                            { label: "Active Cases", value: "24", change: "+3 this week", color: "#e8431a" },
                            { label: "Resolved", value: "138", change: "↑ 92% rate", color: "#27ae60" },
                            { label: "Avg. Resolution", value: "8.2d", change: "−1.4d vs last mo.", color: "#3498db" },
                        ].map((s, i) => (
                            <div key={i} style={{
                                background: "#f8f9fb", borderRadius: "10px",
                                padding: "14px 12px",
                            }}>
                                <div style={{ fontSize: "0.65rem", color: "#aaa", textTransform: "uppercase", letterSpacing: "0.04em", marginBottom: "4px" }}>{s.label}</div>
                                <div style={{ fontSize: "1.35rem", fontWeight: 900, color: "#111", lineHeight: 1 }}>{s.value}</div>
                                <div style={{ fontSize: "0.65rem", color: s.color, marginTop: "4px", fontWeight: 600 }}>{s.change}</div>
                            </div>
                        ))}
                    </div>

                    {/* Case list */}
                    <div style={{ borderRadius: "10px", overflow: "hidden", border: "1px solid #f0f0f0" }}>
                        {[
                            { id: "#2047", type: "Financial Misconduct", status: "Under Review", statusColor: "#f5a623", date: "02 May 2026" },
                            { id: "#2046", type: "Workplace Safety", status: "Resolved", statusColor: "#27ae60", date: "29 Apr 2026" },
                            { id: "#2045", type: "Data Privacy", status: "Escalated", statusColor: "#e8431a", date: "27 Apr 2026" },
                        ].map((c, i) => (
                            <div key={i} style={{
                                display: "flex", alignItems: "center", justifyContent: "space-between",
                                padding: "11px 14px",
                                background: i % 2 === 0 ? "#fff" : "#fafafa",
                                borderBottom: i < 2 ? "1px solid #f4f4f4" : "none",
                            }}>
                                <div style={{ display: "flex", alignItems: "center", gap: "10px" }}>
                                    <span style={{ fontSize: "0.72rem", color: "#bbb", fontFamily: "monospace" }}>{c.id}</span>
                                    <span style={{ fontSize: "0.78rem", color: "#333", fontWeight: 600 }}>{c.type}</span>
                                </div>
                                <div style={{ display: "flex", alignItems: "center", gap: "12px" }}>
                                    <span style={{
                                        fontSize: "0.65rem", fontWeight: 700, color: c.statusColor,
                                        background: c.statusColor + "18",
                                        padding: "3px 9px", borderRadius: "20px",
                                    }}>{c.status}</span>
                                    <span style={{ fontSize: "0.68rem", color: "#ccc" }}>{c.date}</span>
                                </div>
                            </div>
                        ))}
                    </div>

                    {/* Compliance badges */}
                    <div style={{ display: "flex", gap: "8px", marginTop: "16px", flexWrap: "wrap" }}>
                        {["GDPR", "FADP", "EU Dir. 2019/1937", "ISO 27001"].map(b => (
                            <span key={b} style={{
                                fontSize: "0.65rem", fontWeight: 700, color: "#555",
                                background: "#f0f0f0", borderRadius: "4px",
                                padding: "4px 8px", letterSpacing: "0.03em",
                            }}>{b}</span>
                        ))}
                    </div>
                </div>
            </div>

            {/* Floating notification card */}
            <div style={{
                position: "absolute", top: "-20px", right: "-20px",
                background: "#1a1d2e", color: "#fff",
                borderRadius: "14px", padding: "14px 18px",
                maxWidth: "200px",
                boxShadow: "0 12px 40px rgba(0,0,0,0.18)",
                zIndex: 2,
            }}>
                <div style={{ display: "flex", alignItems: "center", gap: "8px", marginBottom: "6px" }}>
                    <div style={{ width: "8px", height: "8px", borderRadius: "50%", background: "#27ae60", flexShrink: 0 }} />
                    <span style={{ fontSize: "0.65rem", color: "rgba(255,255,255,0.5)", letterSpacing: "0.05em", textTransform: "uppercase" }}>Anonymous Report</span>
                </div>
                <div style={{ fontSize: "0.78rem", color: "#fff", lineHeight: 1.4, fontWeight: 500 }}>
                    New case submitted securely from <strong style={{ color: "#e8431a" }}>Switzerland</strong>
                </div>
            </div>

            {/* Floating encryption badge */}
            <div style={{
                position: "absolute", bottom: "24px", left: "-24px",
                background: "#fff",
                border: "1px solid #f0f0f0",
                borderRadius: "14px", padding: "12px 16px",
                boxShadow: "0 8px 32px rgba(0,0,0,0.08)",
                display: "flex", alignItems: "center", gap: "10px",
                zIndex: 2,
            }}>
                <div style={{
                    width: "36px", height: "36px", borderRadius: "10px",
                    background: "#fff5f2",
                    display: "flex", alignItems: "center", justifyContent: "center",
                    fontSize: "1.1rem", flexShrink: 0,
                }}>🔒</div>
                <div>
                    <div style={{ fontSize: "0.78rem", fontWeight: 800, color: "#111" }}>End-to-end encrypted</div>
                    <div style={{ fontSize: "0.65rem", color: "#aaa", marginTop: "1px" }}>AES-256 · Zero-knowledge</div>
                </div>
            </div>
        </div>
    );
}
