<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card" v-for="conference in getConferences" :value="conference.id" :key="conference.id">
                    <div class="card-header">{{conference.title}}<br></div>
                    <div class="card-body">{{conference}}</div>
                    <router-link :to="{name: 'ConferenceDetails', params:{id: conference.id}}">Подробнее</router-link>
                    
                </div>
            </div>
        </div>
        <router-link :to="{name: 'CreateConference'}">Создать</router-link>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.$store.dispatch('ajaxConferences')
            
        },
        computed: {
            getConferences(){
                return this.$store.getters.getConferences
            }
        }
    }
</script>