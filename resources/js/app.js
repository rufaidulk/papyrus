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
import FlashMessage from '@smartweb/vue-flash-message';
import VueProgressBar from 'vue-progressbar'
import VueConfirmDialog from 'vue-confirm-dialog'
import {
    ValidationObserver,
    ValidationProvider,
	extend,
  	localize
} from "vee-validate";

import en from "vee-validate/dist/locale/en.json";
import * as rules from "vee-validate/dist/rules";
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'primevue/resources/primevue.min.css'
import '../sass/layout/layout.scss'

library.add(fas)

// Install VeeValidate rules and localization
Object.keys(rules).forEach(rule => {
    extend(rule, rules[rule]);
  });
  
localize("en", en);

// Install VeeValidate components globally
Vue.component("ValidationObserver", ValidationObserver);
Vue.component("ValidationProvider", ValidationProvider);

Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '2px'
})
Vue.use(VueConfirmDialog)
Vue.component('vue-confirm-dialog', VueConfirmDialog.default)
Vue.use(FlashMessage, { time: 10000 });
Vue.use(BootstrapVue)
Vue.component('InputText', InputText)
Vue.component('font-awesome-icon', FontAwesomeIcon)

Vue.config.productionTip = false
Vue.prototype.$appName = process.env.MIX_APP_NAME;
Vue.prototype.$successFlash = function (msg) {
    this.flashMessage.success({
        message: msg
    });
}
Vue.prototype.$errorFlash = function (msg) {
    this.flashMessage.error({
        message: msg
    });
}

new Vue({
    el: '#app',
    store,
    router,
    data: {},
    components:{App}
});
