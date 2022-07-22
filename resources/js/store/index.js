import Vuex from 'vuex';
import Vue from 'vue';
import axios from 'axios';
Vue.use(Vuex);

export default new Vuex.Store({
    state:{
        conferences:[],
        conference:[],
        user:[],
        userOnConferenceStatus:[],
        reports: [],
        report: [],
        file: []
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
        getFile(state){
            return state.file
        }
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
                    commit('setReport', response.data[id[1] - 1])
                }).catch(error=>{
                })
        },
        ajaxCreateReport({commit}, data){
            let datas = new FormData();
            datas.append("presentation", data[0].presentation)
            datas.append("thema", data[0].thema);
            datas.append("start_time", data[0].start_time);
            datas.append("end_time", data[0].end_time);
            datas.append("description", data[0].description);
            let token = 'Bearer '+ localStorage.getItem('Authorized')
            return axios({
                method: 'post',
                url: 'api/conferences/' + data[1] + '/reports',
                data: datas,
                headers: {
                    "Authorization": token,
                    "Content-type": "multipart/form-data; boundary=<calculated when request is sent>"
                  }
                }).then(response=>{
                    commit('setReports', response.data)
                }).catch(error=>{
                    console.log(error.response);
                })
        },
        ajaxEditReport({commit}, data){
            let datas = new FormData();
            datas.append("presentation", data[0].presentation)
            datas.append("thema", data[0].thema);
            datas.append("start_time", data[0].start_time);
            datas.append("end_time", data[0].end_time);
            datas.append("description", data[0].description);
            datas.append("_method", 'PUT');
            let token = 'Bearer '+ localStorage.getItem('Authorized')
            return axios({
                method: 'post',
                url: 'api/conferences/' + data[1] + '/reports/' + data[2],
                data: datas,
                headers: {
                    "Authorization": token,
                    "Content-type": "multipart/form-data; boundary=<calculated when request is sent>"
                  }
                }).then(response=>{
                    commit('setReports', response.data)
                }).catch(error=>{
                    console.log(error.response);
                })
        },
        ajaxGetReportFile({commit}, data){
            let token = 'Bearer '+ localStorage.getItem('Authorized')
            return axios({
                method: 'get',
                url: 'api/conferences/' + data[0] + '/reports/' + data[1] + '/file',
                headers: {
                    "Authorization": token,
                    "Content-type": "application/json; charset=UTF-8"
                  }
                }).then(response=>{
                    commit('setFile', response.data)
                }).catch(error=>{
                    console.log(error.response);
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
        },
        setFile(state, data){
            return state.file = data
        }
    }
    }
)