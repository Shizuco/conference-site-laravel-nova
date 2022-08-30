import Vuex from "vuex";
import Vue from "vue";
import axios from "axios";
Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        conferences: [],
        conference: [],
        user: [],
        userOnConferenceStatus: [],
        reportInFavoriteStatus: [],
        reports: [],
        report: [],
        file: [],
        comments: [],
        favorites: [],
        categories: [],
        categoryConferences: "",
        categoryReports: "",
        rootCategories: [],
        subCategories: [],
        currentCategory: [],
        meeting: []
    },
    getters: {
        getConferences(state) {
            return state.conferences;
        },
        getMeeting(state) {
            return state.meeting;
        },
        getConference(state) {
            return state.conference;
        },
        getUser(state) {
            return state.user;
        },
        getUserOnConferenceStatus(state) {
            return state.userOnConferenceStatus;
        },
        getReports(state) {
            return state.reports;
        },
        getReport(state) {
            return state.report;
        },
        getFile(state) {
            return state.file;
        },
        getComments(state) {
            return state.comments;
        },
        getFavorites(state) {
            return state.favorites;
        },
        getReportStatus(state) {
            return state.reportInFavoriteStatus;
        },
        getCategories(state) {
            return state.categories;
        },
        getCategoryConferences(state) {
            return state.categoryConferences;
        },
        getCategoryReports(state) {
            return state.categoryReports;
        },
        getRootCategories(state) {
            return state.rootCategories;
        },
        getSubCategories(state) {
            return state.subCategories;
        },
        getCurrentCategory(state) {
            return state.currentCategory;
        },
    },
    actions: {
        ajaxGetMeeting({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "get",
                url: "api/meeting/" + id,
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then((response) => {
                commit("setMeeting", response.data);
            });
        },
        ajaxConferences({ commit }, data) {
            return axios
                .get(
                    "api/conferences?page=" +
                        data[0] +
                        "&numberOfReports=" +
                        data[1] +
                        "&categories=" +
                        data[2] +
                        "&dateFrom=" +
                        data[3] +
                        "&dateTo=" +
                        data[4]
                )
                .then((response) => {
                    commit("setConferences", response.data);
                });
        },
        ajaxConferencesCsv({ commit }) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "get",
                url: "api/conferencesCsv",
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then((response) => {
                commit("setFile", response.config.url);
            });
        },
        ajaxGetConferenceByName({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "get",
                url:
                    "api/conferencesByName?page=" +
                    data[0] +
                    "&conf_title=" +
                    data[1],
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then((response) => {
                commit("setConferences", response.data);
            });
        },
        ajaxGetReportsByName({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "get",
                url:
                    "api/reportsByName?page=" +
                    data[0] +
                    "&rep_title=" +
                    data[1],
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then((response) => {
                commit("setReports", response.data);
            });
        },
        ajaxGetConference({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "get",
                url: "api/conferences/" + id,
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then((response) => {
                commit("setConference", response.data);
            });
        },
        ajaxConferenceDelete({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            axios({
                method: "delete",
                url: "api/conferences/" + id,
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then((response) => {
                commit("setConference", response.data);
            });
        },
        ajaxConferenceCreate({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            axios({
                method: "post",
                url: "api/conferences",
                data: data,
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then(function (response) {
                commit("setConferences", response.data);
            });
        },
        ajaxConferenceEdit({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            axios({
                method: "put",
                url: "api/conferences/" + data.id,
                data: data,
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then(function (response) {
                commit("setConferences", response.data);
            });
        },
        register({ commit }, data) {
            return axios({
                method: "post",
                url: "api/register",
                data: data,
                headers: {
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then(function (response) {
                commit("setUser", response.data);
                localStorage.setItem("Authorized", response.data.token);
            });
        },
        auth({ commit }, data) {
            return axios({
                method: "post",
                url: "api/login",
                data: data,
                headers: {
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then(function (response) {
                commit("setUser", response.data);
                localStorage.setItem("Authorized", response.data.token);
            });
        },
        changeUserData({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "put",
                url: "api/user/change",
                data: data,
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then(function (response) {
                commit("setUser", response.data);
                localStorage.setItem("Authorized", response.data.token);
            });
        },
        ajaxGetFavorites({ commit }) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "get",
                url: "api/favorite",
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then((response) => {
                commit("setFavorites", response.data);
            });
        },
        ajaxAddToFavorites({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "post",
                url: "api/favorite/" + id,
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            });
        },
        ajaxDeleteFromFavorites({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "post",
                url: "api/unfavorite/" + id,
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            });
        },
        logout({ commit }) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            axios({
                method: "post",
                url: "api/logout",
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            });
            localStorage.clear();
        },
        userConferenceJoin({ commit }, conference_id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            axios({
                method: "post",
                url: "/api/conferenceJoin/" + conference_id,
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            });
        },
        userConferenceOut({ commit }, conference_id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            axios({
                method: "post",
                url: "/api/conferenceOut/" + conference_id,
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            });
        },
        ajaxUser({ commit }) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            axios({
                method: "get",
                url: "api/user",
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then((response) => {
                commit("setUser", response.data);
            });
        },
        isReportInFavorite({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            axios({
                method: "get",
                url: "api/isFavorite/" + id,
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then((response) => {
                commit("setOnFavorite", response.data);
            });
        },
        isUserOnConference({ commit }, conference_id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            axios({
                method: "post",
                url: "api/isOnConference/" + conference_id,
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then((response) => {
                commit("setUserOnConferenceStatus", response.data);
            });
        },
        changePoint({ commit }, data) {
            commit("setConferencePoint", data);
        },
        ajaxReports({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "get",
                url:
                    "api/conferences/" +
                    data[1] +
                    "/reports?page=" +
                    data[0] +
                    "&duration=" +
                    data[2] +
                    "&categories=" +
                    data[3] +
                    "&start_time=" +
                    data[4] +
                    "&end_time=" +
                    data[5],
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then((response) => {
                commit("setReports", response.data);
            });
        },
        ajaxGetReport({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "get",
                url: "api/conferences/" + id[0] + "/reports/" + id[1],
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then((response) => {
                commit("setReport", response.data[0]);
            });
        },
        ajaxCreateReport({ commit }, data) {
            let datas = new FormData();
            datas.append("presentation", data[0].presentation);
            datas.append("thema", data[0].thema);
            datas.append("start_time", data[0].start_time);
            datas.append("end_time", data[0].end_time);
            datas.append("description", data[0].description);
            datas.append("category_id", data[0].category_id);
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "post",
                url: "api/conferences/" + data[1] + "/reports",
                data: datas,
                headers: {
                    Authorization: token,
                    "Content-type":
                        "application/vnd.openxmlformats-officedocument.presentationml.presentation;",
                },
            }).then(function (response) {
                commit("setReport", response.data);
            });
        },
        ajaxEditReport({ commit }, data) {
            let datas = new FormData();
            datas.append("presentation", data[0].presentation);
            datas.append("thema", data[0].thema);
            datas.append("start_time", data[0].start_time);
            datas.append("end_time", data[0].end_time);
            datas.append("description", data[0].description);
            datas.append("_method", "PUT");
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "post",
                url: "api/conferences/" + data[1] + "/reports/" + data[2],
                data: datas,
                headers: {
                    Authorization: token,
                    "Content-type":
                        "multipart/form-data; boundary=<calculated when request is sent>",
                },
            }).then((response) => {
                commit("setReport", response.data);
            });
        },
        ajaxReportDelete({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            axios({
                method: "delete",
                url: "api/conferences/" + data[0] + "/reports/" + data[1],
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            });
        },
        ajaxGetReportFile({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "get",
                url:
                    "api/conferences/" +
                    data[0] +
                    "/reports/" +
                    data[1] +
                    "/file",
                headers: {
                    Authorization: token,
                    "Content-type": "application/json",
                },
            }).then((response) => {
                commit("setFile", response.config.url);
            });
        },
        ajaxGetComments({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "get",
                url: "api/conferences/report/" + data[0] + "/comment",
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then((response) => {
                commit("setComments", response.data);
            });
        },
        ajaxSendComment({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "post",
                url:
                    "api/conferences/" +
                    data[0] +
                    "/report/" +
                    data[1] +
                    "/comment",
                data: data[2],
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            });
        },
        ajaxSetComment({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "put",
                url:
                    "api/conferences/" +
                    data[0] +
                    "/report/" +
                    data[1] +
                    "/comment/" +
                    data[2],
                data: data[3],
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            });
        },
        ajaxGetCategories({ commit }) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "get",
                url: "api/categories",
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then((response) => {
                commit("setCategories", response.data);
            });
        },
        ajaxCreateCategory({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            axios({
                method: "post",
                url: "api/categories",
                data: data,
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            });
        },
        ajaxDeleteCategory({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            axios({
                method: "delete",
                url: "api/category/destroy",
                data: id,
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            });
        },
        ajaxGetCategoryConferenceNumber({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "get",
                url: "api/category/" + id + "/getConferences",
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then((response) => {
                commit("setConferences", response.data);
            });
        },
        ajaxGetCategoryReportNumber({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "get",
                url: "api/category/" + id + "/getReports",
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then((response) => {
                commit("setReports", response.data);
            });
        },
        ajaxGetRootCategories({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "get",
                url: "api/rootCategories",
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then((response) => {
                commit("setRootCategories", response.data);
            });
        },
        ajaxGetSubCategories({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "get",
                url: "api/subCategories/" + id,
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then((response) => {
                commit("setSubCategories", response.data);
            });
        },
        ajaxGetCurrentCategory({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            return axios({
                method: "get",
                url: "api/currentCategory/" + id,
                headers: {
                    Authorization: token,
                    "Content-type": "application/json; charset=UTF-8",
                },
            }).then((response) => {
                commit("setCurrentCategory", response.data);
            });
        },
    },
    mutations: {
        setConferences(state, data) {
            return (state.conferences = data);
        },
        setConferencePoint(state, data) {
            state.conference.address_lat = data.address_lat;
            state.conference.address_lon = data.address_lon;
        },
        setConference(state, data) {
            return (state.conference = data);
        },
        setUser(state, data) {
            return (state.user = data);
        },
        setUserOnConferenceStatus(state, data) {
            return (state.userOnConferenceStatus = data);
        },
        setReports(state, data) {
            return (state.reports = data);
        },
        setReport(state, data) {
            return (state.report = data);
        },
        setFile(state, data) {
            return (state.file = data);
        },
        setComments(state, data) {
            return (state.comments = data);
        },
        setFavorites(state, data) {
            return (state.favorites = data);
        },
        setOnFavorite(state, data) {
            return (state.reportInFavoriteStatus = data);
        },
        setCategories(state, data) {
            return (state.categories = data);
        },
        setCategoryConferences(state, data) {
            return (state.categoryConferences = data);
        },
        setCategoryReports(state, data) {
            return (state.categoryReports = data);
        },
        setRootCategories(state, data) {
            return (state.rootCategories = data);
        },
        setSubCategories(state, data) {
            return (state.subCategories = data);
        },
        setCurrentCategory(state, data) {
            return (state.currentCategory = data);
        },
        setMeeting(state, data){
            return (state.meeting = data);
        }
    },
});
