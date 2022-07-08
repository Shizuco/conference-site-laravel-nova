import VueRouter from 'vue-router'

import MainPage from '../components/MainPage'
import ConferenceDetails from '../components/ConferenceDetails.vue'
import CreateConference from '../components/CreateConference.vue'
import EditConference from '../components/EditConference.vue'
import Registration from '../components/Auth/Registration.vue'
import Auth from '../components/Auth/Auth.vue'

export default new VueRouter({
    routes:[
        {
            path:'/conferences',
            alias: '/',
            component: MainPage,
            name: 'MainPage'
        },
        {
            path:'/conferences/:id',
            component: ConferenceDetails,
            name: 'ConferenceDetails'
        },
        {
            path:'/conferences',
            component: CreateConference,
            name: 'CreateConference'
        },
        {
            path:'/conferences/:id',
            component: EditConference,
            name: 'EditConference'
        },
        {
            path:'/register',
            component: Registration,
            name: 'Registration'
        },
        {
            path:'/login',
            component: Auth,
            name: 'Auth'
        }
    ]
})