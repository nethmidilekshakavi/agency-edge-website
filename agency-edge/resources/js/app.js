import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { installDirectives } from './lib/directives';
import SiteLayout from './Layouts/SiteLayout.vue';
import AdminLayout from './Layouts/AdminLayout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Agency Edge';

createInertiaApp({
    title: (title) => (title ? `${title} — ${appName}` : `${appName} — Marketing Meets Technology`),
    resolve: async (name) => {
        const page = await resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'));
        // Persistent layouts: the site shell (cursor, smooth scroll, header)
        // survives page visits so transitions stay seamless.
        if (page.default.layout === undefined) {
            if (name === 'Admin/Login') page.default.layout = null;
            else page.default.layout = name.startsWith('Admin/') ? AdminLayout : SiteLayout;
        }
        return page;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        app.use(plugin);
        installDirectives(app);
        app.mount(el);
    },
    progress: { color: '#ffc41c', showSpinner: false, delay: 250 },
});
