<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <div class="row justify-content-center">
            <div class="col-md-8" v-for="report in getFavorites" :value="report.id" :key="report.id">
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
        </div>
    </v-app>

</template>

<script>
export default {
    mounted() {
        if(this.isAuth()){
            this.$store.dispatch('ajaxGetFavorites')
        }
    },
    computed: {
        getFavorites() {
            return this.$store.getters.getFavorites
        }
    },
    methods: {
        isAuth() {
            return ("Authorized" in localStorage) ? true : false
        },
    }
}

</script>