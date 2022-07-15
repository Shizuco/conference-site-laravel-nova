<template>  
    <v-app>
        <auth style="height: 80px"></auth>
        <v-main>
            <v-container class="fill-height" fluid>
                <v-row align="center" justify="center" dense>
                <v-col cols="12" sm="8" md="4" lg="10">
                    <v-card elevation="0">
                        <v-card-text>
                            <v-form>
                                <v-row>
                                    <v-col><v-text><h1>{{getConference.title}}</h1></v-text></v-col>
                                </v-row>
                                <v-row>
                                    <v-col><v-text><h3>Время проведения: {{getConference.date}} {{getConference.time}}</h3></v-text></v-col>
                                </v-row>
                                <v-row>
                                    <v-col><v-text><h3>Страна: {{getConference.country}}</h3></v-text></v-col>
                                </v-row>
                                <v-row>
                                        <gmap-map
                                        :zoom="15"
                                        :center="{
                                        lat: Number(getConference.address_lat),
                                        lng: Number(getConference.address_lon)
                                        }"
                                        mapTypeId='roadmap'
                                        style="width:100%;height:500px"
                                        id="map"
                                        >
                                        <gmap-marker
                                            :position="{
                                                lat: Number(getConference.address_lat),
                                                lng: Number(getConference.address_lon)
                                            }"
                                            ></gmap-marker>
                                        </gmap-map>
                                </v-row>
                                <br>
                                <v-row>
                                    <v-col><v-btn x-large block red color="#000000"><router-link :to="{name: 'MainPage'}" class="text-h5 white--text">Назад</router-link></v-btn></v-col>
                                    <v-col><v-btn v-if="isAdmin()" @click="deleteConference()" x-large block red color="#000000" class="text-h5 white--text">Удалить</v-btn></v-col>
                                    <v-col><v-btn v-if="isAuth()&&isOnConference()==null&&!isAdmin()" @click="join()" x-large block green color="#000000" class="text-h5 white--text">Присоединиться</v-btn></v-col>
                                    <v-col><v-btn v-if="isAuth()&&isOnConference()!=null&&!isAdmin()" @click="out()" x-large block yellow color="#000000" class="text-h5 white--text">Выйти</v-btn></v-col>
                                    <v-col><v-btn v-if="!isAdmin()" x-large block red color="#000000"><ShareNetwork
                                            network="facebook"
                                            :url=url()
                                            title="Say hi to Vue!"
                                            hashtags="vuejs"
                                        >
                                            Facebook
                                        </ShareNetwork></v-btn>
                                    </v-col>
                                    <v-col><v-btn v-if="!isAdmin()" x-large block red color="#000000"><ShareNetwork
                                            network="twitter"
                                            :url=url()
                                            title="Say hi to Vue"
                                            hashtags="vuejs"
                                        >
                                            Twitter
                                        </ShareNetwork></v-btn>
                                    </v-col>
                                </v-row>
                            </v-form>
                        </v-card-text>
                    </v-card>
                </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>
<script>   
import { gmapApi } from 'vue2-google-maps';
    export default {
        mounted() {
            let id = this.$route.params.id
            this.$store.dispatch('ajaxGetConference', id)
            this.$store.dispatch('isUserOnConference', id)
            this.$store.dispatch('ajaxUser')  
            google: gmapApi
        },
        computed: {
            getConference(){
                return this.$store.getters.getConference
            }, 
        },
        methods:{
            isAuth(){
                if("Authorized" in localStorage){
                    return true
                }
                else{
                    return false
                }
            },
            isAdmin(){
                if(this.$store.getters.getUser.role == "admin"){
                    return true
                }
                else{
                    return false
                }
            },
            join(){
                let conference_id = this.$store.getters.getConference.id
                this.$store.dispatch('userConferenceJoin',conference_id)
                this.$router.go()
            },
            out(){
                let conference_id = this.$store.getters.getConference.id
                this.$store.dispatch('userConferenceOut',conference_id)
                this.$router.go()
            },
            deleteConference(){
                let id = this.$route.params.id
                let href = document.location.origin
                this.$store.dispatch('ajaxConferenceDelete', id)
                this.$router.replace('/conferences')
            },
            isOnConference(){
                return this.$store.getters.getUserOnConferenceStatus
            },
            url(){
                return document.location.origin
            },
        },
    }
</script>
