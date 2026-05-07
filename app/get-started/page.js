import GetStartedForm from "./GetStartedForm";

async function fetchPrices() {
    const base = (process.env.NEXT_PUBLIC_WC_URL || "").replace(/\/$/, "");
    if (!base) return null;
    const fmt = (minor) => "$" + (parseInt(minor, 10) / 100).toLocaleString("en-US");
    try {
        const [bm, by, pm, py] = await Promise.all([
            fetch(`${base}/wp-json/wc/store/v1/products/61`, { next: { revalidate: 3600 } }).then(r => r.json()),
            fetch(`${base}/wp-json/wc/store/v1/products/62`, { next: { revalidate: 3600 } }).then(r => r.json()),
            fetch(`${base}/wp-json/wc/store/v1/products/78`, { next: { revalidate: 3600 } }).then(r => r.json()),
            fetch(`${base}/wp-json/wc/store/v1/products/79`, { next: { revalidate: 3600 } }).then(r => r.json()),
        ]);
        return {
            free:    { monthly: "$0" },
            basic:   { monthly: fmt(bm?.prices?.price), yearly: fmt(by?.prices?.price) },
            premium: { monthly: fmt(pm?.prices?.price), yearly: fmt(py?.prices?.price) },
        };
    } catch {
        return null;
    }
}

export default async function GetStartedPage() {
    const prices = await fetchPrices();
    return <GetStartedForm initialPrices={prices} />;
}
