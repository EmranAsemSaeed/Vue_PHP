// import { createApp } from 'vue'
// import App from './App.vue'
// import router from './router'
// import store from './store'
// import './assets/tailwind.css'

// import '@fortawesome/fontawesome-free/css/all.min.css'

// createApp(App)
//   .use(store)
//   .use(router)
//   .mount('#app')
// frontend/src/main.js
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import './index.css'

createApp(App).use(router).mount('#app')