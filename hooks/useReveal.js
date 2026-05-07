"use client";

import { useEffect, useRef, useState } from "react";

export function useReveal(options = {}) {
    const ref = useRef(null);
    const [visible, setVisible] = useState(false);

    useEffect(() => {
        const el = ref.current;
        if (!el) return;
        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting) {
                    setVisible(true);
                    observer.disconnect();
                }
            },
            { threshold: options.threshold ?? 0.15, rootMargin: options.rootMargin ?? "0px" }
        );
        observer.observe(el);
        return () => observer.disconnect();
    }, []);

    return { ref, visible };
}

// Returns inline style for the animated element
export function revealStyle(visible, options = {}) {
    const delay = options.delay ?? 0;
    const direction = options.direction ?? "up"; // up | left | right | none

    const transforms = {
        up: visible ? "translateY(0)" : "translateY(36px)",
        left: visible ? "translateX(0)" : "translateX(-36px)",
        right: visible ? "translateX(0)" : "translateX(36px)",
        none: "none",
    };

    return {
        opacity: visible ? 1 : 0,
        transform: transforms[direction],
        transition: `opacity 0.65s ease ${delay}ms, transform 0.65s ease ${delay}ms`,
    };
}
