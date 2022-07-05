<template>
    <div class="container">
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
    </div>
</template>

<script>
    export default {
        mounted() {
            let id = this.$route.params.id
            this.$store.dispatch('ajaxGetConference', id)
        },
        computed: {
            getConference(){
                return this.$store.getters.getConference
            },
            deleteConference(){
                let id = this.$route.params.id
                let href = document.location.origin
                this.$store.dispatch('ajaxConferenceDelete', id)
                this.$store.getters.deleteConference
                document.location.href = href
            }
        }
    }
</script>
