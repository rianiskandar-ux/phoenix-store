import Link from "next/link";
import Image from "next/image";

export default function FooterSection() {
    return (
        <footer style={{ background: "#f8f9fb", borderTop: "3px solid #e8431a", boxShadow: "0 -8px 32px rgba(0,0,0,0.06)", padding: "60px 60px 40px", position: "relative", overflow: "hidden" }}>
            {/* ORNAMEN 3 — peach half circle top-right */}
            <div style={{ position: "absolute", top: "-20px", right: "-20px", pointerEvents: "none", opacity: 0.4 }}>
                <Image src="/assets/ORNAMEN 3.png" alt="" width={240} height={150} style={{ objectFit: "contain", transform: "rotate(180deg)" }} />
            </div>
            {/* line.png — orange lines top-left near brand */}
            <div style={{ position: "absolute", top: "48px", left: "48px", pointerEvents: "none", opacity: 0.6 }}>
                <Image src="/assets/line.png" alt="" width={64} height={32} style={{ objectFit: "contain" }} />
            </div>
            {/* ornamen black and orange — mid-right subtle */}
            <div style={{ position: "absolute", top: "50%", right: "-20px", transform: "translateY(-50%)", pointerEvents: "none", opacity: 0.12 }}>
                <Image src="/assets/ornamen black and orange.png" alt="" width={120} height={120} style={{ objectFit: "contain" }} />
            </div>
            {/* ORNAMEN 2 — grey half circle bottom-left, flipped to hug corner */}
            <div style={{ position: "absolute", bottom: 0, left: 0, pointerEvents: "none", opacity: 0.35 }}>
                <Image src="/assets/ORNAMEN 2.png" alt="" width={200} height={120} style={{ objectFit: "contain", transform: "rotate(180deg)" }} />
            </div>
            <div style={{ maxWidth: "1200px", margin: "0 auto" }}>
                <div style={{ display: "flex", gap: "60px", flexWrap: "wrap", marginBottom: "48px" }}>
                    {/* Brand */}
                    <div style={{ flex: "2", minWidth: "220px" }}>
                        <div style={{ fontSize: "1.3rem", fontWeight: 900, color: "#111", marginBottom: "12px" }}>
                            <span style={{ color: "#e8431a" }}>Phoenix</span> Whistleblowing
                        </div>
                        <p style={{ color: "#888", fontSize: "0.875rem", lineHeight: 1.7, maxWidth: "280px" }}>
                            Inspiring Integrity, Guiding Growth. Secure whistleblowing software for organizations that care.
                        </p>
                        <a
                            href="https://www.youtube.com/channel/UCIYXEO_W_OqhMnBFc8Tabmw"
                            target="_blank" rel="noopener noreferrer"
                            style={{ display: "inline-flex", alignItems: "center", gap: "6px", marginTop: "16px", color: "#aaa", textDecoration: "none", fontSize: "0.875rem" }}
                        >
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#e8431a" strokeWidth="2">
                                <circle cx="12" cy="12" r="10" />
                                <polygon points="10 8 16 12 10 16 10 8" fill="#e8431a" stroke="none" />
                            </svg>
                            YouTube
                        </a>
                    </div>

                    {/* Product */}
                    <div style={{ minWidth: "140px" }}>
                        <div style={{ color: "#111", fontWeight: 700, marginBottom: "16px", fontSize: "0.75rem", letterSpacing: "0.08em" }}>PRODUCT</div>
                        {[
                            { label: "Features", href: "/#features" },
                            { label: "Pricing", href: "/get-started" },
                            { label: "How it works", href: "/#workflow" },
                            { label: "Demo", href: "/#video" },
                        ].map(l => (
                            <div key={l.label} style={{ marginBottom: "10px" }}>
                                <Link href={l.href} style={{ color: "#888", textDecoration: "none", fontSize: "0.875rem" }}>
                                    {l.label}
                                </Link>
                            </div>
                        ))}
                    </div>

                    {/* Company */}
                    <div style={{ minWidth: "140px" }}>
                        <div style={{ color: "#111", fontWeight: 700, marginBottom: "16px", fontSize: "0.75rem", letterSpacing: "0.08em" }}>COMPANY</div>
                        {[
                            { label: "About", href: "/about" },
                            { label: "Blog", href: "/blog" },
                            { label: "Contact", href: "/contact" },
                        ].map(l => (
                            <div key={l.label} style={{ marginBottom: "10px" }}>
                                <Link href={l.href} style={{ color: "#888", textDecoration: "none", fontSize: "0.875rem" }}>
                                    {l.label}
                                </Link>
                            </div>
                        ))}
                    </div>

                    {/* Legal */}
                    <div style={{ minWidth: "140px" }}>
                        <div style={{ color: "#111", fontWeight: 700, marginBottom: "16px", fontSize: "0.75rem", letterSpacing: "0.08em" }}>LEGAL</div>
                        {[
                            { label: "Privacy Policy", href: "/privacy" },
                            { label: "Terms of Service", href: "/terms" },
                            { label: "GDPR", href: "/gdpr" },
                        ].map(l => (
                            <div key={l.label} style={{ marginBottom: "10px" }}>
                                <Link href={l.href} style={{ color: "#888", textDecoration: "none", fontSize: "0.875rem" }}>
                                    {l.label}
                                </Link>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Compliance trust strip */}
                <div style={{
                    display: "flex", alignItems: "center", gap: "8px",
                    flexWrap: "wrap", marginBottom: "32px",
                    padding: "16px 20px",
                    background: "#fff", borderRadius: "10px",
                    border: "1px solid #ebebeb",
                }}>
                    <span style={{ fontSize: "0.68rem", color: "#aaa", fontWeight: 600, letterSpacing: "0.05em", textTransform: "uppercase", marginRight: "4px" }}>Compliant with</span>
                    {["GDPR", "FADP", "EU Dir. 2019/1937", "Swiss DSG", "ISO 27001"].map(b => (
                        <span key={b} style={{
                            fontSize: "0.68rem", fontWeight: 700, color: "#555",
                            background: "#f4f4f4", border: "1px solid #e8e8e8",
                            borderRadius: "4px", padding: "4px 10px",
                            letterSpacing: "0.03em",
                        }}>{b}</span>
                    ))}
                </div>

                {/* Bottom bar */}
                <div style={{ borderTop: "1px solid #ebebeb", paddingTop: "24px", display: "flex", justifyContent: "space-between", flexWrap: "wrap", gap: "8px" }}>
                    <p style={{ color: "#bbb", fontSize: "0.8rem", margin: 0 }}>
                        © {new Date().getFullYear()} Phoenix Whistleblowing Software. All rights reserved.
                    </p>
                    <p style={{ color: "#bbb", fontSize: "0.8rem", margin: 0 }}>
                        Built with integrity.
                    </p>
                </div>
            </div>
        </footer>
    );
}
