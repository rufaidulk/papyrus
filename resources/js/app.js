require('./bootstrap')

import Vue from 'vue'
import App from './App.vue'
import store from './store'
import router from './router'
import InputText from 'primevue/inputtext'
import BootstrapVue from 'bootstrap-vue'
import { library } from '@fortawesome/fontawesome-svg-core'
import { fas } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'primevue/resources/primevue.min.css'
import '../sass/layout/layout.scss'

library.add(fas)

Vue.use(BootstrapVue)
Vue.component('InputText', InputText)
Vue.component('font-awesome-icon', FontAwesomeIcon)

Vue.config.productionTip = false
let appName = process.env.MIX_APP_NAME;

new Vue({
    el: '#app',
    store,
    router,
    data: {
        appName: appName
    },
    components:{App}
});
