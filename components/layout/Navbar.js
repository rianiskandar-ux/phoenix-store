"use client";

import { useState } from "react";
import Link from "next/link";
import Image from "next/image";
import { usePathname } from "next/navigation";

const NAV_LINKS = [
    { href: "/", label: "Home" },
    { href: "/features", label: "Features" },
    { href: "/pricing", label: "Pricing" },
    { href: "/consultant-solutions", label: "Consultant Solutions" },
    { href: "/resources", label: "Resources" },
    { href: "/my-account", label: "My Account" },
    { href: "/contact", label: "Contact" },
];

export default function Navbar() {
    const [isOpen, setIsOpen] = useState(false);
    const pathname = usePathname();

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
        <nav style={{position: "fixed", top: 0, left: 0, right: 0, zIndex: 50, background: "white", borderBottom: "1px solid #f0f0f0", boxShadow: "0 1px 3px rgba(0,0,0,0.06)"}}>
            <div style={{maxWidth: "1200px", margin: "0 auto", padding: "0 32px", height: "60px", display: "flex", alignItems: "center", justifyContent: "space-between"}}>

                {/* Logo */}
                <Link href="/" style={{display: "flex", alignItems: "center", textDecoration: "none", flexShrink: 0}}>
                    <Image src="/logo.png" alt="Phoenix Whistleblowing" width={130} height={40} priority style={{objectFit: "contain"}} />
                </Link>

                {/* Desktop Menu */}
                <div className="nav-desktop" style={{alignItems: "center", gap: "28px"}}>
                    {NAV_LINKS.map(({ href, label }) => {
                        const active = pathname === href;
                        return (
                            <Link
                                key={href}
                                href={href}
                                style={{
                                    fontSize: "13.5px",
                                    fontWeight: active ? 600 : 400,
                                    color: active ? "#e8431a" : "#444",
                                    textDecoration: "none",
                                    whiteSpace: "nowrap",
                                    transition: "color 0.15s",
                                }}
                                onMouseEnter={e => { if (!active) e.currentTarget.style.color = "#e8431a"; }}
                                onMouseLeave={e => { if (!active) e.currentTarget.style.color = "#444"; }}
                            >
                                {label}
                            </Link>
                        );
                    })}
                </div>

                {/* Mobile hamburger */}
                <button
                    className="nav-hamburger"
                    onClick={() => setIsOpen(!isOpen)}
                    style={{flexDirection: "column", gap: "5px", padding: "8px", background: "none", border: "none", cursor: "pointer"}}
                >
                    <span style={{width: "22px", height: "2px", background: "#333", display: "block", transition: "all 0.2s", transform: isOpen ? "rotate(45deg) translate(5px, 5px)" : "none"}}></span>
                    <span style={{width: "22px", height: "2px", background: "#333", display: "block", opacity: isOpen ? 0 : 1, transition: "all 0.2s"}}></span>
                    <span style={{width: "22px", height: "2px", background: "#333", display: "block", transition: "all 0.2s", transform: isOpen ? "rotate(-45deg) translate(5px, -5px)" : "none"}}></span>
                </button>
            </div>

            {/* Mobile Menu */}
            {isOpen && (
                <div style={{background: "white", borderTop: "1px solid #f0f0f0", padding: "12px 24px 20px"}}>
                    {NAV_LINKS.map(({ href, label }) => (
                        <Link
                            key={href}
                            href={href}
                            onClick={() => setIsOpen(false)}
                            style={{display: "block", padding: "10px 0", fontSize: "14px", color: pathname === href ? "#e8431a" : "#444", fontWeight: pathname === href ? 600 : 400, textDecoration: "none", borderBottom: "1px solid #f5f5f5"}}
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
