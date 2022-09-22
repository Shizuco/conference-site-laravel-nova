/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue").default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

Vue.component("mainpage", require("./components/MainPage.vue").default);
Vue.component("details", require("./components/ConferenceDetails.vue").default);
Vue.component("auth", require("./components/Auth.vue").default);
Vue.component(
    "register",
    require("./components/Auth/Registration.vue").default
);
Vue.component("authenticate", require("./components/Auth/Auth.vue").default);
Vue.component("list", require("./components/Report/List.vue").default);
Vue.component("detail", require("./components/Report/Details.vue").default);
Vue.component("create", require("./components/Report/Create.vue").default);
Vue.component("edition", require("./components/Report/Edit.vue").default);
Vue.component("userpage", require("./components/Auth/UserPage.vue").default);
Vue.component(
    "userfavorites",
    require("./components/Auth/UserFavorites.vue").default
);
Vue.component(
    "plans",
    require("./components/PaymentPlan/AllPlans.vue").default
);
Vue.component(
    "d-plan",
    require("./components/PaymentPlan/DefaultPlan.vue").default
);
Vue.component(
  "f-plan",
  require("./components/PaymentPlan/FiveJoinPlan.vue").default
);
Vue.component(
  "ft-plan",
  require("./components/PaymentPlan/FivetyJoinPlan.vue").default
);
Vue.component(
  "u-plan",
  require("./components/PaymentPlan/UnlimitedPlan.vue").default
);
Vue.component(
  "success",
  require("./components/PaymentPlan/Success.vue").default
);
Vue.component("skeleton", require("./components/Skeleton.vue").default);
Vue.component("ValidationProvider", ValidationProvider);
Vue.component("ValidationObserver", ValidationObserver);
Vue.component("Pagination", LaravelVuePagination);
Vue.component("Slide", Slide);
Vue.component("vue-skeleton-loader", VueSkeletonLoader);

import VueMask from "v-mask";
import store from "./store";
import VueRouter from "vue-router";
import Vue from "vue";
import router from "./router/index";
import Vuetify from "vuetify";
import "vuetify/dist/vuetify.min.css";
import "bootstrap-vue/dist/bootstrap-vue.css";
import "bootstrap/dist/css/bootstrap.css";
import VueSocialSharing from "vue-social-sharing";
import { ValidationProvider } from "vee-validate/dist/vee-validate.full.esm";
import { ValidationObserver } from "vee-validate";
import * as VueGoogleMaps from "vue2-google-maps";
import VueGeolocation from "vue-browser-geolocation";
import VCalendar from "v-calendar";
import DatetimePicker from "vuetify-datetime-picker";
import VTreeview from "v-treeview";
import VueBreadcrumbs from "vue-breadcrumbs";
import { Slide } from "vue-burger-menu";
import LaravelVuePagination from "laravel-vue-pagination";
import VueSkeletonLoader from "skeleton-loader-vue";
import { VueEditor } from "vue2-editor";

Vue.use(VueBreadcrumbs);
Vue.use(DatetimePicker);
Vue.use(VueSocialSharing);
Vue.use(Vuetify);
Vue.use(VueRouter);
Vue.use(VueMask);
Vue.use(VCalendar, {
    componentPrefix: "vc",
});
Vue.use(VueGeolocation);
Vue.use(VueGoogleMaps, {
    load: {
        key: "AIzaSyAWYpOvTuAYKad3lZf-c_RIvRz9wcEA1Ws",
        libraries: "places",
    },
    installComponents: true,
});
const app = new Vue({
    el: "#app",
    store,
    router,
    vuetify: new Vuetify(),
    VueGoogleMaps,
    VTreeview,
});
