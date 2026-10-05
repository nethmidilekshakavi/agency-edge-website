<?php

/*
|--------------------------------------------------------------------------
| Agency Edge — default website content
|--------------------------------------------------------------------------
| Source: "Agency Edge Website Content and Customer Deck" (slides 1–26).
| Every top-level section below can be edited in Admin → Content. Saved
| edits live in the `content_blocks` table and override these defaults;
| "Reset to default" in the admin restores what is written here.
|
| Rule from the deck's developer handoff: do NOT invent customer logos,
| testimonials, statistics, addresses, phone numbers, emails or case-study
| results. Contact details are therefore empty until confirmed.
|
| Keep this file a plain PHP array (no env()/helper calls): the frontend
| preview build reads it directly.
*/

return [

    'meta' => [
        'site_name' => 'Agency Edge',
        'title_suffix' => 'Agency Edge — Marketing Meets Technology',
        'description' => 'Agency Edge brings marketing thinking, creative execution, digital growth and modern marketing technology together to help brands attract customers, engage audiences and turn digital activity into measurable business growth.',
        'positioning' => 'Marketing + Creative + Growth + MarTech Consulting',
    ],

    'contact' => [
        'email' => '',
        'phone' => '',
        'address' => '',
        'company_linkedin' => '',
        'ribelz_url' => '',
    ],

    'hero' => [
        'eyebrow' => 'Strategy. Creative. Growth. MarTech.',
        'title_lines' => ['Marketing', 'Meets', 'Technology.'],
        'kicker' => 'Built for brands that want more than attention.',
        'lead' => 'Agency Edge brings marketing thinking, creative execution, digital growth and modern marketing technology together to help brands attract customers, engage audiences and turn digital activity into measurable business growth.',
        'primary_cta' => 'Talk to Us',
        'secondary_cta' => 'Explore Our Capabilities',
        'marquee' => ['Strategy', 'Creative', 'Growth', 'MarTech', 'AI', 'Media', 'CRM', 'Automation'],
    ],

    'shift' => [
        'label' => 'The shift',
        'title' => 'Everyone has the tools.',
        'highlight' => 'Few know where to use them.',
        'body' => 'The advantage is no longer access to digital tools or AI. It is knowing how to connect them to a customer journey and a commercial outcome.',
    ],

    'intro' => [
        'label' => 'Not just another digital agency',
        'title' => 'Marketing has changed.',
        'paragraphs' => [
            'Brands need more than social posts, advertisements and creative campaigns. They need connected customer journeys, intelligent automation, useful data, AI and technology working together.',
            'Agency Edge sits at the intersection of marketing and technology. We combine experienced marketing leadership, creative thinking, performance execution and MarTech consulting to solve commercial problems — not simply produce marketing activity.',
        ],
        'cta' => 'See How We Work',
    ],

    'equation' => [
        'label' => 'Our point of view',
        'title' => 'The edge is in the connection.',
        'items' => [
            ['name' => 'Marketing', 'role' => 'Find the demand'],
            ['name' => 'Creative', 'role' => 'Earn attention'],
            ['name' => 'Technology', 'role' => 'Remove friction'],
            ['name' => 'AI', 'role' => 'Scale intelligence'],
        ],
        'result' => 'Agency Edge',
        'result_sub' => 'Marketing + Creative + Growth + MarTech Consulting',
    ],

    'disciplines' => [
        'label' => 'Capabilities',
        'title' => 'One growth system. Four disciplines.',
        'items' => [
            [
                'name' => 'Strategy',
                'summary' => 'Find the demand and define the journey before choosing channels or tools.',
                'points' => ['Positioning', 'Customer journeys', 'Go-to-market', 'Growth planning'],
            ],
            [
                'name' => 'Creative',
                'summary' => 'Creative that earns attention — and gives people a reason to act.',
                'points' => ['Campaign ideas', 'Brand systems', 'Content', 'Video & AI creative'],
            ],
            [
                'name' => 'Media',
                'summary' => 'Build demand, reach the right audience and convert attention into action.',
                'points' => ['Social', 'Meta & Google', 'SEO', 'Lead generation'],
            ],
            [
                'name' => 'MarTech',
                'summary' => 'Connect marketing to the systems that make it work harder.',
                'points' => ['AI assistants', 'CRM journeys', 'Automation', 'Analytics'],
            ],
        ],
    ],

    'digital' => [
        'label' => 'What we do — Digital marketing',
        'title' => 'Build demand. Reach the right audience. Convert attention into action.',
        'items' => [
            ['name' => 'Digital & channel strategy', 'text' => 'A clear plan for which channels play which role in the customer journey.'],
            ['name' => 'Social media management', 'text' => 'Always-on social presence with a purpose behind every post.'],
            ['name' => 'Meta and Google advertising', 'text' => 'Paid media planned, bought and optimized against commercial goals.'],
            ['name' => 'SEO and search marketing', 'text' => 'Be found at the moment customers are looking.'],
            ['name' => 'Content strategy and content marketing', 'text' => 'Content that earns attention and moves people forward.'],
            ['name' => 'Lead-generation campaigns', 'text' => 'Campaigns designed to capture and qualify real opportunities.'],
            ['name' => 'Landing-page and conversion strategy', 'text' => 'Remove friction between interest and action.'],
            ['name' => 'Campaign analytics and optimization', 'text' => 'Measure what matters and improve continuously.'],
        ],
        'summary' => 'We connect channels around a clear customer journey so each activity has a role in moving the customer forward.',
    ],

    'creative' => [
        'label' => 'What we do — Creative & brand',
        'title' => 'Creative that earns attention — and gives people a reason to act.',
        'items' => [
            ['name' => 'Brand and campaign strategy', 'text' => 'The thinking that gives every asset a job to do.'],
            ['name' => 'Campaign concepts and big ideas', 'text' => 'Ideas strong enough to carry across every channel.'],
            ['name' => 'Graphic design and social creative', 'text' => 'Scroll-stopping design built for the platform.'],
            ['name' => 'Digital campaign assets', 'text' => 'Complete, consistent asset systems for launch and always-on.'],
            ['name' => 'AI-assisted creative production', 'text' => 'Faster production with human strategy in control.'],
            ['name' => 'Video and short-form reels', 'text' => 'Motion-led stories for feeds, stories and screens.'],
            ['name' => 'Campaign landing pages and microsites', 'text' => 'Digital destinations that convert attention into action.'],
            ['name' => 'Interactive and AR campaign concepts', 'text' => 'Experiences people play with, share and remember.'],
        ],
        'summary' => 'Our creative work begins with the audience and commercial objective, then finds the strongest idea and format to connect them.',
    ],

    'journey' => [
        'label' => 'Customer journey',
        'title' => 'From first impression to next best action.',
        'stages' => [
            ['name' => 'Discover', 'points' => ['Content', 'Search', 'Media']],
            ['name' => 'Engage', 'points' => ['Creative', 'Experience', 'Conversation']],
            ['name' => 'Capture', 'points' => ['Landing pages', 'AI assistants', 'Lead forms']],
            ['name' => 'Nurture', 'points' => ['CRM', 'Automation', 'Personalization']],
            ['name' => 'Grow', 'points' => ['Analytics', 'Optimization', 'Retention']],
        ],
        'summary' => 'Agency Edge designs the journey — then connects the right creative, media and technology around it.',
    ],

    'process' => [
        'label' => 'How we work',
        'title' => 'Five moves from problem to performance.',
        'steps' => [
            ['name' => 'Understand', 'text' => 'We start with the business, customer, market and commercial objective.'],
            ['name' => 'Strategize', 'text' => 'We define positioning, customer journey, channels, content and the role of technology.'],
            ['name' => 'Create', 'text' => 'We develop the campaign ideas, content, creative assets and digital experiences.'],
            ['name' => 'Connect', 'text' => 'Where technology is required, we design the MarTech requirement and work with Ribelz for web, AI, software and integration development.'],
            ['name' => 'Optimize', 'text' => 'We measure performance, learn from customer behaviour and continuously improve.'],
        ],
    ],

    'martech' => [
        'label' => 'MarTech consulting',
        'title_a' => 'Strategy by Agency Edge.',
        'title_b' => 'Technology by Ribelz.',
        'lead' => 'One connected capability from marketing problem to working solution.',
        'tags' => ['Consulting', 'Journey design', 'Websites', 'AI customer bots', 'CRM', 'Automation', 'Integrations', 'Custom MarTech'],
        'heading' => 'Connect marketing to the systems that make it work harder.',
        'body' => 'Agency Edge helps businesses identify where technology can improve acquisition, engagement, lead handling, customer experience and retention. We translate marketing requirements into practical MarTech journeys and work with Ribelz for technical development.',
        'capabilities' => [
            'Marketing automation',
            'AI customer engagement',
            'AI customer assistants',
            'CRM and lead-management strategy',
            'Customer-journey automation',
            'Marketing analytics',
            'Campaign technology',
            'Website conversion strategy',
            'Customer-engagement platforms',
            'System integrations',
        ],
    ],

    'ai' => [
        'label' => 'AI for marketing',
        'title_a' => 'AI should not be a feature.',
        'title_b' => 'It should do a job.',
        'jobs' => [
            ['name' => 'Answer', 'text' => 'Always-on customer response'],
            ['name' => 'Qualify', 'text' => 'Identify intent and opportunity'],
            ['name' => 'Follow up', 'text' => 'Move leads without delay'],
            ['name' => 'Learn', 'text' => 'Turn conversations into insight'],
        ],
        'tagline' => 'Human strategy. AI leverage. Better customer experience.',
        'page_title' => 'Turn AI into a working part of your marketing team.',
        'page_body' => 'The goal is not to add AI because it is fashionable. The goal is to give AI a useful job inside the customer journey.',
        'use_cases' => [
            ['name' => 'AI Customer Assistants', 'text' => 'Answer questions and guide customers 24/7.'],
            ['name' => 'AI Lead Management', 'text' => 'Capture, qualify, route and prioritize opportunities.'],
            ['name' => 'AI-Powered Campaigns', 'text' => 'Accelerate creative and campaign execution while keeping human strategy in control.'],
            ['name' => 'Marketing Automation', 'text' => 'Trigger timely communication and follow-up.'],
            ['name' => 'AI + CRM', 'text' => 'Connect conversations, customer context and sales action.'],
        ],
        'cta' => 'Explore an AI Use Case',
    ],

    'demo' => [
        'label' => 'Example experience',
        'title' => 'A customer asks. The brand responds. The system moves.',
        'customer_message' => 'Is this available?',
        'bot_reply' => 'Yes — I can help. What date works?',
        'nodes' => [
            ['name' => 'AI Assistant', 'text' => 'Answers instantly'],
            ['name' => 'CRM', 'text' => 'Captures the lead'],
            ['name' => 'Automation', 'text' => 'Triggers follow-up'],
            ['name' => 'Team', 'text' => 'Receives context'],
        ],
        'summary' => 'This is MarTech when strategy, AI and development are designed as one experience.',
    ],

    'industries' => [
        'label' => 'Markets',
        'title' => 'Different industries. The same question:',
        'highlight' => 'How do we move the customer forward?',
        'intro' => 'Cross-industry thinking. Market-specific execution.',
        'body' => 'We bring cross-industry ideas, then adapt the execution to the market, customer and commercial model.',
        'items' => [
            ['name' => 'Hospitality & Travel', 'text' => 'Guest acquisition, direct-booking growth, customer engagement, CRM and AI service.'],
            ['name' => 'FMCG & Consumer Brands', 'text' => 'Brand building, launches, campaigns, social engagement and consumer experiences.'],
            ['name' => 'Retail & E-commerce', 'text' => 'Performance marketing, conversion, retention and automation.'],
            ['name' => 'Corporate & B2B', 'text' => 'Authority building, lead generation, digital journeys and CRM.'],
            ['name' => 'Technology & SaaS', 'text' => 'Go-to-market strategy, product marketing and demand generation.'],
        ],
    ],

    'about' => [
        'label' => 'About Agency Edge',
        'title' => 'Modern marketing works best when strategy, creativity, data and technology are designed together.',
        'paragraphs' => [
            'Agency Edge was created around a simple belief: modern marketing works best when strategy, creativity, data and technology are designed together.',
            'We are a Marketing + Creative + Growth + MarTech Consulting company built to help businesses navigate a market where customer expectations, media platforms and AI are changing rapidly.',
            'We lead marketing strategy, creative thinking, growth execution and MarTech consulting. For technical development, we work closely with Ribelz — connecting marketing requirements to websites, AI systems, automation, CRM integrations and custom technology.',
        ],
        'positioning' => 'We help clients market their businesses; Ribelz provides the specialist technology capability behind the solutions.',
        'quote' => 'Agency Edge exists because we saw the same problem everywhere: brands with access to great tools and talented people, but without the integrated thinking to connect them into a system that actually grows the business.',
    ],

    'founders' => [
        'label' => 'Leadership',
        'title' => 'Built on marketing experience + technology depth.',
        'people' => [
            [
                'name' => 'Indika Jayapala',
                'initials' => 'IJ',
                'role' => 'Co-Founder — Strategy, Technology & Innovation',
                'years' => '15+',
                'short_bio' => '15+ years across digital marketing, web technology and software. Founder of Ribelz. Connects business strategy, AI and technology.',
                'bio' => 'Entrepreneur and technology strategist with 15+ years across digital marketing, web technology, software and technology-led business solutions. Founder of Ribelz. At Agency Edge, Indika focuses on strategy, AI, MarTech innovation and connecting marketing challenges with technology-driven solutions.',
                'linkedin' => '',
                'photo' => '',
            ],
            [
                'name' => 'Sujith Caldera',
                'initials' => 'SC',
                'role' => 'Co-Founder — Sales, Marketing & Business Development',
                'years' => '19+',
                'short_bio' => '19+ years across digital marketing and media. Brings marketing strategy, client relationships, consulting and commercial growth.',
                'bio' => 'Experienced digital marketing professional with 19+ years across digital marketing and media. At Agency Edge, Sujith focuses on marketing strategy, consulting, client partnerships, business development and translating commercial objectives into effective marketing programs.',
                'linkedin' => 'https://www.linkedin.com/in/sujithcaldera/',
                'photo' => '',
            ],
        ],
    ],

    'why' => [
        'label' => 'Why Agency Edge',
        'title_a' => 'Not another social media agency.',
        'title_b' => 'A growth partner with a tech edge.',
        'points' => [
            ['name' => 'Experienced Leadership', 'text' => 'Senior thinking across marketing, technology and commercial growth.'],
            ['name' => 'Marketing + Technology', 'text' => 'Strategy and creative backed by specialist development capability.'],
            ['name' => 'AI-Ready Thinking', 'text' => 'Practical AI use cases tied to customer and business outcomes.'],
            ['name' => 'Strategy Before Execution', 'text' => 'We define the problem and journey before choosing channels or tools.'],
            ['name' => 'Connected Delivery', 'text' => 'Marketing, creative, MarTech consulting and technology execution can work as one coordinated system.'],
        ],
    ],

    'engagement' => [
        'label' => 'Ways to work together',
        'title' => 'Start where the opportunity is.',
        'models' => [
            ['name' => 'Growth Partnership', 'text' => 'Always-on strategy, content, media and optimization.'],
            ['name' => 'Campaign / Launch', 'text' => 'Idea, creative, media, digital experience and measurement.'],
            ['name' => 'MarTech Transformation', 'text' => 'Journey consulting, AI/CRM/automation design and Ribelz development.'],
        ],
    ],

    'cta' => [
        'title_a' => 'Your next campaign can do more than get attention.',
        'title_b' => 'It can build an edge.',
        'contact_title' => 'Ready to find your marketing edge?',
        'contact_body' => 'Whether you need to build your brand, generate more leads, improve digital performance or introduce AI and marketing technology into your customer journey, Agency Edge can help identify the next move.',
        'signoff' => "Let's build your edge.",
        'primary' => 'Start a Conversation',
        'secondary' => 'Tell Us Your Challenge',
        'goals' => [
            'Build my brand',
            'Generate more leads',
            'Improve digital performance',
            'Introduce AI & marketing technology',
            'Launch a campaign',
            'Something else',
        ],
        'success' => "Thank you — your message is in. We'll be in touch shortly.",
    ],

    'newsletter' => [
        'title' => 'Edge notes.',
        'text' => 'Occasional thinking on marketing, MarTech and AI that does a job. No noise.',
        'success' => "You're on the list.",
    ],

    'insights' => [
        'label' => 'Insights',
        'title' => 'Thinking at the intersection of marketing and technology.',
        'empty' => 'New articles are on the way.',
    ],
];
