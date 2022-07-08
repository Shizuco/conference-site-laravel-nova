import Vuex from 'vuex';
import Vue from 'vue';
import axios from 'axios';
Vue.use(Vuex);

export default new Vuex.Store({
    state:{
        conferences:[],
        conference:[],
        user:[]
    },
    getters:{
        getConferences(state){
            return state.conferences;
        },
        getConference(state){
            return state.conference;
        },
        getUser(state){
            return state.user;
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
        },
        ajaxConferenceCreate({commit}, data) {
            axios({
                method: 'post',
                url: 'api/conferences',
                data: {
                    title: data.title,
                    date : data.date,
                    time : data.time,
                    address_lat : data.address_lat,
                    address_lon : data.address_lon,
                    country : data.country
                },
                headers: {
                  "Content-type": "application/json; charset=UTF-8"
                }
              })
              .then(function(response) {
                commit('setConferences', response.data)
                console.log('Ответ сервера успешно получен!');
                console.log(response.data);
              })
              .catch(function(error) {
                console.log(error);
              });
        },
        ajaxConferenceEdit({commit}, data) {
            axios({
                method: 'put',
                url: 'api/conferences/' + data.id,
                data: {
                    id: data.id,
                    title: data.title,
                    date : data.date,
                    time : data.time,
                    address_lat : data.address_lat,
                    address_lon : data.address_lon,
                    country : data.country
                },
                headers: {
                  "Content-type": "application/json; charset=UTF-8"
                }
              })
              .then(function(response) {
                commit('setConferences', response.data)
                console.log('Ответ сервера успешно получен!');
                console.log(response.data);
              })
              .catch(function(error) {
                console.log(error);
              });
        },
        REGISTER({commit}, data) {
            axios({
                method: 'post',
                url: 'api/register',
                data:{
                    name: data.name,
                    surname: data.surname,
                    password: data.password,
                    password_confirmation: data.password_confirmation,
                    country: data.country,
                    birthday: data.birthday,
                    phone: data.phone,
                    role: data.role,
                    email: data.email
                },
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                  }
                })
                .then(function(response) {
                    commit('setUser', response.data)
                    localStorage.setItem('Authorized', response.data.token)
                })
                .catch(function(error) {
                    console.log(error);
            });
        },
        AUTH({commit}, data) {
            axios({
                method: 'post',
                url: 'api/login',
                data:{
                    password: data.password,
                    email: data.email
                },
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                  }
                })
                .then(function(response) {
                    commit('setUser', response.data)
                    localStorage.setItem('Authorized', response.data.token)
                })
                .catch(function(error) {
                    console.log(response.data);
                    console.log(error);
            });
        },
        LOGOUT({commit}) {
            let token = 'Bearer '+ localStorage.getItem('Authorized')
            axios({
                method: 'post',
                url: 'api/logout',
                headers: {
                    "Authorization": token,
                    "Content-type": "application/json; charset=UTF-8"
                  }
                })
            localStorage.clear()
        },
        userConferenceJoin({commit}, conference_id){
            let token = 'Bearer '+ localStorage.getItem('Authorized')
            axios({
                method: 'post',
                url: '/api/conferenceJoin/' + conference_id,
                headers: {
                    "Authorization": token,
                    "Content-type": "application/json; charset=UTF-8"
                }
            }).then(response=>{
                console.log(response.data)
            })
        },
        userConferenceOut({commit}, conference_id){
            let token = 'Bearer '+ localStorage.getItem('Authorized')
            axios({
                method: 'post',
                url: '/api/conferenceOut/' + conference_id,
                headers: {
                    "Authorization": token,
                    "Content-type": "application/json; charset=UTF-8"
                }
            }).then(response=>{
                console.log(response.data)
            })
        },
        ajaxUser({commit}){
            let token = 'Bearer '+ localStorage.getItem('Authorized')
            axios({
                method: 'get',
                url: 'api/user',
                headers: {
                    "Authorization": token,
                    "Content-type": "application/json; charset=UTF-8"
                  }
                })
                .then((response)=>{
                    commit('setUser', response.data)
            })
        },
        isUserOnConference({commit},conference_id){
            let token = 'Bearer '+ localStorage.getItem('Authorized')
            axios({
                method: 'post',
                url: 'api/isOnConference/' + conference_id,
                headers: {
                    "Authorization": token,
                    "Content-type": "application/json; charset=UTF-8"
                  }
                })
                .then((response)=>{
                    commit('setUser', response.data)
                    console.log(response.data)
            })
        }
    },
    mutations:{
        setConferences(state, data){
            return state.conferences = data
        },
        setConference(state, data){
            return state.conference = data
        },
        setUser(state, data){
            return state.user = data
        }
    }
    }
)