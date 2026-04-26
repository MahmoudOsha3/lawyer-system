import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { Toaster } from 'vue-sonner'
import 'vue-sonner/style.css'

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
        return pages[`./Pages/${name}.vue`]
    },
    setup({ el, App, props, plugin }) {
        createApp({
            render: () => h('div', [
                h(Toaster, {
                    position: 'top-right',
                    richColors: true,
                    closeButton: true,
                    duration: 3000,
                }),
                h(App, props)
            ])
        })
        .use(plugin)
        .mount(el)
    },
})
