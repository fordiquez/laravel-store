import './bootstrap'
import '../css/app.css'

import { createApp, DefineComponent, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from 'ziggy-js';
import { library, dom } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { fas } from '@fortawesome/free-solid-svg-icons'
import { far } from '@fortawesome/free-regular-svg-icons'
import { fab } from '@fortawesome/free-brands-svg-icons'
import Vue3Toasity, { type ToastContainerOptions } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'

library.add(fas, far, fab)
dom.watch()

const appName = import.meta.env.VITE_APP_NAME || 'brandford.'

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .component('font-awesome-icon', FontAwesomeIcon)
            .use(plugin)
            .use(ZiggyVue)
            .use(Vue3Toasity, { autoClose: 3000 } as ToastContainerOptions)
            .mount(el)
    },
    progress: {
        color: '#6366f1'
    }
})
