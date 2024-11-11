import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/inertia-vue3';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import {__} from './translation.js';

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./pages/**/*.vue', { eager: true })
        return pages[`./pages/${name}.vue`]

        //return import(`./pages/${name}.vue`).then(module => module.default);
    },
    setup({el, App, props, plugin}) {
        const app = createApp({ render: () => h(App, props) });
        app.config.globalProperties.__ = __;

        createApp({render: () => h(App, props)})
            .use(plugin)
            .use(VueSweetalert2)
            .mount(el);
    },
    progress: {
        color: '#29d',
        includeCSS: true,
        showSpinner: true,
    }
});
