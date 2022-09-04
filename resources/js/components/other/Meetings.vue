<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <div class="row justify-content-center">
            <div class="col-md-8" v-for="meeting in meetings" :value="meeting.id" :key="meeting.id">
                <v-card elevation="3">
                    <v-card-title>
                        <h3>{{ meeting.topic }}</h3>
                    </v-card-title>
                    <v-card-text>
                        <table>
                            <tr>
                                <th>
                                    UUID
                                </th>
                                <th>
                                    {{ meeting.uuid }}
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    ID
                                </th>
                                <th>
                                    {{ meeting.id }}
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    HOST_ID
                                </th>
                                <th>
                                    {{ meeting.host_id }}
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    TYPE
                                </th>
                                <th>
                                    {{ meeting.type }}
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    START_TIME
                                </th>
                                <th>
                                    {{ meeting.start_time }}
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    TIMEZONE
                                </th>
                                <th>
                                    {{ meeting.timezone }}
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    CREATED_AT
                                </th>
                                <th>
                                    {{ meeting.created_at }}
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    JOIN_URL
                                </th>
                                <th>
                                    {{ meeting.join_url }}
                                </th>
                            </tr>
                        </table>
                        <br>
                        <v-btn depressed x-small block color="primary"><a :href=meeting.join_url class="white--text"
                                style="text-decoration: none; color: inherit;">join through app</a></v-btn>
                    </v-card-text>
                </v-card>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-2">
                <v-btn @click="changePageDown()" v-if="currentPage > 1" depressed x-big color="primary">
                    <v-icon class="mdi mdi-arrow-left-bold"></v-icon></v-btn>
            </div>
            <div class="col-md-2">
                <v-btn @click="changePageUp()" v-if="currentPage < totalPage" depressed x-big color="primary"><v-icon class="mdi mdi-arrow-right-bold"></v-icon></v-btn>
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

<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td,
th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>
