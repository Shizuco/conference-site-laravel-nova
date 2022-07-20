import Vuex from 'vuex';
import Vue from 'vue';
import axios from 'axios';
import { message } from 'laravel-mix/src/Log';
Vue.use(Vuex);

export default new Vuex.Store({
    state:{
        conferences:[],
        conference:[],
        user:[],
        userOnConferenceStatus:[],
        reports: [],
        report: []
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
        },
        getUserOnConferenceStatus(state){
            return state.userOnConferenceStatus;
        },
        getReports(state){
            return state.reports;
        },
        getReport(state){
            return state.report;
        },
    },
    actions:{
        ajaxConferences({commit}){
            axios.get("api/conferences").then(response=>{
                commit('setConferences', response.data)
            }).catch(error=>{
            })
        },
        ajaxGetConference({commit}, id){
            let token = 'Bearer '+ localStorage.getItem('Authorized')
            return axios({
                method: 'get',
                url: 'api/conferences/' + id,
                headers: {
                    "Authorization": token,
                    "Content-type": "application/json; charset=UTF-8"
                  }
                }).then(response=>{
                    commit('setConference', response.data)
                }).catch(error=>{
                })
        },
        ajaxConferenceDelete({commit}, id){
            let token = 'Bearer '+ localStorage.getItem('Authorized')
            axios({
                method: 'delete',
                url: 'api/conferences/' + id,
                headers: {
                    "Authorization": token,
                    "Content-type": "application/json; charset=UTF-8"
                  }
                }).then(response=>{
                    commit('setConference', response.data)
                }).catch(error=>{
            })
        },
        ajaxConferenceCreate({commit}, data) {
            let token = 'Bearer '+ localStorage.getItem('Authorized')
            axios({
                method: 'post',
                url: 'api/conferences',
                data: data,
                headers: {
                    "Authorization": token,
                    "Content-type": "application/json; charset=UTF-8"
                }
              })
              .then(function(response) {
                commit('setConferences', response.data)
              })
              .catch(function(error) {
              });
        },
        ajaxConferenceEdit({commit}, data) {
            let token = 'Bearer '+ localStorage.getItem('Authorized')
            axios({
                method: 'put',
                url: 'api/conferences/' + data.id,
                data: data,
                headers: {
                    "Authorization": token,
                    "Content-type": "application/json; charset=UTF-8"
                }
              })
              .then(function(response) {
                commit('setConferences', response.data)
              })
              .catch(function(error) {
              });
        },
        register({commit}, data) {
            return axios({
                method: 'post',
                url: 'api/register',
                data: data,
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                  }
                })
                .then(function(response) {
                    commit('setUser', response.data)
                    localStorage.setItem('Authorized', response.data.token)
                })
        },
        auth({commit}, data) {
            return axios({
                method: 'post',
                url: 'api/login',
                data: data,
                headers: {
                    "Content-type": "application/json; charset=UTF-8"
                  }
                })
                .then(function(response) {
                    commit('setUser', response.data)
                    localStorage.setItem('Authorized', response.data.token)
                });
        },
        logout({commit}) {
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
                    commit('setUserOnConferenceStatus', response.data)
            })
        },
        changePoint({commit}, data){
            commit('setConferencePoint', data)
        },
        ajaxReports({commit}, id){
            let token = 'Bearer '+ localStorage.getItem('Authorized')
            return axios({
                method: 'get',
                url: "api/conferences/" + id + '/reports',
                headers: {
                    "Authorization": token,
                    "Content-type": "application/json; charset=UTF-8"
                }
            }).then(response=>{
                commit('setReports', response.data)
            })
        },
        ajaxGetReport({commit}, id){
            let token = 'Bearer '+ localStorage.getItem('Authorized')
            return axios({
                method: 'get',
                url: 'api/conferences/' + id[0] + '/reports/' + id[1],
                headers: {
                    "Authorization": token,
                    "Content-type": "application/json; charset=UTF-8"
                  }
                }).then(response=>{
                    commit('setReport', response.data[0])
                }).catch(error=>{
                })
        },
    },
    mutations:{
        setConferences(state, data){
            return state.conferences = data
        },
        setConferencePoint(state, data){
            state.conference.address_lat = data.address_lat
            state.conference.address_lon = data.address_lon
        },
        setConference(state, data){
            return state.conference = data
        },
        setUser(state, data){
            return state.user = data
        },
        setUserOnConferenceStatus(state, data){
            return state.userOnConferenceStatus = data
        },
        setReports(state, data){
            return state.reports = data
        },
        setReport(state, data){
            return state.report = data
        }
    }
    }
)