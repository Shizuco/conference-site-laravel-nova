import Vuex from "vuex";
import Vue from "vue";
import Api from "../api/api";
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
    },
    getters: {
        getConferences(state) {
            return state.conferences;
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
        ajaxConferences({ commit }, data) {
            let url =
                "api/conferences?page=" +
                data[0] +
                "&numberOfReports=" +
                data[1] +
                "&categories=" +
                data[2] +
                "&dateFrom=" +
                data[3] +
                "&dateTo=" +
                data[4];
            let headers = {
                "Content-type": "application/json; charset=UTF-8",
            };
            let conferences = new Api(url, 0, headers);
            return conferences.get(url, headers).then((response) => {
                commit("setConferences", response.data);
            });
        },
        ajaxConferencesCsv({ commit }) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/conferencesCsv";
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let csv = new Api(url, 0, headers);
            return csv.get(url, headers).then((response) => {
                commit("setFile", response.config.url);
            });
        },
        ajaxGetConferenceByName({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url =
                "api/conferencesByName?page=" +
                data[0] +
                "&conf_title=" +
                data[1];
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let conferences = new Api(url, 0, headers);
            return conferences.get(url, headers).then((response) => {
                commit("setConferences", response.data);
            });
        },
        ajaxGetReportsByName({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url =
                "api/reportsByName?page=" + data[0] + "&rep_title=" + data[1];
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let reports = new Api(url, 0, headers);
            return reports.get(url, headers).then((response) => {
                commit("setReports", response.data);
            });
        },
        ajaxGetConference({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/conferences/" + id;
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let conference = new Api(url, 0, headers);
            return conference.get(url, headers).then((response) => {
                commit("setConference", response.data);
            });
        },
        ajaxConferenceDelete({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/conferences/" + id;
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let conference = new Api(url, 0, headers);
            return conference.delete(url, headers).then((response) => {
                commit("setConference", response.data);
            });
        },
        ajaxConferenceCreate({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/conferences";
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let conference = new Api(url, data, headers);
            return conference
                .post(url, data, headers)
                .then(function (response) {
                    commit("setConferences", response.data);
                });
        },
        ajaxConferenceEdit({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/conferences/" + data.id;
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let conference = new Api(url, data, headers);
            return conference.put(url, data, headers).then(function (response) {
                commit("setConferences", response.data);
            });
        },
        register({ commit }, data) {
            let url = "api/register";
            let headers = {
                "Content-type": "application/json; charset=UTF-8",
            };
            let user = new Api(url, data, headers);
            return user.post(url, data, headers).then(function (response) {
                commit("setUser", response.data);
                localStorage.setItem("Authorized", response.data.token);
            });
        },
        auth({ commit }, data) {
            let url = "api/login";
            let headers = {
                "Content-type": "application/json; charset=UTF-8",
            };
            let user = new Api(url, data, headers);
            return user.post(url, data, headers).then(function (response) {
                commit("setUser", response.data);
                localStorage.setItem("Authorized", response.data.token);
            });
        },
        changeUserData({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/user/change";
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let user = new Api(url, data, headers);
            return user.put(url, data, headers).then(function (response) {
                commit("setUser", response.data);
                localStorage.setItem("Authorized", response.data.token);
            });
        },
        ajaxGetFavorites({ commit }) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/favorite";
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let favorites = new Api(url, data, headers);
            return favorites.get(url, data, headers).then((response) => {
                commit("setFavorites", response.data);
            });
        },
        ajaxAddToFavorites({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/favorite/" + id;
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let favorite = new Api(url, 0, headers);
            return favorite.post(url, 0, headers);
        },
        ajaxDeleteFromFavorites({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/unfavorite/" + id;
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let favorite = new Api(url, 0, headers);
            return favorite.post(url, 0, headers);
        },
        logout({ commit }) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/logout";
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let user = new Api(url, 0, headers);
            localStorage.clear();
            return user.post(url, 0, headers);
        },
        userConferenceJoin({ commit }, conference_id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "/api/conferenceJoin/" + conference_id;
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let join = new Api(url, 0, headers);
            return join.post(url, 0, headers);
        },
        userConferenceOut({ commit }, conference_id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "/api/conferenceOut/" + conference_id;
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let out = new Api(url, 0, headers);
            return out.post(url, 0, headers);
        },
        ajaxUser({ commit }) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/user";
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let user = new Api(url, 0, headers);
            return user.get(url, 0, headers).then((response) => {
                commit("setUser", response.data);
            });
        },
        isReportInFavorite({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/isFavorite/" + id;
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let report = new Api(url, 0, headers);
            return report.get(url, 0, headers).then((response) => {
                commit("setOnFavorite", response.data);
            });
        },
        isUserOnConference({ commit }, conference_id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/isOnConference/" + conference_id;
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let user = new Api(url, 0, headers);
            return user.post(url, 0, headers).then((response) => {
                commit("setUserOnConferenceStatus", response.data);
            });
        },
        changePoint({ commit }, data) {
            commit("setConferencePoint", data);
        },
        ajaxReports({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url =
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
                data[5];
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let report = new Api(url, 0, headers);
            return report.get(url, 0, headers).then((response) => {
                commit("setReports", response.data);
            });
        },
        ajaxGetReport({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/conferences/" + id[0] + "/reports/" + id[1];
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let report = new Api(url, 0, headers);
            return report.get(url, 0, headers).then((response) => {
                commit("setReport", response.data[0]);
            });
        },
        ajaxCreateReport({ commit }, data) {
            let dataF = new FormData();
            dataF.append("presentation", data[0].presentation);
            dataF.append("thema", data[0].thema);
            dataF.append("start_time", data[0].start_time);
            dataF.append("end_time", data[0].end_time);
            dataF.append("description", data[0].description);
            dataF.append("category_id", data[0].category_id);
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/conferences/" + data[1] + "/reports";
            let headers = {
                Authorization: token,
                "Content-type":
                    "application/vnd.openxmlformats-officedocument.presentationml.presentation;",
            };
            let report = new Api(url, dataF, headers);
            return report.post(url, dataF, headers).then(function (response) {
                commit("setReport", response.data);
            });
        },
        ajaxEditReport({ commit }, data) {
            let dataF = new FormData();
            dataF.append("presentation", data[0].presentation);
            dataF.append("thema", data[0].thema);
            dataF.append("start_time", data[0].start_time);
            dataF.append("end_time", data[0].end_time);
            dataF.append("description", data[0].description);
            dataF.append("_method", "PUT");
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/conferences/" + data[1] + "/reports/" + data[2];
            let headers = {
                Authorization: token,
                "Content-type":
                    "multipart/form-data; boundary=<calculated when request is sent>",
            };
            let report = new Api(url, dataF, headers);
            return report.post(url, dataF, headers).then(function (response) {
                commit("setReport", response.data);
            });
        },
        ajaxReportDelete({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/conferences/" + data[0] + "/reports/" + data[1];
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let report = new Api(url, 0, headers);
            return report.delete(url, 0, headers);
        },
        ajaxGetReportFile({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url =
                "api/conferences/" + data[0] + "/reports/" + data[1] + "/file";
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let file = new Api(url, 0, headers);
            return file.get(url, 0, headers).then((response) => {
                commit("setFile", response.config.url);
            });
        },
        ajaxGetComments({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/conferences/report/" + data[0] + "/comment";
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let comments = new Api(url, 0, headers);
            return comments.get(url, 0, headers).then((response) => {
                commit("setComments", response.data);
            });
        },
        ajaxSendComment({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url =
                "api/conferences/" +
                data[0] +
                "/report/" +
                data[1] +
                "/comment";
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let comment = new Api(url, data[2], headers);
            return comment.post(url, data[2], headers);
        },
        ajaxSetComment({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url =
                "api/conferences/" +
                data[0] +
                "/report/" +
                data[1] +
                "/comment/" +
                data[2];
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let comment = new Api(url, data[3], headers);
            return comment.put(url, data[3], headers);
        },
        ajaxGetCategories({ commit }) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/categories";
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let category = new Api(url, 0, headers);
            return category.get(url, 0, headers).then((response) => {
                commit("setCategories", response.data);
            });
        },
        ajaxCreateCategory({ commit }, data) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/categories";
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let category = new Api(url, data, headers);
            return category.post(url, data, headers);
        },
        ajaxDeleteCategory({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/category/destroy";
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let category = new Api(url, id, headers);
            return category.delete(url, id, headers);
        },
        ajaxGetCategoryConferenceNumber({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/category/" + id + "/getConferences";
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let category = new Api(url, 0, headers);
            return category.get(url, 0, headers).then((response) => {
                commit("setConferences", response.data);
            });
        },
        ajaxGetCategoryReportNumber({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/category/" + id + "/getReports";
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let category = new Api(url, 0, headers);
            return category.get(url, 0, headers).then((response) => {
                commit("setReports", response.data);
            });
        },
        ajaxGetRootCategories({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/rootCategories";
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let category = new Api(url, 0, headers);
            return category.get(url, 0, headers).then((response) => {
                commit("setRootCategories", response.data);
            });
        },
        ajaxGetSubCategories({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/subCategories/" + id;
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let category = new Api(url, 0, headers);
            return category.get(url, 0, headers).then((response) => {
                commit("setSubCategories", response.data);
            });
        },
        ajaxGetCurrentCategory({ commit }, id) {
            let token = "Bearer " + localStorage.getItem("Authorized");
            let url = "api/currentCategory/" + id;
            let headers = {
                Authorization: token,
                "Content-type": "application/json; charset=UTF-8",
            };
            let category = new Api(url, 0, headers);
            return category.get(url, 0, headers).then((response) => {
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
    },
});
