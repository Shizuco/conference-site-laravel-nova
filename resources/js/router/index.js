import VueRouter from 'vue-router'

import MainPage from '../components/MainPage'
import ConferenceDetails from '../components/ConferenceDetails.vue'
import CreateConference from '../components/CreateConference.vue'
import EditConference from '../components/EditConference.vue'
import Registration from '../components/Auth/Registration.vue'
import Auth from '../components/Auth/Auth.vue'

function isAuth(){
    if("Authorized" in localStorage){
        return true
    }
    else{
        return false
    }
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
        }
    ]
})