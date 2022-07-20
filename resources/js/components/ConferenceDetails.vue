<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <v-row align="center" justify="center" dense>
            <v-col cols="12" sm="8" md="4" lg="10">
                <v-card elevation="10">
                    <v-card-text>
                        <v-form>
                            <v-row>
                                <v-col>
                                    <v-text>
                                        <h1>{{ getConference.title }}</h1>
                                    </v-text>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-text>
                                        <h3>Appointed time: {{ getConference.date }} {{ getConference.time }}</h3>
                                    </v-text>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-text>
                                        <h3>Country: {{ getConference.country }}</h3>
                                    </v-text>
                                </v-col>
                            </v-row>
                            <v-row>
                                <gmap-map :zoom="15" :center="{
                                    lat: Number(getConference.address_lat),
                                    lng: Number(getConference.address_lon)
                                }" mapTypeId='roadmap' style="width:100%;height:500px" id="map">
                                    <gmap-marker :position="{
                                        lat: Number(getConference.address_lat),
                                        lng: Number(getConference.address_lon)
                                    }"></gmap-marker>
                                </gmap-map>
                            </v-row>
                            <br>
                            <v-row>
                                <v-col>
                                    <v-btn x-big block color="primary" :to="{ name: 'MainPage' }" class="white--text">
                                        Back
                                    </v-btn>
                                </v-col>
                                <v-col>
                                    <v-btn v-if="isAdmin()" @click="deleteConference()" x-big block color="error"
                                        class="white--text">Delete</v-btn>
                                </v-col>
                                <v-col>
                                    <v-btn v-if="isAuth() && isOnConference() == null && !isAdmin()" @click="join()"
                                        x-big block color="success" class="white--text">Join</v-btn>
                                </v-col>
                                <v-col>
                                    <v-btn v-if="isAuth()" depressed color="warning" big
                                        :to="{ name: 'List', params: { id: getConference.id } }">Reports</v-btn>
                                </v-col>
                                <v-col>
                                    <v-btn v-if="isAuth() && isOnConference() != null && !isAdmin()" @click="out()"
                                        x-big block color="error" class="white--text">Exit</v-btn>
                                </v-col>
                                <v-col>
                                    <v-btn v-if="!isAdmin()" x-big block color="primary">
                                        <ShareNetwork class="white--text" style="text-decoration: none; color: inherit;"
                                            network="facebook" :url=url() title="Say hi to Vue!" hashtags="vuejs">
                                            Facebook
                                        </ShareNetwork>
                                    </v-btn>
                                </v-col>
                                <v-col>
                                    <v-btn v-if="!isAdmin()" x-big block color="primary">
                                        <ShareNetwork class="white--text" style="text-decoration: none; color: inherit;"
                                            network="twitter" :url=url() title="Say hi to Vue" hashtags="vuejs">
                                            Twitter
                                        </ShareNetwork>
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-app>
</template>
<script>
import { isThisExpression } from '@babel/types'

export default {
    mounted() {
        let id = this.$route.params.id
        if (this.isAuth()) {
            this.$store.dispatch('ajaxGetConference', id)
            this.$store.dispatch('isUserOnConference', id)
            this.$store.dispatch('ajaxUser')
        }
        else{
            this.$router.replace('/conferences')
        }
    },
    computed: {
        getConference() {
            return this.$store.getters.getConference
        },
    },
    methods: {
        isAuth() {
            return ("Authorized" in localStorage) ? true : false
        },
        isAdmin() {
            return (this.$store.getters.getUser.role == "admin")? true : false
        },
        join() {
            let conference_id = this.$store.getters.getConference.id
            this.$store.dispatch('userConferenceJoin', conference_id)
            this.$router.go()
        },
        out() {
            let conference_id = this.$store.getters.getConference.id
            this.$store.dispatch('userConferenceOut', conference_id)
            this.$router.go()
        },
        deleteConference() {
            let id = this.$route.params.id
            this.$store.dispatch('ajaxConferenceDelete', id)
            this.$router.replace('/conferences')
        },
        isOnConference() {
            return this.$store.getters.getUserOnConferenceStatus
        },
        url() {
            return document.location.origin
        },
    },
}
</script>
