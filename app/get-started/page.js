import GetStartedForm from "./GetStartedForm";

// Update these when prices change in WooCommerce
const PRICES = {
    free:    { monthly: "$0" },
    basic:   { monthly: "$65", yearly: "$650" },
    premium: { monthly: "$110", yearly: "$995" },
};

export default function GetStartedPage() {
    return <GetStartedForm initialPrices={PRICES} />;
}
