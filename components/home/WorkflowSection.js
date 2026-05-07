"use client";

import { useState, useEffect } from "react";
import { useReveal, revealStyle } from "@/hooks/useReveal";

const steps = [
    {
        num: "01",
        title: "Select your Subscription Plan",
        desc: "Select the subscription plan that best fits your organization's needs and explore different options tailored to various sets of requirements and preferences.",
    },
    {
        num: "02",
        title: "Complete the Payment Process",
        desc: "Choose a secure payment method from our diverse selection for a seamless and convenient transaction process.",
    },
    {
        num: "03",
        title: "Complete the Basic Setup Wizard",
        desc: "Run the Basic Setup Wizard to streamline the initial configuration process. Follow the step-by-step guide to ensure the efficient setup of your whistleblowing system.",
    },
    {
        num: "04",
        title: "Personalize your Phoenix Whistleblowing Platform",
        desc: "Customize your whistleblowing platform to align with your organization's branding and requirements. Tailor settings and features to create a personalized and user-friendly experience for your organisation and audience.",
    },
    {
        num: "05",
        title: "Establish your Team",
        desc: "Invite your team members as managers, operators, or agents to form a robust team capable of effectively managing all your whistleblowing cases and escalations.",
    },
    {
        num: "06",
        title: "Promote your Whistleblowing Website and Channels",
        desc: "Promote your new whistleblowing website and channels to reach a wider audience. Consider providing training to your audience to raise awareness and ensure that they are well-informed and engaged in the reporting process—maximizing the effectiveness of your whistleblowing platform.",
    },
];

const PhoneScreen = ({ step }) => {
    const screens = [
        // Step 1 – Select Plan
        <div key={0} style={{ display: "flex", flexDirection: "column", alignItems: "center", justifyContent: "center", height: "100%", padding: "20px", textAlign: "center" }}>
            <div style={{ width: "64px", height: "64px", borderRadius: "50%", background: "#fff3ee", display: "flex", alignItems: "center", justifyContent: "center", marginBottom: "16px" }}>
                <svg width="28" height="28" fill="#e8431a" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22" fill="none" stroke="#e8431a" strokeWidth="2"/></svg>
            </div>
            <div style={{ fontWeight: 800, fontSize: "1rem", color: "#0a0a0a", marginBottom: "4px" }}>Select your<br />Subscription Plan</div>
            <div style={{ fontSize: "0.75rem", color: "#999", marginBottom: "16px" }}>Choose the perfect subscription</div>
            <div style={{ display: "flex", gap: "8px" }}>
                {["Basic", "Pro", "Enterprise"].map(p => (
                    <span key={p} style={{ background: "#e8431a", color: "#fff", borderRadius: "20px", padding: "4px 10px", fontSize: "0.72rem", fontWeight: 600 }}>{p}</span>
                ))}
            </div>
        </div>,

        // Step 2 – Payment
        <div key={1} style={{ display: "flex", flexDirection: "column", alignItems: "center", justifyContent: "center", height: "100%", padding: "20px", textAlign: "center" }}>
            <div style={{ width: "64px", height: "64px", borderRadius: "50%", background: "#f0fff4", display: "flex", alignItems: "center", justifyContent: "center", marginBottom: "16px" }}>
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2ecc71" strokeWidth="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            </div>
            <div style={{ fontWeight: 800, fontSize: "1rem", color: "#0a0a0a", marginBottom: "4px" }}>Secure Payment</div>
            <div style={{ fontSize: "0.75rem", color: "#999", marginBottom: "16px" }}>Multiple payment methods</div>
            <div style={{ display: "flex", gap: "12px", alignItems: "center" }}>
                <span style={{ fontSize: "1.4rem" }}>💳</span>
                <span style={{ fontSize: "1.4rem" }}>🔒</span>
                <span style={{ fontSize: "1.4rem" }}>✓</span>
            </div>
        </div>,

        // Step 3 – Setup Wizard
        <div key={2} style={{ display: "flex", flexDirection: "column", alignItems: "center", justifyContent: "center", height: "100%", padding: "20px", textAlign: "center" }}>
            <div style={{ width: "64px", height: "64px", borderRadius: "50%", background: "#e8f4fd", display: "flex", alignItems: "center", justifyContent: "center", marginBottom: "16px" }}>
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#3498db" strokeWidth="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div style={{ fontWeight: 800, fontSize: "1rem", color: "#0a0a0a", marginBottom: "4px" }}>Quick<br />Configuration</div>
            <div style={{ fontSize: "0.75rem", color: "#999", marginBottom: "16px" }}>Smart setup assistant</div>
            <div style={{ width: "120px", height: "6px", background: "#eee", borderRadius: "3px", overflow: "hidden" }}>
                <div style={{ width: "75%", height: "100%", background: "#2ecc71", borderRadius: "3px" }} />
            </div>
            <div style={{ fontSize: "0.72rem", color: "#999", marginTop: "6px" }}>75% Complete</div>
        </div>,

        // Step 4 – Personalization
        <div key={3} style={{ display: "flex", flexDirection: "column", alignItems: "center", justifyContent: "center", height: "100%", padding: "20px", textAlign: "center" }}>
            <div style={{ width: "64px", height: "64px", borderRadius: "50%", background: "#f3eeff", display: "flex", alignItems: "center", justifyContent: "center", marginBottom: "16px" }}>
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#9b59b6" strokeWidth="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
            </div>
            <div style={{ fontWeight: 800, fontSize: "1rem", color: "#0a0a0a", marginBottom: "4px" }}>Personalization</div>
            <div style={{ fontSize: "0.75rem", color: "#999", marginBottom: "16px" }}>Adapt to your needs</div>
            <div style={{ display: "flex", gap: "8px" }}>
                {["#e8431a", "#2ecc71", "#3498db", "#9b59b6"].map(c => (
                    <div key={c} style={{ width: "24px", height: "24px", borderRadius: "50%", background: c }} />
                ))}
            </div>
        </div>,

        // Step 5 – Team
        <div key={4} style={{ display: "flex", flexDirection: "column", alignItems: "center", justifyContent: "center", height: "100%", padding: "20px", textAlign: "center" }}>
            <div style={{ width: "64px", height: "64px", borderRadius: "50%", background: "#fff8e6", display: "flex", alignItems: "center", justifyContent: "center", marginBottom: "16px" }}>
                <svg width="28" height="28" viewBox="0 0 24 24" fill="#e8431a"><circle cx="9" cy="8" r="3"/><circle cx="15" cy="8" r="3" opacity="0.6"/><path d="M2 20c0-4 3-6 7-6s7 2 7 6" opacity="0.7"/></svg>
            </div>
            <div style={{ fontWeight: 800, fontSize: "1rem", color: "#0a0a0a", marginBottom: "4px" }}>Invite your Team</div>
            <div style={{ fontSize: "0.75rem", color: "#999", marginBottom: "16px" }}>Simplified collaboration</div>
            <div style={{ display: "flex", gap: "8px" }}>
                {["A", "B", "C", "+"].map(l => (
                    <div key={l} style={{ width: "28px", height: "28px", borderRadius: "50%", background: "#e8431a", display: "flex", alignItems: "center", justifyContent: "center", color: "#fff", fontWeight: 700, fontSize: "0.75rem" }}>{l}</div>
                ))}
            </div>
        </div>,

        // Step 6 – Go live
        <div key={5} style={{ display: "flex", flexDirection: "column", alignItems: "center", justifyContent: "center", height: "100%", padding: "20px", textAlign: "center" }}>
            <div style={{ width: "64px", height: "64px", borderRadius: "50%", border: "3px solid #3498db", display: "flex", alignItems: "center", justifyContent: "center", marginBottom: "16px" }}>
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#3498db" strokeWidth="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div style={{ fontWeight: 800, fontSize: "1rem", color: "#0a0a0a", marginBottom: "4px" }}>Let's Go! 🚀</div>
            <div style={{ fontSize: "0.75rem", color: "#999", marginBottom: "12px" }}>Your platform is ready</div>
            <div style={{ display: "flex", flexDirection: "column", gap: "6px" }}>
                {["✓ Configured", "✓ Secured", "✓ Active"].map(t => (
                    <div key={t} style={{ background: "#e8f9f0", border: "1px solid #3498db", borderRadius: "6px", padding: "4px 12px", fontSize: "0.75rem", color: "#0a0a0a", fontWeight: 600 }}>{t}</div>
                ))}
            </div>
        </div>,
    ];

    return (
        <div style={{
            width: "230px", height: "490px",
            background: "#1a1a1a", borderRadius: "36px",
            padding: "10px",
            boxShadow: "0 24px 60px rgba(0,0,0,0.45), inset 0 0 0 1px rgba(255,255,255,0.08)",
            flexShrink: 0,
            position: "relative",
        }}>
            {/* Notch */}
            {/* Side buttons */}
            <div style={{ position: "absolute", right: "-3px", top: "100px", width: "3px", height: "40px", background: "#2a2a2a", borderRadius: "0 2px 2px 0" }} />
            <div style={{ position: "absolute", left: "-3px", top: "80px", width: "3px", height: "28px", background: "#2a2a2a", borderRadius: "2px 0 0 2px" }} />
            <div style={{ position: "absolute", left: "-3px", top: "118px", width: "3px", height: "28px", background: "#2a2a2a", borderRadius: "2px 0 0 2px" }} />

            <div style={{ position: "relative", background: "#fff", borderRadius: "28px", height: "100%", overflow: "hidden" }}>
                {/* Dynamic Island / notch */}
                <div style={{
                    position: "absolute", top: "10px", left: "50%", transform: "translateX(-50%)",
                    width: "72px", height: "22px", background: "#111", borderRadius: "12px", zIndex: 2,
                }} />
                <div style={{ paddingTop: "40px", height: "100%" }}>
                    {screens[step]}
                </div>
            </div>
        </div>
    );
};

export default function WorkflowSection() {
    const [active, setActive] = useState(0);
    const phone = useReveal();
    const grid = useReveal();

    // Auto-advance every 3 seconds
    useEffect(() => {
        const t = setInterval(() => setActive(a => (a + 1) % steps.length), 3000);
        return () => clearInterval(t);
    }, []);

    return (
        <section style={{ background: "#e8431a", padding: "100px 60px" }}>
            <div style={{ maxWidth: "1200px", margin: "0 auto" }}>
                <div style={{ display: "flex", gap: "48px", alignItems: "stretch", flexWrap: "wrap" }}>

                    {/* Phone — LEFT, stretches to match grid height */}
                    <div ref={phone.ref} style={{
                        display: "flex", justifyContent: "center", alignItems: "center",
                        flexShrink: 0, width: "220px",
                        ...revealStyle(phone.visible, { direction: "left" }),
                    }}>
                        <PhoneScreen step={active} />
                    </div>

                    {/* Steps grid — RIGHT */}
                    <div ref={grid.ref} style={{ flex: 1, minWidth: "340px", ...revealStyle(grid.visible, { direction: "right", delay: 100 }) }}>
                        <div style={{
                            display: "grid",
                            gridTemplateColumns: "repeat(2, 1fr)",
                            gap: "16px",
                        }}>
                            {steps.map((step, i) => (
                                <div
                                    key={i}
                                    onClick={() => setActive(i)}
                                    style={{
                                        background: active === i ? "rgba(0,0,0,0.15)" : "rgba(255,255,255,0.1)",
                                        borderRadius: "16px",
                                        padding: "20px",
                                        cursor: "pointer",
                                        transition: "background 0.2s",
                                    }}
                                >
                                    {/* Number badge */}
                                    <div style={{
                                        display: "inline-flex", alignItems: "center", justifyContent: "center",
                                        width: "36px", height: "36px",
                                        background: active === i ? "#e8431a" : "rgba(255,255,255,0.9)",
                                        borderRadius: "8px",
                                        fontWeight: 800, fontSize: "0.85rem",
                                        color: active === i ? "#fff" : "#e8431a",
                                        marginBottom: "12px",
                                        boxShadow: active === i ? "none" : "0 2px 8px rgba(0,0,0,0.1)",
                                    }}>
                                        {step.num}
                                    </div>
                                    <div style={{ fontWeight: 700, color: "#fff", fontSize: "0.95rem", marginBottom: "8px", lineHeight: 1.3 }}>
                                        {step.title}
                                    </div>
                                    <div style={{ color: "rgba(255,255,255,0.85)", fontSize: "0.8rem", lineHeight: 1.6 }}>
                                        {step.desc}
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
