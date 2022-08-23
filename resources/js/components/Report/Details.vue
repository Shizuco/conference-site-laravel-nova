<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <v-breadcrumbs :items="items"><template v-slot:divider>
                <v-icon>mdi-forward</v-icon>
            </template></v-breadcrumbs>
        <v-row align="center" justify="center" dense>
            <v-col cols="12" sm="8" md="4" lg="10">
                <v-card elevation="10">
                    <v-card-title>
                        <h3>{{ formData.thema }}</h3>
                    </v-card-title>
                    <v-card-text>
                        <p>Duration: {{ formData.start_time }} to {{ formData.end_time }}</p>
                        <h4>About</h4>
                        <p>{{ formData.description }}</p>
                        <a :href="getFile">{{ formData.presentation }}</a>
                        <button @click="onClick">download</button>
                    </v-card-text>
                    <v-btn v-if="getReport.user_id == getUser.id" depressed color="warning" big
                        :to="{ name: 'Edit', params: { id: getReport.conference_id, r_id: getReport.id } }">Edit</v-btn>
                </v-card>
            </v-col>
            <br>
            <v-col cols="12" sm="8" md="4" lg="10">
                <br>
                <v-card elevation="5">
                    <v-textarea v-model="comment" label="Enter your comment" outlined></v-textarea>
                    <v-btn depressed color="primary" x-big @click="sendComment">Comment</v-btn>
                </v-card>
            </v-col>
            <br>
            <v-col cols="12" sm="8" md="4" lg="10" v-for="(comment, index) in getReport.comments" :value="comment.id"
                :key="comment.id">
                <br>
                <v-card elevation="3">
                    <v-card-title>
                        <p>
                        <h3>{{ comment.users.name }} <h5>{{ new Date(comment.updated_at).toLocaleString() }} <v-btn
                                    v-if="isDateOk(index) && edit == 0 && comment.users.id == getUser.id"
                                    @click="plusEdit">
                                </v-btn>
                            </h5>
                        </h3>
                        </p>
                    </v-card-title>
                    <v-card-text
                        v-if="(!isDateOk(index)) || (edit == 0 && isDateOk(index) && comment.users.id == getUser.id) || comment.users.id != getUser.id">
                        <p>{{ comment.comment }}</p>
                    </v-card-text>
                    <v-textarea v-if="isDateOk(index) && edit == 1 && comment.users.id == getUser.id"
                        v-model="comment.comment" label="Enter your comment" outlined></v-textarea>
                    <v-btn v-if="isDateOk(index) && edit == 1 && comment.users.id == getUser.id"
                        @click="setComment(index)">Edit
                    </v-btn>
                </v-card>
            </v-col>
            <v-col>
                <v-col>
                    <v-btn v-if="isFavorite.length == 0" @click="join()" x-big block color="success"
                        class="white--text">Add to favorite</v-btn>
                </v-col>
                <v-col>
                    <v-btn v-if="isFavorite.length > 0" @click="out()" x-big block color="error" class="white--text">
                        Delete from favorite</v-btn>
                </v-col>
                <v-col>
                    <v-btn v-if="getUser.role == 'admin'" @click="deleteReport()" x-big block color="error" class="white--text">
                        Delete report</v-btn>
                </v-col>
            </v-col>
        </v-row>
    </v-app>
</template>

<script>

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
        newComment: [],
        edit: 0,
    }),
    mounted() {
        if ("Authorized" in localStorage) {
            this.$store.dispatch('ajaxGetReport', [this.$route.params.id, this.$route.params.rep_id]).then(() => {
                this.$store.dispatch('ajaxGetReportFile', [this.$route.params.id, this.$route.params.rep_id]).then(() => {
                    this.$store.dispatch('ajaxUser')
                    this.$store.dispatch('ajaxGetConference', this.$route.params.id).then(()=>{
                      this.$store.dispatch('ajaxGetCurrentCategory', this.$store.getters.getConference.category_id).then(() => {
                        this.items.push({
                            text: this.$store.getters.getCurrentCategory[0].name,
                            disabled: false,
                            exact: true,
                            to: '/conferences/' + this.$route.params.id,
                            replace: true
                        })
                    }).then(()=>{
                        this.$store.dispatch('ajaxGetCurrentCategory', this.getReport.category_id).then(()=>{
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
                    console.log(this.isFavorite.length)
                })
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
        deleteReport(){
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
        }
    }
}
</script>

<style>
a {
    z-index: 0;
}
</style>