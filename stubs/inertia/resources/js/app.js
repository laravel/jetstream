require('./bootstrap');

require('moment');

// Import modules...
import Vue from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue';
import { InertiaForm } from 'laravel-jetstream';
import PortalVue from 'portal-vue';
import VueI18n from 'vue-i18n';

// Configure Vue...
Vue.mixin({ methods: { route } });

Vue.use(InertiaPlugin);
Vue.use(InertiaForm);
Vue.use(PortalVue);
Vue.use(VueI18n);

// Configure Vue internationalization...
const app = document.getElementById('app');

const initialPage = JSON.parse(app.dataset.page);

const i18n = new VueI18n({
    locale: initialPage.props.jetstream.locale,
    fallbackLocale: initialPage.props.jetstream.fallbackLocale,
    formatFallbackMessages: true,
    silentTranslationWarn: true,
    silentFallbackWarn: true,
});

// Create the Vue application instance...
new Vue({
    i18n,

    created() {
        this.$inertia.on('success', (event) => {
            const locale = event.detail.page.props.jetstream?.locale || i18n.locale;

            document.documentElement.lang = i18n.locale = locale;
        });
    },

    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage,
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);
