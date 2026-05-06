const WC_URL = process.env.NEXT_PUBLIC_WC_URL;
const KEY = process.env.WC_CONSUMER_KEY;
const SECRET = process.env.WC_CONSUMER_SECRET;

const authHeader = {
    Authorization: "Basic " + btoa(KEY + ":" + SECRET),
};

export async function getProducts() {
    const res = await fetch(`${WC_URL}/wp-json/wc/v3/products`, {
        headers: authHeader,
    });
    return res.json();
}

export async function getProduct(id) {
    const res = await fetch(`${WC_URL}/wp-json/wc/v3/products/${id}`, {
        headers: authHeader,
        next: { revalidate: 3600 },
    });
    return res.json();
}

export async function getVariation(productId, variationId) {
    const res = await fetch(
        `${WC_URL}/wp-json/wc/v3/products/${productId}/variations/${variationId}`,
        { headers: authHeader, next: { revalidate: 3600 } }
    );
    return res.json();
}

export async function getPlanPrices() {
    try {
        const [freeProduct, basicMonthly, basicYearly, premiumMonthly, premiumYearly] = await Promise.all([
            getProduct(30596),
            getVariation(58, 61),
            getVariation(58, 62),
            getVariation(76, 78),
            getVariation(76, 79),
        ]);

        const fmt = (p) => p?.price ? `$${parseFloat(p.price).toFixed(0)}` : null;

        return {
            free:           fmt(freeProduct)    || "$0",
            basicMonthly:   fmt(basicMonthly)   || "$65",
            basicYearly:    fmt(basicYearly)    || "$650",
            premiumMonthly: fmt(premiumMonthly) || "$110",
            premiumYearly:  fmt(premiumYearly)  || "$995",
        };
    } catch {
        return {
            free: "$0", basicMonthly: "$50", basicYearly: "$499",
            premiumMonthly: "$90", premiumYearly: "$849",
        };
    }
}