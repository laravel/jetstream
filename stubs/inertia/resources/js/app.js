require('./bootstrap');

// Import modules...
import Vue from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue';
import PortalVue from 'portal-vue';

window.prop = (keys, defaultProp = null) => {
    const prop = keys.split('.').reduce((object, key) => (object || {})[key], vueApp.$page.props);

    return prop ?? defaultProp ?? null;
};

Vue.mixin({ methods: { route, prop } });
Vue.use(InertiaPlugin);
Vue.use(PortalVue);

const app = document.getElementById('app');

window.vueApp = new Vue({
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);
