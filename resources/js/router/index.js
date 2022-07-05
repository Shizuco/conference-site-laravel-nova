import VueRouter from 'vue-router'

import MainPage from '../components/MainPage'
import ConferenceDetails from '../components/ConferenceDetails.vue'
import CreateConference from '../components/CreateConference.vue'

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
    ]
})