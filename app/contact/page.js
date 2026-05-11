"use client";

import Image from "next/image";
import { useState } from "react";
import { useReveal, revealStyle } from "@/hooks/useReveal";

export default function ContactPage() {
    const hero = useReveal();
    const form = useReveal();

    const [fields, setFields] = useState({ name: "", email: "", message: "" });
    const [submitted, setSubmitted] = useState(false);
    const [loading, setLoading] = useState(false);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        await new Promise((r) => setTimeout(r, 800));
        setSubmitted(true);
        setLoading(false);
    };

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
                        <span style={{ color: "#e8431a", fontSize: "0.72rem", fontWeight: 700, letterSpacing: "0.07em" }}>CONTACT US</span>
                    </div>
                    <h1 style={{ fontSize: "clamp(2rem, 4vw, 3rem)", fontWeight: 900, color: "#111", margin: "0 0 12px", lineHeight: 1.2 }}>
                        Get in Touch: <span style={{ color: "#e8431a" }}>Your Questions,</span><br />Our Answers.
                    </h1>
                    <p style={{ color: "#666", fontSize: "1rem", maxWidth: "520px", margin: "0 auto 48px", lineHeight: 1.7 }}>
                        If you have any questions, feedback, or special requests, don't hesitate to reach out to us through our easy-to-use contact form. Our dedicated team is eager to assist you.
                    </p>
                </div>

                <div style={{ lineHeight: 0 }}>
                    <svg viewBox="0 0 1440 60" preserveAspectRatio="none" style={{ width: "100%", height: "60px", display: "block" }}>
                        <path d="M0,30 C360,60 1080,0 1440,30 L1440,60 L0,60 Z" fill="#f8f9fb" />
                    </svg>
                </div>
            </div>

            {/* Form */}
            <div style={{ maxWidth: "680px", margin: "0 auto", padding: "60px 24px 80px" }}>
                <div
                    ref={form.ref}
                    style={{
                        background: "#fff",
                        borderRadius: "20px",
                        padding: "44px 40px",
                        boxShadow: "0 2px 24px rgba(0,0,0,0.07)",
                        border: "1px solid #ebebeb",
                        ...revealStyle(form.visible, { direction: "up" }),
                    }}
                >
                    {submitted ? (
                        <div style={{ textAlign: "center", padding: "40px 0" }}>
                            <div style={{
                                width: "56px", height: "56px", borderRadius: "50%",
                                background: "rgba(232,67,26,0.1)", display: "flex",
                                alignItems: "center", justifyContent: "center", margin: "0 auto 20px",
                            }}>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M5 13l4 4L19 7" stroke="#e8431a" strokeWidth="2.5" strokeLinecap="round" strokeLinejoin="round" />
                                </svg>
                            </div>
                            <h3 style={{ fontSize: "1.3rem", fontWeight: 800, color: "#111", margin: "0 0 8px" }}>Message Sent!</h3>
                            <p style={{ color: "#888", fontSize: "0.9rem", lineHeight: 1.6 }}>Thank you for reaching out. Our team will get back to you shortly.</p>
                        </div>
                    ) : (
                        <form onSubmit={handleSubmit}>
                            <p style={{ fontSize: "0.72rem", color: "#aaa", marginBottom: "28px" }}>
                                <span style={{ color: "#e8431a" }}>*</span> indicates required fields
                            </p>

                            {/* Full Name */}
                            <div style={{ marginBottom: "20px" }}>
                                <label style={{ display: "block", fontSize: "0.82rem", fontWeight: 600, color: "#333", marginBottom: "6px" }}>
                                    Full Name <span style={{ color: "#e8431a" }}>*</span>
                                </label>
                                <input
                                    type="text"
                                    required
                                    value={fields.name}
                                    onChange={(e) => setFields({ ...fields, name: e.target.value })}
                                    style={{
                                        width: "100%", padding: "11px 14px", borderRadius: "8px",
                                        border: "1.5px solid #e8e8e8", fontSize: "0.9rem", color: "#333",
                                        outline: "none", boxSizing: "border-box",
                                        transition: "border-color 0.15s",
                                    }}
                                    onFocus={(e) => e.target.style.borderColor = "#e8431a"}
                                    onBlur={(e) => e.target.style.borderColor = "#e8e8e8"}
                                />
                            </div>

                            {/* Work Email */}
                            <div style={{ marginBottom: "20px" }}>
                                <label style={{ display: "block", fontSize: "0.82rem", fontWeight: 600, color: "#333", marginBottom: "6px" }}>
                                    Work Email <span style={{ color: "#e8431a" }}>*</span>
                                </label>
                                <input
                                    type="email"
                                    required
                                    value={fields.email}
                                    onChange={(e) => setFields({ ...fields, email: e.target.value })}
                                    style={{
                                        width: "100%", padding: "11px 14px", borderRadius: "8px",
                                        border: "1.5px solid #e8e8e8", fontSize: "0.9rem", color: "#333",
                                        outline: "none", boxSizing: "border-box",
                                        transition: "border-color 0.15s",
                                    }}
                                    onFocus={(e) => e.target.style.borderColor = "#e8431a"}
                                    onBlur={(e) => e.target.style.borderColor = "#e8e8e8"}
                                />
                            </div>

                            {/* Message */}
                            <div style={{ marginBottom: "28px" }}>
                                <label style={{ display: "block", fontSize: "0.82rem", fontWeight: 600, color: "#333", marginBottom: "6px" }}>
                                    Your Message <span style={{ color: "#e8431a" }}>*</span>
                                </label>
                                <textarea
                                    required
                                    maxLength={600}
                                    rows={5}
                                    value={fields.message}
                                    onChange={(e) => setFields({ ...fields, message: e.target.value })}
                                    style={{
                                        width: "100%", padding: "11px 14px", borderRadius: "8px",
                                        border: "1.5px solid #e8e8e8", fontSize: "0.9rem", color: "#333",
                                        outline: "none", resize: "vertical", boxSizing: "border-box",
                                        transition: "border-color 0.15s", fontFamily: "inherit",
                                    }}
                                    onFocus={(e) => e.target.style.borderColor = "#e8431a"}
                                    onBlur={(e) => e.target.style.borderColor = "#e8e8e8"}
                                />
                                <div style={{ textAlign: "right", fontSize: "0.72rem", color: "#bbb", marginTop: "4px" }}>
                                    {fields.message.length} of 600 max characters
                                </div>
                            </div>

                            <button
                                type="submit"
                                disabled={loading}
                                style={{
                                    width: "100%", padding: "13px", borderRadius: "8px",
                                    background: "#e8431a", color: "#fff", border: "none",
                                    fontSize: "0.9rem", fontWeight: 700, cursor: loading ? "not-allowed" : "pointer",
                                    opacity: loading ? 0.7 : 1,
                                    boxShadow: "0 6px 20px rgba(232,67,26,0.3)",
                                    transition: "opacity 0.15s, transform 0.15s",
                                }}
                            >
                                {loading ? "Sending..." : "Send Message"}
                            </button>
                        </form>
                    )}
                </div>
            </div>
        </div>
    );
}
