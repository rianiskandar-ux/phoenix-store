"use client";

import React from "react";
import Link from "next/link";
import Image from "next/image";
import { useState, useCallback, useRef } from "react";
import { ComposableMap, Geographies, Geography, ZoomableGroup, Marker } from "react-simple-maps";
import { geoCentroid, geoBounds } from "d3-geo";

const waveKeyframes = `
@keyframes mapFadeIn {
    from { opacity: 0; transform: scale(0.98); }
    to   { opacity: 1; transform: scale(1); }
}
@keyframes tooltipIn {
    from { opacity: 0; transform: translateY(4px) scale(0.95); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
@keyframes geoFloat {
    0%   { transform: scale(1.05) translateY(-2px); }
    35%  { transform: scale(1.08) translateY(-6px); }
    70%  { transform: scale(1.06) translateY(-4px); }
    100% { transform: scale(1.05) translateY(-2px); }
}
.rsm-geo {
    transform-box: fill-box;
    transform-origin: center;
    cursor: pointer;
    transition: fill 0.18s ease, filter 0.22s ease;
}
.rsm-geo:hover {
    animation: geoFloat 1.8s cubic-bezier(0.45, 0.05, 0.55, 0.95) infinite;
    filter: drop-shadow(0 5px 12px rgba(80, 130, 180, 0.5));
}
@keyframes waveEntrance1 {
    0%   { transform: translateX(18%) translateY(12%) scale(0.88); opacity: 0; }
    15%  { transform: translateX(10%) translateY(7%) scale(0.93); opacity: 0.5; }
    35%  { transform: translateX(-4%) translateY(-3%) scale(1.04); opacity: 1; }
    55%  { transform: translateX(2%) translateY(2%) scale(0.99); opacity: 1; }
    72%  { transform: translateX(-2%) translateY(-1%) scale(1.02); opacity: 1; }
    88%  { transform: translateX(1%) translateY(0.5%) scale(1.005); opacity: 1; }
    100% { transform: translateX(0) translateY(0) scale(1); opacity: 1; }
}
@keyframes waveEntrance2 {
    0%   { transform: translateX(24%) translateY(16%) scale(0.82); opacity: 0; }
    20%  { transform: translateX(14%) translateY(10%) scale(0.9); opacity: 0.4; }
    40%  { transform: translateX(-3%) translateY(-4%) scale(1.06); opacity: 1; }
    58%  { transform: translateX(3%) translateY(3%) scale(0.98); opacity: 1; }
    75%  { transform: translateX(-1.5%) translateY(-1.5%) scale(1.015); opacity: 1; }
    90%  { transform: translateX(0.8%) translateY(0.5%) scale(1.003); opacity: 1; }
    100% { transform: translateX(0) translateY(0) scale(1); opacity: 1; }
}
.rsm-puzzle {
    transform-box: fill-box;
    transform-origin: center;
    cursor: pointer;
    filter: drop-shadow(1.5px 2.5px 1px rgba(0,0,0,0.28));
}
.rsm-puzzle:hover {
    animation: geoFloat 1.8s cubic-bezier(0.45, 0.05, 0.55, 0.95) infinite;
    filter: drop-shadow(0 7px 16px rgba(0,0,0,0.42)) brightness(1.08);
}
`;

export default function HeroSection() {
    const [mouse, setMouse] = useState({ x: 0, y: 0 });

    const handleMouseMove = useCallback((e) => {
        const rect = e.currentTarget.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width - 0.5) * 2;
        const y = ((e.clientY - rect.top) / rect.height - 0.5) * 2;
        setMouse({ x, y });
    }, []);

    const p = (strength) => ({
        transform: `translate(${mouse.x * strength}px, ${mouse.y * strength}px)`,
        transition: "transform 0.12s ease-out",
    });

    return (
        <div
            onMouseMove={handleMouseMove}
            style={{
                position: "relative", width: "100%",
                background: "#ffffff",
                overflow: "hidden",
            }}>
            <style>{waveKeyframes}</style>

            {/* Wave 2-layer container — strict right strip */}
            <div style={{
                position: "absolute", top: 0, right: 0, bottom: 0,
                width: "18%",
                overflow: "hidden",
                pointerEvents: "none", zIndex: 0,
            }}>
                {/* Layer 1 — back, lower opacity, shifted up-left */}
                <svg
                    viewBox="0 0 848 700"
                    preserveAspectRatio="xMaxYMid slice"
                    style={{
                        position: "absolute", top: 0, left: 0,
                        width: "160%", height: "100%",
                        animation: "waveEntrance1 2.8s cubic-bezier(0.22,1,0.36,1) 1 forwards",
                        opacity: 0,
                        transformOrigin: "right center",
                    }}
                >
                    <path
                        fill="#e8431a"
                        fillOpacity="0.10"
                        d="M847.57,34.983L847.57,44.946L847.57,54.696L847.57,64.248L847.57,73.616L847.57,82.816L847.57,91.859L847.57,100.763L847.57,109.541L847.57,118.205L847.57,126.774L847.57,135.064L847.57,135.258L847.57,143.675L847.57,152.038L847.57,160.36L847.57,168.656L847.57,176.942L847.57,185.231L847.57,193.539L847.57,201.878L847.57,210.262L847.57,218.709L847.57,227.231L847.57,235.842L847.57,244.558L847.57,253.392L847.57,262.361L847.57,271.475L847.57,280.751L847.57,290.203L847.57,299.846L847.57,309.694L847.57,319.761L847.57,330.061L847.57,335.225L847.57,340.61L847.57,351.421L847.57,362.509L847.57,373.888L847.57,385.573L847.57,397.578L847.57,409.918L847.57,422.606L847.57,435.657L847.57,449.086L847.57,462.907L847.57,477.135L847.57,491.783L843.119,500.811L833.062,502.152L823.168,503.472L813.431,504.771L809.877,505.245L803.843,506.05L794.396,507.31L785.082,508.553L775.893,509.779L766.823,510.989L757.864,512.183L749.007,513.365L740.246,514.534L731.573,515.691L722.979,516.837L714.458,517.974L706.002,519.102L697.603,520.222L689.253,521.336L680.946,522.445L672.672,523.548L664.426,524.648L656.198,525.746L647.982,526.842L639.77,527.938L631.554,529.034L623.326,530.131L615.08,531.231L611.474,531.712L606.806,532.335L598.499,533.443L590.149,534.557L581.75,535.677L573.294,536.805L564.773,537.942L556.179,539.088L547.506,540.246L538.745,541.414L529.888,542.596L520.929,543.791L511.859,545.001L502.671,546.227L493.357,547.469L483.909,548.729L474.321,550.008L464.584,551.307L454.69,552.627L444.633,553.969L434.404,555.333L423.996,556.722L413.401,558.135L413.071,558.179L402.612,559.574L391.621,561.041L380.42,562.535L369.002,564.058L357.359,565.611L345.483,567.195L333.367,568.812L321.004,570.461L308.385,572.144L295.502,573.863L282.35,575.617L268.919,577.409L255.202,579.239L241.192,581.108L226.88,583.017L214.668,584.646L212.26,584.967L203.821,586.149L206.871,585.301L210.673,583.533L215.183,580.903L220.353,577.473L226.137,573.303L232.489,568.451L239.362,562.978L246.71,556.945L254.486,550.411L262.645,543.435L271.14,536.079L279.924,528.402L288.951,520.462L298.176,512.323L307.55,504.043L317.029,495.681L326.566,487.298L336.113,478.954L345.626,470.709L350.012,466.949L355.058,462.622L364.362,454.755L373.491,447.165L382.4,439.915L391.043,433.063L399.372,426.67L407.342,420.795L415.273,414.768L422.276,407.753L428.159,399.988L433.082,391.593L437.208,382.691L440.701,373.4L443.721,363.843L446.432,354.14L448.995,344.412L451.573,334.779L454.328,325.363L457.423,316.283L460.473,308.971L461.019,307.662L465.279,299.619L470.365,292.275L476.439,285.751L483.664,280.168L492.203,275.647L502.216,272.309L513.866,270.273L527.317,269.661L534.861,269.5L542.253,268.831L549.589,267.663L556.847,266.006L564.002,263.87L571.035,261.262L577.921,258.196L584.638,254.676L591.165,250.714L597.477,246.319L603.554,241.5L609.372,236.267L614.909,230.628L620.143,224.593L625.051,218.171L626.355,216.225L629.61,211.371L633.798,204.204L637.593,196.677L640.972,188.801L643.913,180.584L646.393,172.036L648.389,163.167L649.88,153.985L650.843,144.499L651.255,134.72L651.094,124.655L650.337,114.316L648.962,103.71L646.946,92.847L644.267,81.737L640.903,70.388L636.831,58.81L632.028,47.013L626.472,35.005L621.874,26.138L620.141,22.796L613.011,10.395L605.062,-2.188L596.269,-14.945L586.612,-27.866L576.066,-40.942L585.444,-59.105L598.424,-76.32L611.059,-91.535L623.348,-104.83L635.293,-116.286L646.896,-125.981L652.787,-130.173L658.158,-133.996L669.08,-140.41L679.663,-145.303L689.909,-148.754L699.819,-150.844L709.394,-151.653L718.636,-151.259L727.547,-149.742L736.126,-147.183L744.376,-143.661L752.298,-139.256L759.894,-134.047L767.163,-128.114L774.109,-121.537L780.732,-114.395L787.034,-106.769L793.015,-98.738L798.677,-90.381L804.022,-81.779L809.051,-73.011L811.532,-68.349L813.764,-64.156L818.164,-55.296L822.252,-46.508L826.029,-37.874L829.496,-29.472L832.655,-21.382L835.506,-13.685L838.052,-6.459L840.294,0.214L842.232,6.256L843.869,11.588L845.205,16.129L846.241,19.8L846.98,22.521L847.423,24.212L847.57,24.793Z"
                    />
                </svg>
                {/* Layer 2 — front, higher opacity, shifted down-right */}
                <svg
                    viewBox="0 0 848 700"
                    preserveAspectRatio="xMaxYMid slice"
                    style={{
                        position: "absolute", top: "20%", left: "20%",
                        width: "140%", height: "100%",
                        animation: "waveEntrance2 3.4s cubic-bezier(0.22,1,0.36,1) 1 forwards",
                        animationDelay: "0.25s",
                        opacity: 0,
                        transformOrigin: "right center",
                    }}
                >
                    <path
                        fill="#e8431a"
                        fillOpacity="0.18"
                        d="M847.57,34.983L847.57,44.946L847.57,54.696L847.57,64.248L847.57,73.616L847.57,82.816L847.57,91.859L847.57,100.763L847.57,109.541L847.57,118.205L847.57,126.774L847.57,135.064L847.57,135.258L847.57,143.675L847.57,152.038L847.57,160.36L847.57,168.656L847.57,176.942L847.57,185.231L847.57,193.539L847.57,201.878L847.57,210.262L847.57,218.709L847.57,227.231L847.57,235.842L847.57,244.558L847.57,253.392L847.57,262.361L847.57,271.475L847.57,280.751L847.57,290.203L847.57,299.846L847.57,309.694L847.57,319.761L847.57,330.061L847.57,335.225L847.57,340.61L847.57,351.421L847.57,362.509L847.57,373.888L847.57,385.573L847.57,397.578L847.57,409.918L847.57,422.606L847.57,435.657L847.57,449.086L847.57,462.907L847.57,477.135L847.57,491.783L843.119,500.811L833.062,502.152L823.168,503.472L813.431,504.771L809.877,505.245L803.843,506.05L794.396,507.31L785.082,508.553L775.893,509.779L766.823,510.989L757.864,512.183L749.007,513.365L740.246,514.534L731.573,515.691L722.979,516.837L714.458,517.974L706.002,519.102L697.603,520.222L689.253,521.336L680.946,522.445L672.672,523.548L664.426,524.648L656.198,525.746L647.982,526.842L639.77,527.938L631.554,529.034L623.326,530.131L615.08,531.231L611.474,531.712L606.806,532.335L598.499,533.443L590.149,534.557L581.75,535.677L573.294,536.805L564.773,537.942L556.179,539.088L547.506,540.246L538.745,541.414L529.888,542.596L520.929,543.791L511.859,545.001L502.671,546.227L493.357,547.469L483.909,548.729L474.321,550.008L464.584,551.307L454.69,552.627L444.633,553.969L434.404,555.333L423.996,556.722L413.401,558.135L413.071,558.179L402.612,559.574L391.621,561.041L380.42,562.535L369.002,564.058L357.359,565.611L345.483,567.195L333.367,568.812L321.004,570.461L308.385,572.144L295.502,573.863L282.35,575.617L268.919,577.409L255.202,579.239L241.192,581.108L226.88,583.017L214.668,584.646L212.26,584.967L203.821,586.149L206.871,585.301L210.673,583.533L215.183,580.903L220.353,577.473L226.137,573.303L232.489,568.451L239.362,562.978L246.71,556.945L254.486,550.411L262.645,543.435L271.14,536.079L279.924,528.402L288.951,520.462L298.176,512.323L307.55,504.043L317.029,495.681L326.566,487.298L336.113,478.954L345.626,470.709L350.012,466.949L355.058,462.622L364.362,454.755L373.491,447.165L382.4,439.915L391.043,433.063L399.372,426.67L407.342,420.795L415.273,414.768L422.276,407.753L428.159,399.988L433.082,391.593L437.208,382.691L440.701,373.4L443.721,363.843L446.432,354.14L448.995,344.412L451.573,334.779L454.328,325.363L457.423,316.283L460.473,308.971L461.019,307.662L465.279,299.619L470.365,292.275L476.439,285.751L483.664,280.168L492.203,275.647L502.216,272.309L513.866,270.273L527.317,269.661L534.861,269.5L542.253,268.831L549.589,267.663L556.847,266.006L564.002,263.87L571.035,261.262L577.921,258.196L584.638,254.676L591.165,250.714L597.477,246.319L603.554,241.5L609.372,236.267L614.909,230.628L620.143,224.593L625.051,218.171L626.355,216.225L629.61,211.371L633.798,204.204L637.593,196.677L640.972,188.801L643.913,180.584L646.393,172.036L648.389,163.167L649.88,153.985L650.843,144.499L651.255,134.72L651.094,124.655L650.337,114.316L648.962,103.71L646.946,92.847L644.267,81.737L640.903,70.388L636.831,58.81L632.028,47.013L626.472,35.005L621.874,26.138L620.141,22.796L613.011,10.395L605.062,-2.188L596.269,-14.945L586.612,-27.866L576.066,-40.942L585.444,-59.105L598.424,-76.32L611.059,-91.535L623.348,-104.83L635.293,-116.286L646.896,-125.981L652.787,-130.173L658.158,-133.996L669.08,-140.41L679.663,-145.303L689.909,-148.754L699.819,-150.844L709.394,-151.653L718.636,-151.259L727.547,-149.742L736.126,-147.183L744.376,-143.661L752.298,-139.256L759.894,-134.047L767.163,-128.114L774.109,-121.537L780.732,-114.395L787.034,-106.769L793.015,-98.738L798.677,-90.381L804.022,-81.779L809.051,-73.011L811.532,-68.349L813.764,-64.156L818.164,-55.296L822.252,-46.508L826.029,-37.874L829.496,-29.472L832.655,-21.382L835.506,-13.685L838.052,-6.459L840.294,0.214L842.232,6.256L843.869,11.588L845.205,16.129L846.241,19.8L846.98,22.521L847.423,24.212L847.57,24.793Z"
                    />
                </svg>
            </div>
            {/* line.png */}
            <div style={{ position: "absolute", top: "18%", left: "3%", pointerEvents: "none", opacity: 0.45, transform: `rotate(-15deg) translate(${mouse.x * 6}px, ${mouse.y * 6}px)` }}>
                <Image src="/assets/line.png" alt="" width={72} height={36} style={{ objectFit: "contain" }} />
            </div>
            {/* ornamen black and orange */}
            <div style={{ position: "absolute", bottom: "60px", left: "-28px", pointerEvents: "none", opacity: 0.15, transform: `rotate(12deg) scale(1.1) translate(${mouse.x * 10}px, ${mouse.y * 10}px)` }}>
                <Image src="/assets/ornamen black and orange.png" alt="" width={110} height={110} style={{ objectFit: "contain" }} />
            </div>
            {/* ORNAMEN 3 peach */}
            <div style={{ position: "absolute", top: "-60px", left: "-60px", pointerEvents: "none", opacity: 0.2, ...p(4) }}>
                <Image src="/assets/ORNAMEN 3.png" alt="" width={340} height={210} style={{ objectFit: "contain", transform: "scaleY(-1)" }} />
            </div>
            {/* dot grid */}
            <div style={{
                position: "absolute", top: "30%", left: "0",
                width: "180px", height: "240px",
                backgroundImage: "radial-gradient(circle, #e8431a 1px, transparent 1px)",
                backgroundSize: "18px 18px",
                opacity: 0.04, pointerEvents: "none",
            }} />
            {/* Bottom wave */}
            <div style={{ position: "absolute", bottom: 0, left: 0, right: 0, lineHeight: 0, pointerEvents: "none" }}>
                <svg viewBox="0 0 1440 60" preserveAspectRatio="none" style={{ width: "100%", height: "60px" }}>
                    <path d="M0,30 C360,60 1080,0 1440,30 L1440,60 L0,60 Z" fill="#f8f9fb" />
                </svg>
            </div>

            <div style={{ maxWidth: "1280px", margin: "0 auto", padding: "0 32px" }}>
                <div style={{
                    display: "flex", justifyContent: "space-between",
                    alignItems: "center", gap: "48px", flexWrap: "wrap",
                    minHeight: "calc(100vh - 60px)",
                    paddingTop: "60px", paddingBottom: "80px",
                }}>
                    {/* Left */}
                    <div style={{ maxWidth: "420px", zIndex: 10, position: "relative", flexShrink: 0 }}>
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

                    {/* Right: interactive world map — large, uncapped */}
                    <div style={{ flex: "1 1 520px", minWidth: "380px", position: "relative", ...p(-5) }}>
                        <MapWithPulse />
                    </div>
                </div>
            </div>
        </div>
    );
}

// World TopoJSON from CDN — loaded client-side
const GEO_URL = "https://cdn.jsdelivr.net/npm/world-atlas@2/countries-110m.json";

// ISO numeric → country name (full world atlas coverage)
const COUNTRY_NAMES = {
    "4":"Afghanistan","8":"Albania","12":"Algeria","20":"Andorra","24":"Angola",
    "32":"Argentina","36":"Australia","40":"Austria","31":"Azerbaijan",
    "44":"Bahamas","48":"Bahrain","50":"Bangladesh","52":"Barbados",
    "112":"Belarus","56":"Belgium","84":"Belize","204":"Benin","64":"Bhutan",
    "68":"Bolivia","70":"Bosnia & Herz.","72":"Botswana","76":"Brazil",
    "96":"Brunei","100":"Bulgaria","854":"Burkina Faso","108":"Burundi",
    "132":"Cape Verde","116":"Cambodia","120":"Cameroon","124":"Canada",
    "140":"Central African Rep.","148":"Chad","152":"Chile","156":"China",
    "170":"Colombia","174":"Comoros","180":"DR Congo","178":"Congo",
    "188":"Costa Rica","384":"Côte d'Ivoire","191":"Croatia","192":"Cuba",
    "196":"Cyprus","203":"Czech Republic","208":"Denmark","262":"Djibouti",
    "212":"Dominica","214":"Dominican Rep.","218":"Ecuador","818":"Egypt",
    "222":"El Salvador","226":"Equatorial Guinea","232":"Eritrea","233":"Estonia",
    "748":"Eswatini","231":"Ethiopia","242":"Fiji","246":"Finland","250":"France",
    "266":"Gabon","270":"Gambia","268":"Georgia","276":"Germany","288":"Ghana",
    "300":"Greece","308":"Grenada","320":"Guatemala","324":"Guinea",
    "624":"Guinea-Bissau","328":"Guyana","332":"Haiti","340":"Honduras",
    "348":"Hungary","352":"Iceland","356":"India","360":"Indonesia","364":"Iran",
    "368":"Iraq","372":"Ireland","376":"Israel","380":"Italy","388":"Jamaica",
    "392":"Japan","400":"Jordan","398":"Kazakhstan","404":"Kenya",
    "296":"Kiribati","408":"North Korea","410":"South Korea","414":"Kuwait",
    "417":"Kyrgyzstan","418":"Laos","428":"Latvia","422":"Lebanon","426":"Lesotho",
    "430":"Liberia","434":"Libya","438":"Liechtenstein","440":"Lithuania",
    "442":"Luxembourg","450":"Madagascar","454":"Malawi","458":"Malaysia",
    "462":"Maldives","466":"Mali","470":"Malta","584":"Marshall Islands",
    "478":"Mauritania","480":"Mauritius","484":"Mexico","583":"Micronesia",
    "498":"Moldova","492":"Monaco","496":"Mongolia","499":"Montenegro",
    "504":"Morocco","508":"Mozambique","104":"Myanmar","516":"Namibia",
    "520":"Nauru","524":"Nepal","528":"Netherlands","554":"New Zealand",
    "558":"Nicaragua","562":"Niger","566":"Nigeria","578":"Norway",
    "512":"Oman","586":"Pakistan","585":"Palau","591":"Panama",
    "598":"Papua New Guinea","600":"Paraguay","604":"Peru","608":"Philippines",
    "616":"Poland","620":"Portugal","634":"Qatar","642":"Romania","643":"Russia",
    "646":"Rwanda","659":"Saint Kitts & Nevis","662":"Saint Lucia",
    "670":"Saint Vincent & Gren.","882":"Samoa","674":"San Marino",
    "678":"São Tomé & Príncipe","682":"Saudi Arabia","686":"Senegal","688":"Serbia",
    "694":"Sierra Leone","703":"Slovakia","705":"Slovenia","090":"Solomon Islands",
    "706":"Somalia","710":"South Africa","728":"South Sudan","724":"Spain",
    "144":"Sri Lanka","729":"Sudan","740":"Suriname","752":"Sweden",
    "756":"Switzerland","760":"Syria","762":"Tajikistan","764":"Thailand",
    "626":"Timor-Leste","768":"Togo","776":"Tonga","780":"Trinidad & Tobago",
    "788":"Tunisia","792":"Turkey","795":"Turkmenistan","798":"Tuvalu",
    "800":"Uganda","804":"Ukraine","784":"United Arab Emirates",
    "826":"United Kingdom","840":"United States","858":"Uruguay",
    "860":"Uzbekistan","548":"Vanuatu","862":"Venezuela","704":"Vietnam",
    "887":"Yemen","894":"Zambia","716":"Zimbabwe","807":"North Macedonia",
    "051":"Armenia","533":"Aruba","540":"New Caledonia","630":"Puerto Rico",
    "344":"Hong Kong","446":"Macao","275":"Palestine","010":"Antarctica",
    "239":"South Georgia","334":"Heard Island","238":"Falkland Islands",
    "316":"Guam","850":"US Virgin Islands","531":"Curaçao",
};

// Shift hex color lighter/darker
function shiftColor(hex, amount) {
    const n = parseInt(hex.replace("#",""), 16);
    const r = Math.min(255, Math.max(0, (n >> 16) + amount));
    const g = Math.min(255, Math.max(0, ((n >> 8) & 0xff) + amount));
    const b = Math.min(255, Math.max(0, (n & 0xff) + amount));
    return `#${((r<<16)|(g<<8)|b).toString(16).padStart(6,"0")}`;
}
// Mix color with white — factor 1.0 = white, 0 = original
function tintColor(hex, factor) {
    const n = parseInt(hex.replace("#",""), 16);
    const r = Math.round(((n >> 16) * (1 - factor)) + 255 * factor);
    const g = Math.round((((n >> 8) & 0xff) * (1 - factor)) + 255 * factor);
    const b = Math.round(((n & 0xff) * (1 - factor)) + 255 * factor);
    return `#${((r<<16)|(g<<8)|b).toString(16).padStart(6,"0")}`;
}

// Phoenix language coverage countries
// iso2 used for flag images from flagcdn.com
const COUNTRY_DATA = {
    "40":  { name: "Austria",     iso2: "at", color: "#C07840", cx: [14.6, 47.5] },
    "56":  { name: "Belgium",     iso2: "be", color: "#D44030", cx: [4.5,  50.5] },
    "100": { name: "Bulgaria",    iso2: "bg", color: "#2E7A4E", cx: [25.5, 42.8] },
    "196": { name: "Cyprus",      iso2: "cy", color: "#2E8AAE", cx: [33.2, 35.1] },
    "191": { name: "Croatia",     iso2: "hr", color: "#C46028", cx: [16.4, 45.2] },
    "203": { name: "Czech Rep.",  iso2: "cz", color: "#9A2848", cx: [15.5, 49.8] },
    "208": { name: "Denmark",     iso2: "dk", color: "#2878C0", cx: [10.0, 56.3] },
    "233": { name: "Estonia",     iso2: "ee", color: "#4898C8", cx: [25.0, 58.6] },
    "246": { name: "Finland",     iso2: "fi", color: "#3858A8", cx: [26.0, 64.5] },
    "250": { name: "France",      iso2: "fr", color: "#68B428", cx: [2.4,  46.6] },
    "276": { name: "Germany",     iso2: "de", color: "#A07A40", cx: [10.4, 51.5] },
    "300": { name: "Greece",      iso2: "gr", color: "#2888C8", cx: [22.0, 39.5] },
    "348": { name: "Hungary",     iso2: "hu", color: "#C02838", cx: [19.5, 47.2] },
    "372": { name: "Ireland",     iso2: "ie", color: "#289A48", cx: [-8.2, 53.4] },
    "380": { name: "Italy",       iso2: "it", color: "#78C028", cx: [12.6, 43.0] },
    "428": { name: "Latvia",      iso2: "lv", color: "#902828", cx: [24.9, 56.9] },
    "440": { name: "Lithuania",   iso2: "lt", color: "#C0A020", cx: [23.9, 55.5] },
    "442": { name: "Luxembourg",  iso2: "lu", color: "#C08020", cx: [6.1,  49.8] },
    "470": { name: "Malta",       iso2: "mt", color: "#C02828", cx: [14.4, 35.9] },
    "528": { name: "Netherlands", iso2: "nl", color: "#C8B018", cx: [5.3,  52.3] },
    "578": { name: "Norway",      iso2: "no", color: "#287898", cx: [15.5, 65.5] },
    "616": { name: "Poland",      iso2: "pl", color: "#D8C818", cx: [19.4, 52.1] },
    "620": { name: "Portugal",    iso2: "pt", color: "#2868C0", cx: [-8.2, 39.6] },
    "642": { name: "Romania",     iso2: "ro", color: "#3888B8", cx: [24.9, 45.9] },
    "703": { name: "Slovakia",    iso2: "sk", color: "#287A48", cx: [19.7, 48.7] },
    "705": { name: "Slovenia",    iso2: "si", color: "#986820", cx: [15.0, 46.1] },
    "724": { name: "Spain",       iso2: "es", color: "#6A2880", cx: [-3.7, 40.4] },
    "752": { name: "Sweden",      iso2: "se", color: "#C09820", cx: [15.3, 62.5] },
    "756": { name: "Switzerland", iso2: "ch", color: "#C02828", cx: [8.2,  46.8] },
    "826": { name: "UK",          iso2: "gb", color: "#2848C0", cx: [-2.5, 54.0] },
};


// Compute zoom level — returns null for giants (no zoom, just highlight)
function getZoomForGeo(geo) {
    try {
        const [[x0, y0], [x1, y1]] = geoBounds(geo);
        const area = (x1 - x0) * (y1 - y0);
        if (area > 1500) return null;  // Russia, Canada, etc. → no zoom
        if (area < 1)    return 10;
        if (area < 5)    return 7;
        if (area < 20)   return 5;
        if (area < 80)   return 3.5;
        if (area < 300)  return 2.2;
        if (area < 800)  return 1.6;
        return 1.15;
    } catch {
        return 4;
    }
}

function MapWithPulse() {
    return <WorldMapInteractive />;
}

// Europe-only projection: center on Europe, high scale to push other continents off-screen
const EUROPE_CENTER = [13, 52];
const EUROPE_SCALE  = 1100;

function WorldMapInteractive() {
    const [hovered, setHovered] = useState(null);
    const [selectedId, setSelectedId] = useState(null);
    const [tooltipPos, setTooltipPos] = useState({ x: 0, y: 0 });
    const [mapCenter, setMapCenter] = useState(EUROPE_CENTER);
    const [mapZoom, setMapZoom] = useState(1);
    const wrapRef = useRef(null);

    const handleMouseMove = useCallback((e) => {
        if (!wrapRef.current) return;
        const rect = wrapRef.current.getBoundingClientRect();
        setTooltipPos({ x: e.clientX - rect.left, y: e.clientY - rect.top });
    }, []);

    const handleCountryClick = useCallback((geo) => {
        try {
            const targetZoom = getZoomForGeo(geo);
            setSelectedId(geo.id);
            if (targetZoom !== null) {
                const centroid = geoCentroid(geo);
                setMapCenter(centroid);
                setMapZoom(targetZoom);
            }
            // null zoom → just highlight, no pan/zoom (e.g. Russia)
        } catch {
            setSelectedId(geo.id);
        }
    }, []);

    const handleReset = useCallback(() => {
        setMapCenter(EUROPE_CENTER);
        setMapZoom(1);
        setSelectedId(null);
    }, []);

    return (
        <div
            ref={wrapRef}
            onMouseMove={handleMouseMove}
            style={{
                position: "relative",
                width: "100%",
                animation: "mapFadeIn 1s ease forwards",
                background: "transparent",
                overflow: "hidden",
                userSelect: "none",
            }}
        >
            {/* Global Reach label — centered, tight above map */}
            <div style={{ textAlign: "center", marginBottom: "4px" }}>
                <span style={{
                    display: "inline-flex", alignItems: "center", gap: "6px",
                    fontSize: "0.62rem", fontWeight: 700, color: "#e8431a", letterSpacing: "0.08em",
                }}>
                    <span style={{ width: "5px", height: "5px", borderRadius: "50%", background: "#e8431a", display: "inline-block" }} />
                    GLOBAL REACH · 50+ LANGUAGES
                </span>
            </div>

            <ComposableMap
                width={800} height={600}
                projectionConfig={{ scale: EUROPE_SCALE, center: EUROPE_CENTER }}
                style={{ width: "100%", height: "auto" }}
            >
                <defs>
                    <clipPath id="flag-circle"><circle r={5} cx={0} cy={0} /></clipPath>
                    {Object.entries(COUNTRY_DATA).map(([id, data]) => (
                        <React.Fragment key={id}>
                            {/* Vivid gradient — used on hover */}
                            <linearGradient id={`grad-${id}`} x1="0%" y1="0%" x2="30%" y2="100%">
                                <stop offset="0%"   stopColor={shiftColor(data.color, 40)} />
                                <stop offset="55%"  stopColor={data.color} />
                                <stop offset="100%" stopColor={shiftColor(data.color, -35)} />
                            </linearGradient>
                            {/* Muted hatch pattern — used as default */}
                            <pattern id={`hatch-${id}`} x="0" y="0" width="7" height="7"
                                patternUnits="userSpaceOnUse" patternTransform="rotate(42)">
                                <rect width="7" height="7" fill={tintColor(data.color, 0.72)} />
                                <line x1="0" y1="0" x2="0" y2="7"
                                    stroke={tintColor(data.color, 0.46)} strokeWidth="2" />
                            </pattern>
                        </React.Fragment>
                    ))}
                </defs>
                <ZoomableGroup
                    zoom={mapZoom}
                    center={mapCenter}
                    minZoom={0.9}
                    maxZoom={12}
                    translateExtent={[[-300, -250], [1100, 850]]}
                    onMoveEnd={({ coordinates, zoom }) => {
                        // Clamp pan to Europe's geographic bounds
                        const lon = Math.max(-28, Math.min(50, coordinates[0]));
                        const lat = Math.max(30, Math.min(73, coordinates[1]));
                        setMapCenter([lon, lat]);
                        setMapZoom(zoom);
                    }}
                >
                    <Geographies geography={GEO_URL}>
                        {({ geographies }) => {
                            // SVG renders in document order — put hovered/selected last so they appear on top
                            const sorted = [...geographies].sort((a, b) => {
                                const aTop = a.id === hovered || a.id === selectedId;
                                const bTop = b.id === hovered || b.id === selectedId;
                                if (aTop === bTop) return 0;
                                return aTop ? 1 : -1;
                            });
                            return sorted.map((geo) => {
                                const id = geo.id;
                                const data = COUNTRY_DATA[id];
                                const isSelected = selectedId === id;
                                const isPuzzle = !!data;

                                // Hide non-European countries completely
                                if (!isPuzzle) return (
                                    <Geography
                                        key={geo.rsmKey}
                                        geography={geo}
                                        style={{
                                            default: { fill: "transparent", stroke: "transparent", outline: "none" },
                                            hover:   { fill: "transparent", stroke: "transparent", outline: "none" },
                                            pressed: { fill: "transparent", outline: "none" },
                                        }}
                                    />
                                );

                                return (
                                    <Geography
                                        key={geo.rsmKey}
                                        geography={geo}
                                        className="rsm-puzzle"
                                        onMouseEnter={() => setHovered(id)}
                                        onMouseLeave={() => setHovered(null)}
                                        onClick={() => handleCountryClick(geo)}
                                        style={{
                                            default: {
                                                fill: isSelected ? `url(#grad-${id})` : `url(#hatch-${id})`,
                                                stroke: "#ffffff",
                                                strokeWidth: isSelected ? 1.4 : 0.8,
                                                outline: "none",
                                            },
                                            hover: {
                                                fill: `url(#grad-${id})`,
                                                stroke: "#ffffff",
                                                strokeWidth: 1.2,
                                                outline: "none",
                                            },
                                            pressed: {
                                                fill: `url(#grad-${id})`,
                                                outline: "none",
                                            },
                                        }}
                                    />
                                );
                            });
                        }}
                    </Geographies>

                    {/* Flag pins + country labels */}
                    {Object.entries(COUNTRY_DATA).map(([id, data]) => (
                        <Marker key={`pin-${id}`} coordinates={data.cx}>
                            {/* Country name — rotated like engraved text */}
                            <text
                                textAnchor="middle"
                                y={-9}
                                transform="rotate(-5)"
                                style={{
                                    fontSize: "3.6px",
                                    fontWeight: 800,
                                    fill: "#111",
                                    fontFamily: "Arial, sans-serif",
                                    pointerEvents: "none",
                                    letterSpacing: "0.02em",
                                    paintOrder: "stroke",
                                    stroke: "rgba(255,255,255,0.7)",
                                    strokeWidth: "1.2px",
                                }}
                            >
                                {data.name}
                            </text>
                            {/* Pin drop shadow */}
                            <circle r={5.8} cx={0.5} cy={1.3} fill="rgba(0,0,0,0.20)" />
                            {/* Pin white body */}
                            <circle r={5.2} fill="white" stroke="#ccc" strokeWidth={0.3} />
                            {/* Actual flag image from flagcdn */}
                            <image
                                href={`https://flagcdn.com/w20/${data.iso2}.png`}
                                x={-5.2} y={-3.5}
                                width={10.4} height={7}
                                clipPath="url(#flag-circle)"
                                style={{ pointerEvents: "none" }}
                            />
                        </Marker>
                    ))}
                </ZoomableGroup>
            </ComposableMap>

            {/* Hint + Reset — below the map */}
            <div style={{
                display: "flex", alignItems: "center", justifyContent: "center",
                gap: "16px", padding: "6px 0 2px",
            }}>
                <span style={{ fontSize: "0.58rem", color: "#bbb", letterSpacing: "0.04em" }}>
                    {selectedId && COUNTRY_NAMES[selectedId]
                        ? `📍 ${COUNTRY_NAMES[selectedId]}`
                        : "Click a country to zoom · Drag to pan"}
                </span>
                {selectedId && (
                    <button onClick={handleReset} style={{
                        fontSize: "0.58rem", color: "#e8431a", background: "none",
                        border: "1px solid rgba(232,67,26,0.3)", borderRadius: "4px",
                        padding: "2px 10px", cursor: "pointer", letterSpacing: "0.04em",
                        fontWeight: 600,
                    }}>
                        ← Reset
                    </button>
                )}
            </div>

            {/* Floating tooltip */}
            {hovered && COUNTRY_NAMES[hovered] && (
                <div style={{
                    position: "absolute",
                    left: tooltipPos.x + 12,
                    top: tooltipPos.y - 36,
                    background: "#fff",
                    border: "1px solid #f0f0f0",
                    borderRadius: "8px",
                    padding: "5px 10px",
                    fontSize: "0.72rem",
                    fontWeight: 700,
                    color: "#111",
                    boxShadow: "0 4px 16px rgba(0,0,0,0.12)",
                    pointerEvents: "none",
                    whiteSpace: "nowrap",
                    animation: "tooltipIn 0.15s ease forwards",
                    zIndex: 20,
                }}>
                    <span style={{ color: "#e8431a", marginRight: "4px" }}>●</span>
                    {COUNTRY_NAMES[hovered]}
                </div>
            )}
        </div>
    );
}
