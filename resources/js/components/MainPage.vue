<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <div class="row justify-content-center">
            <div class="col-md-8" v-for="conference in getConferences" :value="conference.id" :key="conference.id">
                <v-card  elevation="3">
                    <v-card-title>{{ conference.title }}</v-card-title>
                    <v-card-text>
                        Appointed time: {{ conference.date }} {{ conference.time }}
                        <br>
                        <span>
                            <v-btn x-big depressed color="success">
                                <router-link class="white--text" style="text-decoration: none; color: inherit;"
                                    v-if="isAuth()" :to="{ name: 'ConferenceDetails', params: { id: conference.id } }">More
                                </router-link>
                                <router-link class="white--text" style="text-decoration: none; color: inherit;" v-else
                                    :to="{ name: 'Registration' }">More</router-link>
                            </v-btn>
                        </span>
                        <span>
                            <v-btn v-if="isAdmin()" depressed color="warning" big
                                :to="{ name: 'EditConference', params: { id: conference.id } }">Edit</v-btn>
                        </span>
                        <span>
                            <v-btn v-if="isAdmin()" x-big depressed color="error"
                                @click="deleteConference(conference.id)">Delete</v-btn>
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
        this.$store.dispatch('ajaxConferences')
        if(this.isAuth()){
            this.$store.dispatch('ajaxUser')
        }       
    },
    computed: {
        getConferences() {
            return this.$store.getters.getConferences
        }
    },
    methods: {
        isAuth() {
            return ("Authorized" in localStorage) ? true : false
        },
        isAdmin() {
           return (this.$store.getters.getUser.role == "admin")? true : false
        },
        deleteConference(id) {
            this.$store.dispatch('ajaxConferenceDelete', id)
            this.$store.getters.deleteConference
            this.$router.go()
        },
    }
}

</script>