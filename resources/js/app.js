import './bootstrap';

import { createApp, h } from 'vue';
import VueLazyload from 'vue-lazyload'
import { createInertiaApp} from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

import DefaultLayout from './Layouts/DefaultLayout.vue';

createInertiaApp({
    progress: {
        color: '#29d',
    },
    resolve: (name) => {
        let page = resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue'));

        page.then((module) => {
            module.default.layout = module.default.layout || DefaultLayout;
        });

        return page;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .mixin({ methods: {route}})
            .use(plugin)
            .use(VueLazyload)
            .mount(el)

        // Hide preloader after app setup
        document.getElementById('preloader-custom').style.display = 'none'
    },
});
