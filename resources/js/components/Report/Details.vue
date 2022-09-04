<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <v-breadcrumbs :items="items"><template v-slot:divider>
                <v-icon>mdi-forward</v-icon>
            </template></v-breadcrumbs>
        <v-row align="center" justify="center" dense>
            <v-row cols="12" sm="8" md="4" lg="10">
                <v-card elevation="2">
                    <p v-if="currentTime > 0"> {{ time }} </p>
                    <v-card-title>
                        <h3>{{ formData.thema }}</h3>
                    </v-card-title>
                    <v-card-text>
                        <p>Duration: {{ formData.start_time }} to {{ formData.end_time }}</p>
                        <h4>About</h4>
                        <p>{{ formData.description }}</p>
                        <a :href="getFile">{{ formData.presentation }}</a>
                        <v-btn @click="onClick" x-small color="primary">download</v-btn>
                    </v-card-text>
                    <v-btn v-if="getReport.user_id == getUser.id" depressed color="warning" big
                        :to="{ name: 'Edit', params: { id: getReport.conference_id, r_id: getReport.id } }">Edit</v-btn>
                </v-card>
            </v-row>
            <v-row cols="12" sm="8" md="4" lg="10">
                <v-col v-if="currentTime <= 600 && getUser.id == getReport.user_id">
                    <v-btn depressed x-small block color="primary"><a :href=start_url class="white--text"
                            style="text-decoration: none; color: inherit;">start meeting</a></v-btn>
                </v-col>
                <v-col v-if="currentTime == 0 && isOnConference() != null">
                    <v-btn depressed x-small block color="primary"><a :href=join_url_browser class="white--text"
                            style="text-decoration: none; color: inherit;">join through browser</a></v-btn>
                </v-col>
                <v-col v-if="currentTime == 0 && isOnConference() != null">
                    <v-btn depressed x-small block color="primary"><a :href=join_url_app class="white--text"
                            style="text-decoration: none; color: inherit;">join through app</a></v-btn>
                </v-col>
            </v-row>
            <br>
            <v-row cols="12" sm="8" md="4" lg="10">
                <br>
                <v-card elevation="5">
                    <vue-editor v-model="comment" outlined></vue-editor>
                    <v-btn x-small depressed color="primary" @click="sendComment">Comment</v-btn>
                </v-card>
            </v-row>
            <v-row cols="12" sm="8" md="4" lg="10" v-for="(comment, index) in getReport.comments" :value="comment.id"
                :key="comment.id">
                <v-col>
                    <v-card elevation="3">
                        <v-card-title>
                            <p>
                            <h3>{{ comment.users.name }} <h5>{{ new Date(comment.updated_at).toLocaleString() }} <v-btn
                                        v-if="isDateOk(index) && edit == 0 && comment.users.id == getUser.id"
                                        @click="plusEdit" x-small color="warning"> Edit
                                    </v-btn>
                                </h5>
                            </h3>
                            </p>
                        </v-card-title>
                        <v-card-text
                            v-if="(!isDateOk(index)) || (edit == 0 && isDateOk(index) && comment.users.id == getUser.id) || comment.users.id != getUser.id">
                            <p v-html="comment.comment"></p>
                        </v-card-text>
                        <vue-editor v-if="isDateOk(index) && edit == 1 && comment.users.id == getUser.id"
                            v-model="comment.comment" label="Enter your comment" outlined></vue-editor>
                        <v-btn v-if="isDateOk(index) && edit == 1 && comment.users.id == getUser.id"
                            @click="setComment(index)" x-small color="warning">Edit
                        </v-btn>
                        <v-btn v-if="isDateOk(index) && edit == 1 && comment.users.id == getUser.id" @click="edit--" x-small color="error">Cancel
                        </v-btn>
                    </v-card>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-btn v-if="getUser.role == 'admin'" @click="deleteReport()" x-big block color="error"
                        class="white--text">
                        Delete report</v-btn>
                </v-col>
                <v-col>
                    <v-btn block v-if="CsvButtonType == 0 && isAdmin()" @click="getCsv()">export</v-btn>
                    <spinner v-if="CsvButtonType == 1"></spinner>
                    <v-btn block v-if="CsvButtonType == 2" @click="downloadCsv()">download</v-btn>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-btn v-if="isFavorite.length == 0" @click="join()" small block color="success"
                        class="white--text">Add to favorite</v-btn>
                    <v-btn v-if="isFavorite.length > 0" @click="out()" small block color="error" class="white--text">
                        Delete from favorite</v-btn>
                </v-col>
            </v-row>
        </v-row>
    </v-app>
</template>

<script>
import { VueEditor } from "vue2-editor";
export default {
    data: () => ({
        formData: {
            thema: '',
            start_time: '',
            end_time: '',
            description: '',
            presentation: ''
        },
        items: [
            {
                text: 'conferences',
                disabled: false,
                exact: true,
                to: { name: 'MainPage' },
                replace: true
            },
        ],
        comments: [],
        comment: '',
        commentNum: 0,
        newComment: '',
        edit: 0,
        CsvButtonType: 0,
        currentTime: 5,
        timer: null,
        time: 0,
        join_url_app: '',
        join_url_browser: '',
        start_url: ''
    }),
    destroyed() {
        this.stopTimer()
    },
    components: {
        VueEditor
    },
    watch: {
        currentTime(time) {
            if (time === 0) {
                this.stopTimer()
            }
        }
    },
    mounted() {
        if ("Authorized" in localStorage) {
            this.$store.dispatch('ajaxGetReport', [this.$route.params.id, this.$route.params.rep_id]).then(() => {
                let date = new Date(this.getReport.start_time).toLocaleString()
                this.currentTime = parseInt(this.toTimestamp(date) - (Date.now() / 1000));
                this.$store.dispatch('ajaxGetReportFile', [this.$route.params.id, this.$route.params.rep_id]).then(() => {
                    this.$store.dispatch('ajaxUser')
                    this.$store.dispatch('isUserOnConference', this.$route.params.id)
                    this.$store.dispatch('ajaxGetConference', this.$route.params.id).then(() => {
                        this.$store.dispatch('ajaxGetCurrentCategory', this.$store.getters.getConference.category_id).then(() => {
                            this.items.push({
                                text: this.$store.getters.getCurrentCategory[0].name,
                                disabled: false,
                                exact: true,
                                to: '/conferences/' + this.$route.params.id,
                                replace: true
                            })
                        }).then(() => {
                            this.$store.dispatch('ajaxGetCurrentCategory', this.getReport.category_id).then(() => {
                                this.items.push({
                                    text: this.$store.getters.getCurrentCategory[0].name
                                })
                            })
                        })
                    })
                    this.link = this.$route.params.id
                    this.$store.dispatch('isReportInFavorite', this.getReport.id)
                    this.$data.formData.thema = this.getReport.thema
                    this.$data.formData.start_time = this.getReport.start_time
                    this.$data.formData.end_time = this.getReport.end_time
                    this.$data.formData.description = this.getReport.description
                    this.$data.formData.presentation = this.getReport.presentation
                    this.$store.dispatch('ajaxGetMeeting', this.getReport.zoom_meeting_id).then(() => {
                        this.join_url_app = this.$store.getters.getMeeting.data.join_url
                        this.join_url_browser = this.joinInBrowser(this.$store.getters.getMeeting.data.join_url)
                        this.start_url = this.$store.getters.getMeeting.data.start_url
                    })
                    this.startTimer()
                })
            })
            Echo.channel('downloadCsvFile')
                .listen('DownloadExportCsvFile', (e) => {
                    if (e.message == 'start') {
                        this.CsvButtonType = 1
                    }
                    if (e.message == 'done') {
                        this.CsvButtonType = 2
                    }
                })
        }
    },
    computed: {
        getReport() {
            return this.$store.getters.getReport
        },
        getUser() {
            return this.$store.getters.getUser
        },
        getFile() {
            return this.$store.getters.getFile
        },
        isFavorite() {
            return this.$store.getters.getReportStatus
        },
    },
    methods: {
        toTimestamp(strDate) {
            var datum = Date.parse(strDate);
            return datum / 1000;
        },
        toDate(timestamps) {
            let unix_timestamp = timestamps
            let date = new Date(unix_timestamp * 1000);
            let days = parseInt(timestamps / 86400);
            let hours = "0" + (date.getHours() - 3);
            let minutes = "0" + date.getMinutes();
            let seconds = "0" + date.getSeconds();
            return days + ':' + hours.substr(-2) + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
        },
        startTimer() {
            this.timer = setInterval(() => {
                this.currentTime--
                this.time = this.toDate(this.currentTime);
            }, 1000)
        },
        isOnConference() {
            return this.$store.getters.getUserOnConferenceStatus
        },
        stopTimer() {
            clearTimeout(this.timer)
        },
        onClick() {
            let token = 'Bearer ' + localStorage.getItem('Authorized')
            axios({
                url: this.getFile, //your url
                method: 'GET',
                headers: {
                    "Authorization": token,
                    "Content-type": "application/json"
                },
                responseType: 'blob', // important
            }).then((response) => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', this.formData.presentation);
                document.body.appendChild(link);
                link.click();
            });
        },
        isAdmin() {
            return (this.$store.getters.getUser.role == "admin") ? true : false;
        },
        deleteReport() {
            let report_id = this.$store.getters.getReport.id
            this.$store.dispatch('ajaxReportDelete', [this.getReport.conference_id, report_id]).then(() => {
                this.$router.replace('/conferences')
            })
        },
        join() {
            let report_id = this.$store.getters.getReport.id
            this.$store.dispatch('ajaxAddToFavorites', report_id).then(() => {
                this.$router.go()
            })
        },
        out() {
            let report_id = this.$store.getters.getReport.id
            this.$store.dispatch('ajaxDeleteFromFavorites', report_id).then(() => {
                this.$router.go()
            })
        },
        sendComment() {
            let data = {
                'comment': this.comment
            }
            this.$store.dispatch('ajaxSendComment', [this.getReport.conference_id, this.getReport.id, data]).then(() => {
                this.$router.go()
            })

        },
        isDateOk(index) {
            if (this.getReport.comments.length == 0) {
                return false;
            }
            let previousTime = new Date(this.getReport.comments[index].updated_at).toUTCString();
            let dateTime = new Date().toUTCString();
            return ((Date.parse(previousTime) / 1000) >= ((Date.parse(dateTime) / 1000) - 600)) ? true : false
        },
        setComment(index) {
            this.newComment = this.getReport.comments[index].comment
            let data = {
                'comment': this.newComment
            }
            this.$store.dispatch('ajaxSetComment', [this.getReport.conference_id, this.getReport.id, this.getReport.comments[index].id, data]).then(() => {
                this.$router.go()
            })
        },
        plusEdit() {
            this.edit++;
        },
        commentN() {
            this.commentNum++;
        },
        getCsv() {
            let token = 'Bearer ' + localStorage.getItem('Authorized')
            axios({
                url: 'api/conferences/reports/' + this.getReport.id + '/commentCsv', //your url
                method: 'GET',
                headers: {
                    "Authorization": token,
                    "Content-type": "application/json"
                },
            })
        },
        downloadCsv() {
            let token = 'Bearer ' + localStorage.getItem('Authorized')
            axios({
                url: 'api/conferences/reports/' + this.getReport.id + '/commentDownloadCsv', //your url
                method: 'GET',
                headers: {
                    "Authorization": token,
                    "Content-type": "application/json"
                },
                responseType: 'blob', // important
            }).then((response) => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                let filename = response.headers['content-disposition'].split('filename=')[1].split(';')[0]
                link.setAttribute('download', filename);
                document.body.appendChild(link);
                link.click();
            })
        },
        joinInBrowser(link) {
            link = link.replace("/j/", "/wc/")
            link = link.slice(0, link.lastIndexOf('?'))
            link = link + '/join'
            return link
        }
    }
}
</script>

<style>
a {
    z-index: 0;
}

v-row {
    padding-bottom: 25px;
    padding-top: 10px;
}
</style>