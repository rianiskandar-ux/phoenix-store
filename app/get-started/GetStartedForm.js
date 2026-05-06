"use client";

import { useState, Suspense } from "react";
import { useSearchParams, useRouter } from "next/navigation";

const PHOENIX_DOMAINS = [
    "whistleblowing.direct",
    "speak-up.link",
    "speak-up.direct",
    "whistleblowing.link",
];

const SERVER_LOCATIONS = [
    { value: "eu-west", label: "Europe West (Switzerland)" },
    { value: "eu-central", label: "Europe Central (Germany)" },
    { value: "us-east", label: "United States East" },
    { value: "ap-southeast", label: "Asia Pacific (Singapore)" },
];

const PLAN_CONFIG = {
    free: {
        name: "Free Plan",
        hasPaymentToggle: false,
        hasDomainOptions: false,
        features: {
            "1 Dedicated website": ["Web form only", "Choice between 3 questionnaires"],
            "User Accounts": ["1 account as Manager"],
            "Language & Localisation": ["1 language only"],
            "Themes": ["Default theme (logo can be added)"],
            "Server & Security": ["Choice of server", "Choice of Phoenix web domains"],
            "Assistance": ["Self-serve knowledge base"],
        },
    },
    basic: {
        name: "Basic Plan",
        hasPaymentToggle: true,
        hasDomainOptions: false,
        features: {
            "1 Dedicated website": ["1 Email Address", "1 Phone Number", "1 Instant Messaging", "1 Postal Address"],
            "Web Form": ["Choice between 3 questionnaires"],
            "User Accounts": ["1 account as Manager"],
            "Language & Localisation": ["2 languages"],
            "Themes": ["Access to 3 themes only"],
            "Server & Security": ["Choice of server", "Choice of Phoenix web domains"],
            "Assistance": ["Self-serve knowledge base", "Ticketing (3-day response time)"],
            "Add-ons": ["Available"],
        },
    },
    premium: {
        name: "Premium Plan",
        hasPaymentToggle: true,
        hasDomainOptions: true,
        features: {
            "1 Dedicated website": ["1 Email Address", "1 Phone Number", "1 Instant Messaging", "1 Postal Address", "1 Online Chat room"],
            "Web Form": ["Customisable questionnaire"],
            "User Accounts": ["1 account as Manager", "1 account as Operator", "1 account as Agent"],
            "Language & Localisation": ["2 languages"],
            "Themes": ["Access to theme library", "Custom domain theme"],
            "Server & Security": ["Choice of server", "Choice of Phoenix web domains"],
            "Assistance": ["Ticketing (3-day response time)"],
            "Add-ons": ["Available"],
        },
    },
};

function GetStartedInner() {
    const searchParams = useSearchParams();
    const router = useRouter();
    const plan = searchParams.get("plan") || "free";
    const config = PLAN_CONFIG[plan] || PLAN_CONFIG.free;

    const [payment, setPayment] = useState("monthly");
    const [domainOption, setDomainOption] = useState("phoenix");
    const [form, setForm] = useState({
        orgName: "",
        serverLocation: "",
        subdomain: "",
        phoenixDomain: PHOENIX_DOMAINS[0],
        ownSubdomain: "",
        ownDomain: "",
        purchasedDomain: "",
    });
    const [errors, setErrors] = useState({});
    const [loading, setLoading] = useState(false);

    const planPrices = {
        free:    { monthly: "$0" },
        basic:   { monthly: "$65",  yearly: "$650" },
        premium: { monthly: "$110", yearly: "$995" },
    };
    const currentPrice = plan === "free"
        ? planPrices.free.monthly
        : planPrices[plan]?.[payment] || "";

    function handleChange(e) {
        const { name, value } = e.target;
        const cleaned = name === "subdomain" || name === "ownSubdomain"
            ? value.toLowerCase().replace(/[^a-z0-9_-]/g, "")
            : value;
        setForm((prev) => ({ ...prev, [name]: cleaned }));
        setErrors((prev) => ({ ...prev, [name]: "" }));
    }

    function validate() {
        const newErrors = {};
        if (!form.orgName.trim()) newErrors.orgName = "Organisation name is required";
        if (!form.serverLocation) newErrors.serverLocation = "Please select a server location";
        if (!config.hasDomainOptions || domainOption === "phoenix") {
            if (!form.subdomain.trim()) newErrors.subdomain = "Subdomain is required";
        }
        if (config.hasDomainOptions) {
            if (domainOption === "own" && (!form.ownSubdomain.trim() || !form.ownDomain.trim()))
                newErrors.ownDomain = "Please enter your subdomain and domain";
            if (domainOption === "purchased" && !form.purchasedDomain.trim())
                newErrors.purchasedDomain = "Please enter your purchased domain";
        }
        return newErrors;
    }

    function getDedicatedUrl() {
        if (!config.hasDomainOptions || domainOption === "phoenix")
            return form.subdomain ? `https://${form.subdomain}.${form.phoenixDomain}` : null;
        if (domainOption === "own")
            return form.ownSubdomain && form.ownDomain ? `https://${form.ownSubdomain}.${form.ownDomain}` : null;
        if (domainOption === "purchased")
            return form.purchasedDomain ? `https://${form.purchasedDomain}` : null;
        return null;
    }

    async function handleSubmit(e) {
        e.preventDefault();
        const newErrors = validate();
        if (Object.keys(newErrors).length > 0) { setErrors(newErrors); return; }
        setLoading(true);

        sessionStorage.setItem("phoenix_onboarding", JSON.stringify({
            ...form, plan, payment, domainOption, dedicatedUrl: getDedicatedUrl(),
        }));

        const PRODUCT_IDS = {
            free: 30596,
            basic: { monthly: 61, yearly: 62 },
            premium: { monthly: 78, yearly: 79 },
        };
        const productId = plan === "free" ? PRODUCT_IDS.free : PRODUCT_IDS[plan]?.[payment];
        const base = process.env.NEXT_PUBLIC_WC_URL.replace(/\/$/, "");
        router.push(`${base}/checkout/?add-to-cart=${productId}`);
    }

    const inputClass = "w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-phoenix-text bg-white focus:outline-none focus:ring-2 focus:ring-phoenix-orange/20 focus:border-phoenix-orange transition-all placeholder:text-gray-400";
    const labelClass = "block text-sm font-medium text-phoenix-text mb-1";

    return (
        <div className="min-h-screen bg-gray-50">
            {/* Page banner */}
            <div style={{background:"linear-gradient(135deg,#fde8e2 0%,#f3e8f8 100%)", paddingTop:"80px", paddingBottom:"40px", textAlign:"center"}}>
                <h1 className="text-2xl font-bold text-phoenix-text">Subscription Page</h1>
            </div>

            <div style={{maxWidth:"1100px", margin:"0 auto", padding:"40px 32px 60px"}}>
                <div className="flex flex-col-reverse md:grid md:grid-cols-[260px_1fr] gap-8 items-start w-full">

                    {/* LEFT: Features */}
                    <div className="w-full bg-white rounded-xl border border-gray-100 shadow-sm" style={{padding:"24px"}}>
                        <h2 className="text-base font-bold text-phoenix-text" style={{marginBottom:"16px"}}>Features</h2>
                        {Object.entries(config.features).map(([category, items]) => (
                            <div key={category} style={{marginBottom:"14px"}}>
                                <p className="text-xs font-bold text-phoenix-text" style={{marginBottom:"4px"}}>{category}</p>
                                <ul style={{listStyle:"none", padding:0, margin:0}}>
                                    {items.map((item) => (
                                        <li key={item} className="text-xs text-gray-500" style={{display:"flex", alignItems:"flex-start", gap:"6px", marginBottom:"2px"}}>
                                            <span className="text-phoenix-orange" style={{flexShrink:0}}>✓</span>
                                            {item}
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        ))}
                    </div>

                    {/* RIGHT: Form */}
                    <div className="w-full">
                        <div className="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

                            {/* Plan + price header */}
                            <div style={{padding:"20px 24px 16px", borderBottom:"1px solid #f0f0f0", display:"flex", alignItems:"flex-start", justifyContent:"space-between", gap:"16px"}}>
                                <p style={{fontSize:"18px", fontWeight:700, color:"#333", margin:0}}>{config.name}</p>
                                <div style={{textAlign:"right", flexShrink:0}}>
                                    <p style={{fontSize:"22px", fontWeight:700, color:"#e8431a", margin:0}}>{currentPrice}</p>
                                    <p style={{fontSize:"11px", color:"#999", margin:"2px 0 0"}}>
                                        {plan === "free" ? "free for 6 months" : payment === "monthly" ? "per month" : "per year, billed annually"}
                                    </p>
                                </div>
                            </div>

                            {/* Billing toggle */}
                            {config.hasPaymentToggle && (
                                <div style={{padding:"12px 24px", borderBottom:"1px solid #f0f0f0", background:"#fafafa", display:"flex", alignItems:"center", justifyContent:"space-between", gap:"16px"}}>
                                    <p style={{fontSize:"11px", fontWeight:600, color:"#999", textTransform:"uppercase", letterSpacing:"0.5px", margin:0}}>Billing cycle</p>
                                    <div style={{display:"flex", background:"#e5e7eb", borderRadius:"8px", padding:"3px", gap:"3px"}}>
                                        {[{value:"monthly",label:"Monthly"},{value:"yearly",label:"Yearly",badge:"Save 17%"}].map((opt) => (
                                            <button key={opt.value} type="button" onClick={() => setPayment(opt.value)}
                                                style={{padding:"5px 14px", borderRadius:"6px", fontSize:"12px", fontWeight:600, border:"none", cursor:"pointer", display:"flex", alignItems:"center", gap:"6px", transition:"all 0.15s",
                                                    background: payment === opt.value ? "white" : "transparent",
                                                    color: payment === opt.value ? "#e8431a" : "#888",
                                                    boxShadow: payment === opt.value ? "0 1px 3px rgba(0,0,0,0.1)" : "none",
                                                }}
                                            >
                                                {opt.label}
                                                {opt.badge && (
                                                    <span style={{fontSize:"10px", fontWeight:700, padding:"1px 6px", borderRadius:"99px", background: payment === opt.value ? "#e8431a" : "#ccc", color:"white"}}>
                                                        {opt.badge}
                                                    </span>
                                                )}
                                            </button>
                                        ))}
                                    </div>
                                </div>
                            )}

                            <div style={{padding:"28px 24px"}}>
                                <p className="text-xs text-gray-400" style={{marginBottom:"20px"}}>
                                    Fields marked with <span className="text-phoenix-orange font-semibold">*</span> are required.
                                </p>

                                <form onSubmit={handleSubmit} className="flex flex-col gap-5">

                                    {/* 1. Organisation */}
                                    <div>
                                        <div className="flex items-center gap-2 mb-3">
                                            <div className="w-6 h-6 rounded-full bg-phoenix-orange/10 text-phoenix-orange flex items-center justify-center text-xs font-bold flex-shrink-0">1</div>
                                            <h2 className="text-sm font-semibold text-phoenix-text uppercase tracking-wide">Organisation</h2>
                                        </div>
                                        <label className={labelClass}>Full name of your organisation <span className="text-phoenix-orange">*</span></label>
                                        <input type="text" name="orgName" value={form.orgName} onChange={handleChange} placeholder="E.g. Acme Corporation" className={inputClass} />
                                        {errors.orgName && <p className="text-xs text-red-500 mt-1">{errors.orgName}</p>}
                                    </div>

                                    <hr className="border-gray-100" />

                                    {/* 2. Server Location */}
                                    <div>
                                        <div className="flex items-center gap-2 mb-3">
                                            <div className="w-6 h-6 rounded-full bg-phoenix-orange/10 text-phoenix-orange flex items-center justify-center text-xs font-bold flex-shrink-0">2</div>
                                            <h2 className="text-sm font-semibold text-phoenix-text uppercase tracking-wide">Server Location</h2>
                                        </div>
                                        <label className={labelClass}>Choose your server location <span className="text-phoenix-orange">*</span></label>
                                        <select name="serverLocation" value={form.serverLocation} onChange={handleChange} className={inputClass}>
                                            <option value="">Select the region</option>
                                            {SERVER_LOCATIONS.map((loc) => <option key={loc.value} value={loc.value}>{loc.label}</option>)}
                                        </select>
                                        {errors.serverLocation && <p className="text-xs text-red-500 mt-1">{errors.serverLocation}</p>}
                                    </div>

                                    <hr className="border-gray-100" />

                                    {/* 3. Website Address */}
                                    <div>
                                        <div className="flex items-center gap-2 mb-3">
                                            <div className="w-6 h-6 rounded-full bg-phoenix-orange/10 text-phoenix-orange flex items-center justify-center text-xs font-bold flex-shrink-0">3</div>
                                            <h2 className="text-sm font-semibold text-phoenix-text uppercase tracking-wide">Website Address</h2>
                                        </div>

                                        {config.hasDomainOptions ? (
                                            <div className="flex flex-col" style={{gap:"12px"}}>
                                                <p className="text-xs text-gray-500">
                                                    Choose the address for your Whistleblowing Application.{" "}
                                                    <span className="text-phoenix-orange font-medium">Cannot be changed after checkout.</span>
                                                </p>
                                                {[
                                                    {value:"phoenix", title:"Use a Phoenix domain", badge:"Recommended", desc:"Subdomain + one of Phoenix's domains", example:"acme.speak-up.link"},
                                                    {value:"own", title:"Use your own domain", badge:null, desc:"A subdomain on a domain you already own", example:"whistleblowing.acme.com"},
                                                    {value:"purchased", title:"Use a purchased domain", badge:null, desc:"A domain purchased specifically for this app", example:"acme-whistleblowing.com"},
                                                ].map((opt) => (
                                                    <label key={opt.value}
                                                        className={`flex items-start cursor-pointer rounded-lg border-2 transition-all ${domainOption===opt.value ? "border-phoenix-orange bg-orange-50" : "border-gray-200 hover:border-gray-300 bg-white"}`}
                                                        style={{padding:"12px 14px", gap:"12px"}}
                                                    >
                                                        <input type="radio" name="domainOption" value={opt.value} checked={domainOption===opt.value} onChange={()=>setDomainOption(opt.value)} className="mt-0.5 accent-phoenix-orange flex-shrink-0" />
                                                        <div>
                                                            <div className="flex items-center" style={{gap:"6px", marginBottom:"2px"}}>
                                                                <span className="text-sm font-semibold text-phoenix-text">{opt.title}</span>
                                                                {opt.badge && <span className="bg-phoenix-orange text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full uppercase tracking-wide">{opt.badge}</span>}
                                                            </div>
                                                            <p className="text-xs text-gray-500">{opt.desc} — e.g. <span className="text-phoenix-orange">{opt.example}</span></p>
                                                        </div>
                                                    </label>
                                                ))}
                                                {domainOption === "phoenix" && (
                                                    <div className="flex" style={{gap:"12px"}}>
                                                        <div className="flex-1">
                                                            <label className={labelClass}>Subdomain <span className="text-phoenix-orange">*</span></label>
                                                            <input type="text" name="subdomain" value={form.subdomain} onChange={handleChange} placeholder="E.g. acme" className={inputClass} />
                                                        </div>
                                                        <div className="flex-1">
                                                            <label className={labelClass}>Phoenix domain <span className="text-phoenix-orange">*</span></label>
                                                            <select name="phoenixDomain" value={form.phoenixDomain} onChange={handleChange} className={inputClass}>
                                                                {PHOENIX_DOMAINS.map((d) => <option key={d} value={d}>{d}</option>)}
                                                            </select>
                                                        </div>
                                                    </div>
                                                )}
                                                {domainOption === "own" && (
                                                    <div className="flex" style={{gap:"12px"}}>
                                                        <div className="flex-1">
                                                            <label className={labelClass}>Your subdomain <span className="text-phoenix-orange">*</span></label>
                                                            <input type="text" name="ownSubdomain" value={form.ownSubdomain} onChange={handleChange} placeholder="E.g. whistleblowing" className={inputClass} />
                                                        </div>
                                                        <div className="flex-1">
                                                            <label className={labelClass}>Your domain <span className="text-phoenix-orange">*</span></label>
                                                            <input type="text" name="ownDomain" value={form.ownDomain} onChange={handleChange} placeholder="E.g. acme.com" className={inputClass} />
                                                        </div>
                                                    </div>
                                                )}
                                                {domainOption === "purchased" && (
                                                    <div>
                                                        <label className={labelClass}>Your domain <span className="text-phoenix-orange">*</span></label>
                                                        <input type="text" name="purchasedDomain" value={form.purchasedDomain} onChange={handleChange} placeholder="E.g. acme-whistleblowing.com" className={inputClass} />
                                                        {errors.purchasedDomain && <p className="text-xs text-red-500 mt-1">{errors.purchasedDomain}</p>}
                                                    </div>
                                                )}
                                            </div>
                                        ) : (
                                            <div className="flex flex-col" style={{gap:"12px"}}>
                                                <div className="flex items-start bg-blue-50 border border-blue-100 rounded-lg" style={{gap:"10px", padding:"12px"}}>
                                                    <div className="bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0" style={{width:"20px", height:"20px", marginTop:"1px"}}>i</div>
                                                    <div>
                                                        <p className="text-sm font-semibold text-phoenix-text" style={{marginBottom:"4px"}}>Your dedicated address</p>
                                                        <p className="text-xs text-gray-600">
                                                            Choose a subdomain (e.g. <strong>acme</strong>) + one of Phoenix's domains (e.g. <strong>speak-up.link</strong>) → <strong>https://acme.speak-up.link</strong>
                                                        </p>
                                                        <p className="text-xs text-phoenix-orange font-medium" style={{marginTop:"4px"}}>Cannot be changed after checkout.</p>
                                                    </div>
                                                </div>
                                                <div className="flex" style={{gap:"12px"}}>
                                                    <div className="flex-1">
                                                        <label className={labelClass}>Subdomain <span className="text-phoenix-orange">*</span></label>
                                                        <input type="text" name="subdomain" value={form.subdomain} onChange={handleChange} placeholder="E.g. acme" className={inputClass} />
                                                        {errors.subdomain && <p className="text-xs text-red-500 mt-1">{errors.subdomain}</p>}
                                                    </div>
                                                    <div className="flex-1">
                                                        <label className={labelClass}>Phoenix domain <span className="text-phoenix-orange">*</span></label>
                                                        <select name="phoenixDomain" value={form.phoenixDomain} onChange={handleChange} className={inputClass}>
                                                            {PHOENIX_DOMAINS.map((d) => <option key={d} value={d}>{d}</option>)}
                                                        </select>
                                                    </div>
                                                </div>
                                                <p className="text-xs text-gray-400">Allowed: a–z, 0–9, hyphen (-), underscore (_)</p>
                                            </div>
                                        )}

                                        {getDedicatedUrl() && (
                                            <div className="flex items-center border border-phoenix-orange/20 rounded-lg" style={{gap:"10px", padding:"10px 14px", backgroundColor:"rgba(232,67,26,0.04)", marginTop:"8px"}}>
                                                <div className="rounded-full bg-phoenix-orange flex-shrink-0" style={{width:"8px", height:"8px"}}></div>
                                                <div>
                                                    <p className="text-xs text-gray-400">Your address will be:</p>
                                                    <p className="text-sm font-semibold text-phoenix-orange">{getDedicatedUrl()}</p>
                                                </div>
                                            </div>
                                        )}
                                    </div>

                                    <hr className="border-gray-100" />

                                    {/* Submit */}
                                    <div className="flex items-center justify-between">
                                        <p className="text-xs text-gray-400">Redirected to secure checkout.</p>
                                        <button type="submit" disabled={loading}
                                            className="bg-phoenix-orange text-white rounded-lg font-bold text-sm hover:opacity-90 transition-opacity disabled:opacity-50 flex items-center"
                                            style={{padding:"10px 24px", gap:"8px"}}
                                        >
                                            {loading ? (
                                                <>
                                                    <svg className="animate-spin" style={{width:"14px", height:"14px"}} fill="none" viewBox="0 0 24 24">
                                                        <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"/>
                                                        <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                                    </svg>
                                                    Processing...
                                                </>
                                            ) : "Continue to Checkout →"}
                                        </button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    );
}

export default function GetStartedForm() {
    return (
        <Suspense fallback={<div style={{minHeight:"100vh", display:"flex", alignItems:"center", justifyContent:"center"}}><p className="text-gray-400 text-sm">Loading...</p></div>}>
            <GetStartedInner />
        </Suspense>
    );
}
