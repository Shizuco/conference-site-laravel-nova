<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <v-card>
            <v-card-title>
                <v-text-field label="Search" single-line solo style="padding-right: 20px" @change="getSearch()"
                    v-model="title"></v-text-field>
                <v-divider :vertical="true" color="black" class="mx-2"></v-divider>
                <v-radio-group  v-model="category" @change="getSearch()" style="width: 400px; padding-left: 20px; padding-bottom: 20px;">
                    <v-radio v-for="name in categories" :key="name" :label="name" :value="name"></v-radio>
                </v-radio-group>
            </v-card-title>
            <v-card-text>
                <div class="row justify-content-center">
                    <div class="col-md-12" v-for="conference in conferences" :value="conference.id"
                        :key="conference.id">
                        <v-card elevation="3">
                            <v-card-title>{{ conference.title }}</v-card-title>
                            <v-card-text>
                                Appointed time: {{ conference.date }} {{ conference.time }}
                                <br>
                                <span>
                                    <v-btn x-big depressed color="success">
                                        <router-link class="white--text" style="text-decoration: none; color: inherit;"
                                            :to="{ name: 'ConferenceDetails', params: { id: conference.id } }" target="_blank">
                                            More
                                        </router-link>
                                    </v-btn>
                                </span>
                            </v-card-text>
                            Conference
                        </v-card>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-12" v-for="report in reports" :value="report.id" :key="report.id">
                        <v-card elevation="3">
                            <v-card-title>{{ report.thema }}</v-card-title>
                            <v-card-text>
                                Duration: {{ report.start_time }} to {{ report.end_time }}
                                <br>
                                <span>
                                    <v-btn x-big depressed color="success">
                                        <router-link class="white--text" style="text-decoration: none; color: inherit;"
                                            :to="{ name: 'Details', params: { id: report.conference_id, rep_id: report.id } }" target="_blank">
                                            More
                                        </router-link>
                                    </v-btn>
                                </span>
                            </v-card-text>
                            Report
                        </v-card>
                    </div>
                </div>
            </v-card-text>
        </v-card>
    </v-app>
</template>

<script>
export default {
    data: () => ({
        categories: ['Report', 'Conference'],
        category: '',
        conferences: [],
        reports: [],
        title: '',
    }),
    methods: {
        getSearch(){
            if(this.category == 'Conference'){
                this.getConfByName()
            }
            else if(this.category == 'Report'){
                this.getRepByName()
            }
            else{
                this.getConfByName()
                this.getRepByName()
            }
        },
        getConfByName(page = 1) {
            this.conferences = []
            this.reports = []
            this.$store.dispatch('ajaxGetConferenceByName', [page, this.title]).then(() => {
                this.$store.getters.getConferences.data.forEach(element => {
                    this.conferences.push(element);
                })
            })
        },
        getRepByName(page = 1) {
            this.conferences = []
            this.reports = []
            this.$store.dispatch('ajaxGetReportsByName', [page, this.title]).then(() => {
                this.$store.getters.getReports.data.forEach(element => {
                    this.reports.push(element);
                })
            })
        }
    }
}
</script>