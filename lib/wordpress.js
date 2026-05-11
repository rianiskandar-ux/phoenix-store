const WP_URL = process.env.NEXT_PUBLIC_WC_URL?.replace(/\/$/, "");

// ─── GraphQL helper ───────────────────────────────────────────────
async function wpQuery(query, variables = {}) {
    const res = await fetch(`${WP_URL}/graphql`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ query, variables }),
        next: { revalidate: 3600 },
    });
    if (!res.ok) throw new Error(`WPGraphQL error: ${res.status}`);
    const { data, errors } = await res.json();
    if (errors) throw new Error(errors[0].message);
    return data;
}

// ─── Posts ────────────────────────────────────────────────────────
export async function getPosts(first = 10) {
    const data = await wpQuery(`
        query GetPosts($first: Int!) {
            posts(first: $first, where: { status: PUBLISH }) {
                nodes {
                    id
                    slug
                    title
                    date
                    excerpt
                    featuredImage { node { sourceUrl altText } }
                    categories { nodes { name slug } }
                    author { node { name } }
                }
            }
        }
    `, { first });
    return data.posts.nodes;
}

export async function getPostBySlug(slug) {
    const data = await wpQuery(`
        query GetPost($slug: ID!) {
            post(id: $slug, idType: SLUG) {
                id
                slug
                title
                date
                content
                excerpt
                featuredImage { node { sourceUrl altText } }
                categories { nodes { name slug } }
                author { node { name } }
                seo { title description }
            }
        }
    `, { slug });
    return data.post;
}

// ─── Pages ────────────────────────────────────────────────────────
export async function getPageBySlug(slug) {
    const data = await wpQuery(`
        query GetPage($slug: ID!) {
            page(id: $slug, idType: URI) {
                id
                slug
                title
                content
                featuredImage { node { sourceUrl altText } }
                seo { title description }
            }
        }
    `, { slug });
    return data.page;
}

// ─── Menus ────────────────────────────────────────────────────────
export async function getMenu(location = "PRIMARY") {
    const data = await wpQuery(`
        query GetMenu($location: MenuLocationEnum!) {
            menus(where: { location: $location }) {
                nodes {
                    menuItems {
                        nodes {
                            id
                            label
                            url
                            parentId
                            childItems { nodes { id label url } }
                        }
                    }
                }
            }
        }
    `, { location });
    return data.menus.nodes[0]?.menuItems.nodes ?? [];
}

// ─── Site info ────────────────────────────────────────────────────
export async function getSiteInfo() {
    const data = await wpQuery(`
        query GetSiteInfo {
            generalSettings {
                title
                description
                url
                language
            }
        }
    `);
    return data.generalSettings;
}
