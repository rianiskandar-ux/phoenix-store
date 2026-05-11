import { getPlanPrices } from "@/lib/woocommerce";

export const revalidate = 3600;

export async function GET() {
    const prices = await getPlanPrices();
    return Response.json(prices);
}
