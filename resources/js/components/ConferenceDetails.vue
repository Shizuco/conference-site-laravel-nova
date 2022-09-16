<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <v-breadcrumbs :items="items"><template v-slot:divider>
                <v-icon>mdi-forward</v-icon>
            </template></v-breadcrumbs>
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
                                    lat: Number(getConference.latitude),
                                    lng: Number(getConference.longitude)
                                }" mapTypeId='roadmap' style="width:100%;height:500px" id="map">
                                    <gmap-marker :position="{
                                        lat: Number(getConference.latitude),
                                        lng: Number(getConference.longitude)
                                    }"></gmap-marker>
                                </gmap-map>
                            </v-row>
                            <br>
                            <v-row>
                                <h3 v-if="getConference.hasTime == false" style="color:brown">No free time for reports
                                </h3>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-btn x-big block color="primary" :to="{ name: 'MainPage' }" class="white--text">
                                        Back
                                    </v-btn>
                                </v-col>
                                <v-col>
                                    <v-btn
                                        v-if="isOnConference() == null  && getConference.hasTime == true"
                                        @click="join()" x-big block color="success" class="white--text">Join</v-btn>
                                        <v-btn v-else @click="out()"
                                        x-big block color="error" class="white--text">Exit</v-btn>
                                </v-col>
                                <v-col>
                                    <v-btn v-if="isAuth()" depressed color="warning" x-big @click="toReports">Reports
                                    </v-btn>
                                </v-col>
                                <v-col>
                                    <v-btn x-big block color="primary">
                                        <ShareNetwork class="white--text" style="text-decoration: none; color: inherit;"
                                            network="facebook" :url=url() title="Say hi to Vue!" hashtags="vuejs">
                                            Facebook
                                        </ShareNetwork>
                                    </v-btn>
                                </v-col>
                                <v-col>
                                    <v-btn  x-big block color="primary">
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

export default {
    data: () => ({
        items: [
            {
                text: 'conferences',
                disabled: false,
                exact: true,
                to: { name: 'MainPage' },
                replace: true
            }
        ],
        CsvButtonType: 0
    }),
    mounted() {
        let id = this.$route.params.id
        if (this.isAuth()) {
            this.$store.dispatch('ajaxGetConference', id).then(() => {
                this.$store.dispatch('ajaxGetCurrentCategory', this.$store.getters.getConference.category_id).then(() => {
                    this.items.push({
                        text: this.$store.getters.getCurrentCategory[0].name,
                        disabled: true
                    })
                })
                console.log(this.getConference)
                this.$store.dispatch('isUserOnConference', id)
                this.$store.dispatch('ajaxUser')
            })
        }
        else {
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
        join() {
            let conference_id = this.$store.getters.getConference.id
            this.$store.dispatch('userConferenceJoin', conference_id)
            if (this.$store.getters.getUser.role == "announcer") {
                this.$router.replace({ name: 'Create' })
            }
            else {
                this.$router.go()
            }
        },
        out() {
            let conference_id = this.$store.getters.getConference.id
            this.$store.dispatch('userConferenceOut', conference_id).then(() => {
                this.$store.dispatch('ajaxReportDelete', [conference_id, 0])
            }).then(() => {
                this.$router.go()
            })
        },
        isOnConference() {
            return this.$store.getters.getUserOnConferenceStatus
        },
        url() {
            return document.location.origin
        },
        toReports() {
            this.$router.replace('/conferences/' + this.$store.getters.getConference.id + '/reports')
        },
    },
}
</script>

<style>
a {
    z-index: 0;
}
</style>