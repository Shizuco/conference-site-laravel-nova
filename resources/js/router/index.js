import VueRouter from "vue-router";

import MainPage from "../components/MainPage";
import ConferenceDetails from "../components/ConferenceDetails.vue";
import Registration from "../components/Auth/Registration.vue";
import Auth from "../components/Auth/Auth.vue";
import List from "../components/Report/List.vue";
import Details from "../components/Report/Details.vue";
import Create from "../components/Report/Create.vue";
import Edit from "../components/Report/Edit.vue";
import UserPage from "../components/Auth/UserPage.vue";
import UserFavorites from "../components/Auth/UserFavorites.vue";
import AllPlans from "../components/PaymentPlan/AllPlans.vue";
import DefaultPlan from "../components/PaymentPlan/DefaultPlan.vue";

function isAuth() {
    return "Authorized" in localStorage ? true : false;
}

export default new VueRouter({
    routes: [
        {
            path: "/defaultPlan",
            component: DefaultPlan,
            name: "DefaultPlan",
            beforeEnter(to, from, next) {
                if (!isAuth()) {
                    next("/conferences");
                } else {
                    next();
                }
            },
        },
        {
            path: "/plans",
            component: AllPlans,
            name: "AllPlans",
            beforeEnter(to, from, next) {
                if (!isAuth()) {
                    next("/conferences");
                } else {
                    next();
                }
            },
        },
        {
            path: "/conferences",
            alias: "/",
            component: MainPage,
            name: "MainPage",
        },
        {
            path: "/conferences/:id",
            beforeEnter(to, from, next) {
                if (!isAuth()) {
                    next("/conferences");
                } else {
                    next();
                }
            },
            component: ConferenceDetails,
            name: "ConferenceDetails",
        },
        {
            path: "/register",
            beforeEnter(to, from, next) {
                if (isAuth()) {
                    next("/conferences");
                } else {
                    next();
                }
            },
            component: Registration,
            name: "Registration",
        },
        {
            path: "/login",
            component: Auth,
            name: "Auth",
            beforeEnter(to, from, next) {
                if (isAuth()) {
                    next("/conferences");
                } else {
                    next();
                }
            },
        },
        {
            path: "/conferences/:id/reports",
            component: List,
            name: "List",
            beforeEnter(to, from, next) {
                if (!isAuth()) {
                    next("/conferences");
                } else {
                    next();
                }
            },
        },
        {
            path: "/conferences/:id/reports/:rep_id",
            beforeEnter(to, from, next) {
                if (!isAuth()) {
                    next("/conferences");
                } else {
                    next();
                }
            },
            component: Details,
            name: "Details",
        },
        {
            path: "/conferences/:id/reports",
            beforeEnter(to, from, next) {
                if (!isAuth()) {
                    next("/conferences");
                } else {
                    next();
                }
            },
            component: Create,
            name: "Create",
        },
        {
            path: "/conferences/:id/reports/:r_id",
            beforeEnter(to, from, next) {
                if (!isAuth()) {
                    next("/conferences");
                } else {
                    next();
                }
            },
            component: Edit,
            name: "Edit",
        },
        {
            path: "/user",
            beforeEnter(to, from, next) {
                if (!isAuth()) {
                    next("/conferences");
                } else {
                    next();
                }
            },
            component: UserPage,
            name: "UserPage",
        },
        {
            path: "/user/favorites",
            beforeEnter(to, from, next) {
                if (!isAuth()) {
                    next("/conferences");
                } else {
                    next();
                }
            },
            component: UserFavorites,
            name: "UserFavorites",
        },
    ],
});
