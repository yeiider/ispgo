import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

createInertiaApp({
    resolve: name => {
        //const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        //return pages[`./Pages/${name}.vue`]

        return import(`./pages/${name}.vue`).then(module => module.default);

    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(VueSweetalert2)
            .mount(el);
    },
});
