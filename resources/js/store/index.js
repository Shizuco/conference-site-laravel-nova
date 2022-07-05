import Vuex from 'vuex';
import Vue from 'vue';
Vue.use(Vuex);

export default new Vuex.Store({
    state:{
        conferences:[],
        conference:[]
    },
    getters:{
        getConferences(state){
            return state.conferences;
        },
        getConference(state){
            return state.conference;
        }
    },
    actions:{
        ajaxConferences({commit}){
            axios.get("api/conferences").then(response=>{
                commit('setConferences', response.data)
            }).catch(error=>{
                console.log('Error', error)
            })
        },
        ajaxGetConference({commit}, id){
            axios.get(`api/conferences/` + id).then(response=>{
                console.log(response.data)
                commit('setConference', response.data)
            }).catch(error=>{
                console.log('Error', error)
            })
        },
        ajaxConferenceDelete({commit}, id){
            axios.delete(`api/conferences/` + id).then(response=>{
                console.log(response.data)
                commit('setConference', response.data)
            }).catch(error=>{
                console.log('Error', error)
            })
        }
    },
    mutations:{
        setConferences(state, data){
            return state.conferences = data
        },
        setConference(state, data){
            return state.conference = data
        }
    }
})