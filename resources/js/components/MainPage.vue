<template>
    <div>
        <auth></auth>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <br>
                <div class="card" v-for="conference in getConferences" :value="conference.id" :key="conference.id">
                    <div class="card-header">{{conference.title}}<br></div>
                    <div class="card-body">{{conference}}</div>
                    <router-link v-if="isAuth()" :to="{name: 'ConferenceDetails', params:{id: conference.id}}">Подробнее</router-link>
                    <router-link v-else :to="{name: 'Registration'}">Подробнее</router-link>
                    <router-link v-if="isAdmin()" :to="{name: 'EditConference', params:{id: conference.id}}">Изменить</router-link>
                    <button v-if="isAdmin()" @click="deleteConference(conference.id)">Удалить</button>
                </div>
            </div>
        </div>
        <router-link v-if="isAdmin()" :to="{name: 'CreateConference'}">Создать</router-link>
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