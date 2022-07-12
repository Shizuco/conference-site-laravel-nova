<template>
    <div>
        <auth style="height: 80px"></auth>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <br>
                <div class="card" v-for="conference in getConferences" :value="conference.id" :key="conference.id">
                    <v-card>
                        <v-card-title>{{conference.title}}</v-card-title>
                        <v-card-text>
                            <v-row>
                                <v-col>Время проведения: {{conference.date}}  {{conference.time}}</v-col>
                            </v-row>
                            <v-row>
                                <v-col ><v-btn x-big block><router-link v-if="isAuth()" :to="{name: 'ConferenceDetails', params:{id: conference.id}}">Подробнее</router-link>
                                <router-link v-else :to="{name: 'Registration'}">Подробнее</router-link></v-btn></v-col>
                                <v-col v-if="isAdmin()"><router-link :to="{name: 'EditConference', params:{id: conference.id}}"><v-btn x-big block>Изменить</v-btn></router-link></v-col>
                                <v-col v-if="isAdmin()"><v-btn x-big block><button @click="deleteConference(conference.id)">УДАЛИТЬ</button></v-btn></v-col>         
                            </v-row>
                        </v-card-text>
                    </v-card>
                </div>
                <br>
            </div>
            <router-link v-if="isAdmin()" :to="{name: 'CreateConference'}">Создать</router-link>
        </div>
    </div>
    
</template>

<script>
    export default {
        mounted() {
            this.$store.dispatch('ajaxConferences')
            this.$store.dispatch('ajaxUser')
        },
        computed: {
            getConferences(){
                return this.$store.getters.getConferences
            }
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
            deleteConference(id){
                let href = document.location.origin
                this.$store.dispatch('ajaxConferenceDelete', id)
                this.$store.getters.deleteConference
                document.location.href = href
            },
        }
    }
    
</script>