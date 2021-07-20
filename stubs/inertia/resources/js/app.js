require('./bootstrap');

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

// By default, a fresh Laravel Jetstream application has the Application Name configured as the title tag.
// Let's grab this before Inertia gets a chance to overwrite it, and store it's value for later usage.
// When this isn't available, we'll instead use the static value of 'Laravel'.
const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

// Next, we'll initialize our Inertia.js client-side application, for which the
// documentation can be found at https://inertiajs.com/client-side-setup
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .mixin({ methods: { route } })
            .mount(el);
    },
});

// Finally, we'll initialize Inertia's NProgress-based progress bar plugin, for which
// the documentation can be found at https://inertiajs.com/progress-indicators
InertiaProgress.init({ color: '#4B5563' });
