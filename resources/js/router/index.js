import VueRouter from 'vue-router'

import MainPage from '../components/MainPage'
import ConferenceDetails from '../components/ConferenceDetails.vue'
import CreateConference from '../components/CreateConference.vue'
import EditConference from '../components/EditConference.vue'
import Registration from '../components/Auth/Registration.vue'
import Auth from '../components/Auth/Auth.vue'
import List from '../components/Report/List.vue'
import Details from '../components/Report/Details.vue'
import Create from '../components/Report/Create.vue'
import Edit from '../components/Report/Create.vue'

function isAuth(){
   return ("Authorized" in localStorage) ? true : false
}

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
            beforeEnter(to, from, next){
                if(!isAuth()){
                    next('/conferences')
                }    
                else{
                    next()
                }
            },
            component: ConferenceDetails,
            name: 'ConferenceDetails'
        },
        {
            path:'/conferences',
            beforeEnter(to, from, next){
                if(!isAuth()){
                    next('/conferences')
                }    
                else{
                    next()
                }
            },
            component: CreateConference,
            name: 'CreateConference'
        },
        {
            path:'/conferences/:id',
            beforeEnter(to, from, next){
                if(!isAuth()){
                    next('/conferences')
                }    
                else{
                    next()
                }
            },
            component: EditConference,
            name: 'EditConference'
        },
        {
            path:'/register',
            beforeEnter(to, from, next){
                if(isAuth()){
                    next('/conferences')
                }    
                else{
                    next()
                }
            },
            component: Registration,
            name: 'Registration'
        },
        {
            path:'/login',
            component: Auth,
            name: 'Auth',
            beforeEnter(to, from, next){
                if(isAuth()){
                    next('/conferences')
                }    
                else{
                    next()
                }
            },
        },
        {
            path:'/conferences/:id/reports',
            component: List,
            name: 'List',
            beforeEnter(to, from, next){
                if(!isAuth()){
                    next('/conferences')
                }    
                else{
                    next()
                }
            },
        },
        {
            path:'/conferences/:id/reports/:rep_id',
            beforeEnter(to, from, next){
                if(!isAuth()){
                    next('/conferences')
                }    
                else{
                    next()
                }
            },
            component: Details,
            name: 'Details',
        },
        {
            path:'/conferences/:id/reports',
            beforeEnter(to, from, next){
                if(!isAuth()){
                    next('/conferences')
                }    
                else{
                    next()
                }
            },
            component: Create,
            name: 'Create',
        },
        {
            path:'/conferences/:id/reports/:rep_id',
            beforeEnter(to, from, next){
                if(!isAuth()){
                    next('/conferences')
                }    
                else{
                    next()
                }
            },
            component: Edit,
            name: 'Edit',
        }
    ]
})