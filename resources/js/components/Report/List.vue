<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <div class="row justify-content-center">
            <div class="col-md-8" v-for="report in getReports" :value="report.id" :key="report.id">
                <v-card elevation="3">
                    <v-card-title>{{ report.thema }}</v-card-title>
                    <v-card-text>
                        Duration: {{ report.start_time }} to {{ report.end_time }}
                        <br>
                        <span>
                            <v-btn x-big depressed color="success">
                                <router-link class="white--text" style="text-decoration: none; color: inherit;"
                                    v-if="isAuth()"
                                    :to="{ name: 'Details', params: { id: report.conference_id, rep_id: report.id } }">
                                    More
                                </router-link>
                            </v-btn>
                        </span>
                    </v-card-text>
                </v-card>
            </div>
            <v-btn x-big block color="primary"
                :to="{ name: 'ConferenceDetails', params: { id: this.$route.params.id } }" class="white--text">Back
            </v-btn>
        </div>
    </v-app>

</template>

<script>
export default {
    mounted() {
        console.log(this.$route.params)
        this.$store.dispatch('ajaxReports', this.$route.params.id)
    },
    computed: {
        getReports() {
            return this.$store.getters.getReports
        }
    },
    methods: {
        isAuth() {
            return ("Authorized" in localStorage) ? true : false
        },
    }
}

</script>