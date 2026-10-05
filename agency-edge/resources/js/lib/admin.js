export const humanize = (key) =>
    String(key)
        .replace(/_/g, ' ')
        .replace(/\bai\b/gi, 'AI')
        .replace(/\bcta\b/gi, 'CTA')
        .replace(/\burl\b/gi, 'URL')
        .replace(/^./, (c) => c.toUpperCase());

export const SECTION_HINTS = {
    meta: 'Site name and default search description',
    contact: 'Email, phone, address, LinkedIn — shown only when filled',
    hero: 'Home page headline and buttons',
    shift: '"Everyone has the tools" statement',
    intro: 'Not just another digital agency',
    equation: 'Marketing × Creative × Technology × AI',
    disciplines: 'The four capability cards',
    digital: 'What We Do — digital marketing services',
    creative: 'What We Do — creative & brand services',
    journey: 'Discover → Engage → Capture → Nurture → Grow',
    process: 'How we work — five steps',
    martech: 'MarTech page and Ribelz partnership',
    ai: 'AI for Marketing page',
    demo: 'Chat demo and system flow',
    industries: 'Markets / industries list',
    about: 'About page copy and co-founder quote',
    founders: 'Founder names, bios, photos, LinkedIn',
    why: 'Why Agency Edge proof points',
    engagement: 'Ways to work together',
    cta: 'Final call to action and contact form options',
    newsletter: 'Footer newsletter text',
    insights: 'Insights page heading',
};

/** Fields whose value is an uploaded image URL. */
export const isImageKey = (key) => /(^|_)(photo|image|logo|cover)$/.test(String(key));

/** Long strings get a textarea. */
export const isLong = (key, value) => typeof value === 'string' && (value.length > 70 || /(body|bio|text|lead|summary|description|quote|paragraph)/.test(String(key)));

/** Build an empty item shaped like a template value (for "Add item"). */
export function blankLike(template) {
    if (Array.isArray(template)) return [];
    if (template && typeof template === 'object') {
        return Object.fromEntries(Object.entries(template).map(([k, v]) => [k, blankLike(v)]));
    }
    return '';
}
