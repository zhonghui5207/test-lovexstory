import { createSSRApp } from 'vue'
import App from './App.vue'
import plugins from './plugins'
import { setupRouter } from './router'
import './styles/index.scss'
import share, { initShareEvent } from '@/utils/share'
export function createApp() {
    const app = createSSRApp(App)
    app.mixin(share)
    Promise.resolve().then(() => {
        setupRouter()
    })

    app.use(plugins)
    initShareEvent()
    return {
        app
    }
}
