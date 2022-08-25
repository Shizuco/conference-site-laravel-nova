import axios from "axios";

export default class Api {

    constructor(url, data, headers) {
        this.url = url;
        this.data = data;
        this.headers = headers;
    }

    get(url, headers) {
        return axios({
            method: "get",
            url: url,
            headers: headers,
        });
    }

    delete(url, headers) {
        return axios({
            method: "delete",
            url: url,
            headers: headers,
        });
    }

    post(url, data, headers){
        return axios({
            method: 'post',
            url: url,
            data: data,
            headers: headers
        })
    }

    put(url, data, headers){
        return axios({
            method: 'put',
            url: url,
            data: data,
            headers: headers
        })
    }
}
