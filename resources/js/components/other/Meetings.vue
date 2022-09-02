<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <div class="row justify-content-center">
            <div class="col-md-8" v-for="meeting in meetings" :value="meeting.id" :key="meeting.id">
                <v-card elevation="3">
                    <v-card-title>
                        {{ meeting.topic }}
                    </v-card-title>
                    <v-card-text>
                        uuid => {{ meeting.uuid }}
                        <br>
                        id => {{ meeting.id }}
                        <br>
                        host_id => {{ meeting.host_id }}
                        <br>
                        type => {{ meeting.type }}
                        <br>
                        start_time => {{ meeting.start_time }}
                        <br>
                        timezone => {{ meeting.timezone }}
                        <br>
                        created_at => {{ meeting.created_at }}
                        <br>
                        join_url => {{ meeting.join_url }}
                        <br>
                        <v-col>
                            <v-btn depressed x-small block color="primary"><a :href=meeting.join_url class="white--text"
                                    style="text-decoration: none; color: inherit;">join through app</a></v-btn>
                        </v-col>
                    </v-card-text>
                </v-card>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-2" v-for="meeting in meetings" :value="meeting.id" :key="meeting.id">
                <v-btn @click="changePageDown()" v-if="currentPage > 1">
                    <= </v-btn>
            </div>
            <div class="col-md-2" v-for="meeting in meetings" :value="meeting.id" :key="meeting.id">
                <v-btn @click="changePageUp()" v-if="currentPage < totalPage">=></v-btn>
            </div>
        </div>
    </v-app>
</template>

<script>
export default {
    data: () => ({
        meetings: [],
        data: '',
        currentPage: 1,
        totalPage: 0
    }),
    mounted() {
        this.getMeetingsPug()
    },
    methods: {
        changePageUp() {
            this.currentPage++
            this.getMeetingsPug(this.currentPage)
        },
        changePageDown() {
            this.currentPage--
            this.getMeetingsPug(this.currentPage)
        },
        getMeetingsPug(page = 1) {
            this.meetings = []
            this.getMeetings(page)
        },
        getMeetings(page) {
            this.$store.dispatch('ajaxGetMeetings', page).then(() => {
                console.log(this.$store.getters.getMeetings)
                this.totalPage = this.$store.getters.getMeetings.page_count
                this.$store.getters.getMeetings.meetings.forEach(meeting => {
                    this.meetings.push(meeting)
                });
            })
        },
    }
}
</script>
