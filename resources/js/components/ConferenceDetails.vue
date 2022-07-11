<template>
    <div class="container">
        <auth style="height: 80px"></auth>
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
        <button v-if="isAdmin()" @click="deleteConference()">Удалить</button>
        <button v-if="isAuth()&&isOnConference()==null" @click="join()">Присоединиться</button>
        <button v-if="isAuth()&&isOnConference()!=null" @click="out()">Выйти</button>
    </div>
</template>

<script>
    export default {
        mounted() {
            let id = this.$route.params.id
            this.$store.dispatch('ajaxGetConference', id)
            this.$store.dispatch('isUserOnConference', id)
            this.$store.dispatch('ajaxUser')
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
            isAdmin(){
                if(this.$store.getters.getUser.role == "Admin"){
                    return true
                }
                else{
                    return false
                }
            },
            join(){
                let conference_id = this.$store.getters.getConference.id
                this.$store.dispatch('userConferenceJoin',conference_id)
                location.reload();
            },
            out(){
                let conference_id = this.$store.getters.getConference.id
                this.$store.dispatch('userConferenceOut',conference_id)
                location.reload();
            },
            deleteConference(){
                let id = this.$route.params.id
                let href = document.location.origin
                this.$store.dispatch('ajaxConferenceDelete', id)
                this.$store.getters.deleteConference
                document.location.href = href
            },
            isOnConference(){
                return this.$store.getters.getUserOnConferenceStatus
            }
        }
    }
</script>
