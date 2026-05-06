import { getProduct, getVariation } from "@/lib/woocommerce";

export async function GET() {
    const [
        free,
        basicParent,
        basicV61, basicV62,
        premiumParent,
        premiumV78, premiumV79,
    ] = await Promise.allSettled([
        getProduct(30596),
        getProduct(58),
        getVariation(58, 61),
        getVariation(58, 62),
        getProduct(76),
        getVariation(76, 78),
        getVariation(76, 79),
    ]);

    const pick = (r) => r.status === "fulfilled"
        ? { id: r.value?.id, name: r.value?.name, price: r.value?.price, regular_price: r.value?.regular_price, sale_price: r.value?.sale_price, status: r.value?.status, attributes: r.value?.attributes }
        : { error: r.reason?.message };

    return Response.json({
        free:          pick(free),
        basicParent:   pick(basicParent),
        basicV61:      pick(basicV61),
        basicV62:      pick(basicV62),
        premiumParent: pick(premiumParent),
        premiumV78:    pick(premiumV78),
        premiumV79:    pick(premiumV79),
    });
}
