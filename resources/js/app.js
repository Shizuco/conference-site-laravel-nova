/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

Vue.component('mainpage', require('./components/MainPage.vue').default);
Vue.component('details', require('./components/ConferenceDetails.vue').default);
Vue.component('create', require('./components/CreateConference.vue').default);
Vue.component('edit', require('./components/EditConference.vue').default);
Vue.component('auth', require('./components/Auth.vue').default);
Vue.component('register', require('./components/Auth/Registration.vue').default);
Vue.component('authenticate', require('./components/Auth/Auth.vue').default);
Vue.component('error403', require('./components/Errors/Error403Forbidden.vue').default);
Vue.component('ValidationProvider', ValidationProvider);
Vue.component('ValidationObserver', ValidationObserver);

import VueMask from 'v-mask'
import store from './store'
import VueRouter from 'vue-router'
import Vue from 'vue'
import router from './router/index'
import Vuetify from 'vuetify';
import 'vuetify/dist/vuetify.min.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import 'bootstrap/dist/css/bootstrap.css';
import VueSocialSharing from 'vue-social-sharing'
import { ValidationProvider} from 'vee-validate/dist/vee-validate.full.esm'
import {ValidationObserver} from 'vee-validate'

Vue.use(VueSocialSharing)
Vue.use(Vuetify)
Vue.use(VueRouter)
Vue.use(VueMask)

const app = new Vue({
    el: '#app',
    store,
    router,
    vuetify : new Vuetify()
});
