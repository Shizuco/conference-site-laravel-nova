<template>
    <div class="container">
        <auth></auth>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Conference Component</div>
                        <p>{{getConference}}</p>
                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
        <router-link :to="{name: 'MainPage'}">Назад</router-link>
        <button @click="deleteConference()">Удалить</button>
        <button v-if="isAuth()&&isOnConference()==null" @click="join()">Присоединиться</button>
        <button v-if="isAuth()&&isOnConference()!=null" @click="out()">Выйти</button>
        <button v-if="isAuth()" @click="isOnConference()">аулцхал</button>
    </div>
</template>

<script>
    export default {
        mounted() {
            let id = this.$route.params.id
            this.$store.dispatch('ajaxGetConference', id)
            this.$store.dispatch('isUserOnConference', id)
        },
        computed: {
            getConference(){
                return this.$store.getters.getConference
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
            join(){
                let conference_id = this.$store.getters.getConference.id
                console.log(conference_id)
                this.$store.dispatch('userConferenceJoin',conference_id)
            },
            out(){
                let conference_id = this.$store.getters.getConference.id
                this.$store.dispatch('userConferenceOut',conference_id)
            },
            deleteConference(){
                let id = this.$route.params.id
                let href = document.location.origin
                this.$store.dispatch('ajaxConferenceDelete', id)
                this.$store.getters.deleteConference
                document.location.href = href
            },
            ajaxUser(){
                let conference_id = this.$store.getters.getConference.id
                this.$store.dispatch('userConferenceOut',conference_id)
            },
            isOnConference(){
                return this.$store.getters.getUser
            }
        }
    }
</script>
