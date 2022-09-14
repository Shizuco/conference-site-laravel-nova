<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <Slide right style=" z-index: 100; position: relative; top: -100px;" width="550" v-if="isAuth()">
            <div v-for="name in categories" :value="name" :key="name.id">
                <v-checkbox v-model="formData.parentCategory" :label="name" :value="name"
                    @change="count++; getReports()">
                </v-checkbox>
            </div>
            <v-col cols="12" sm="10" md="26">
                <v-menu ref="menu" v-model="menu" :close-on-content-click="false" :return-value.sync="date"
                    transition="scale-transition" offset-y min-width="auto">
                    <template v-slot:activator="{ on, attrs }">
                        <v-text-field v-model="date" label="Date from" prepend-icon="mdi-calendar" readonly
                            v-bind="attrs" v-on="on"></v-text-field>
                    </template>
                    <v-date-picker v-model="date" no-title scrollable>
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="menu = false">
                            Cancel
                        </v-btn>
                        <v-btn text color="primary" @click="$refs.menu.save(date); count++; getReports()">
                            OK
                        </v-btn>
                    </v-date-picker>
                </v-menu>
            </v-col>
            <v-col cols="12" sm="10" md="26">
                <v-menu ref="menu2" v-model="menu2" :close-on-content-click="false" :return-value.sync="date2"
                    transition="scale-transition" offset-y min-width="auto">
                    <template v-slot:activator="{ on, attrs }">
                        <v-text-field v-model="date2" label="Date to" prepend-icon="mdi-calendar" readonly
                            v-bind="attrs" v-on="on"></v-text-field>
                    </template>
                    <v-date-picker v-model="date2" no-title scrollable>
                        <v-spacer></v-spacer>
                        <v-btn text color="primary" @click="menu2 = false">
                            Cancel
                        </v-btn>
                        <v-btn text color="primary" @click="$refs.menu2.save(date2); count++; getReports()">
                            OK
                        </v-btn>
                    </v-date-picker>
                </v-menu>
            </v-col>
            <v-col cols="12">
                <v-slider v-model="duration" :thumb-size="24" thumb-label="always"
                    @change="count++; countForDuretion++; getReports()" max="60" min="5" step="5"></v-slider>
            </v-col>
            <v-col>
                <v-btn x-big depressed color="error" @click="resetFilters">Reset</v-btn>
            </v-col>
        </Slide>
        <div class="row justify-content-center">
            <skeleton v-if="sortedProducts.length == 0"></skeleton>
            <div class="col-md-8" v-for="(report, index) in sortedProducts" :value="report.id" :key="report.id">
                <v-badge :content="reportStatuses[index]"></v-badge>
                <v-card elevation="3">
                    <v-card-title>{{ report.thema }}</v-card-title>
                    <v-card-text>
                        Duration: {{ report.start_time }} to {{ report.end_time }}
                        <br>
                        <span>
                            <v-btn x-big depressed color="success">
                                <router-link class="white--text" style="text-decoration: none; color: inherit;"
                                    v-if="isAuth()"
                                    :to="{ name: 'Details', params: { id: report.conference_id, rep_id: report.id } }">
                                    More
                                </router-link>
                            </v-btn>
                        </span>
                    </v-card-text>
                </v-card>
            </div>
            <v-btn x-big block color="primary"
                :to="{ name: 'ConferenceDetails', params: { id: this.$route.params.id } }" class="white--text">Back
            </v-btn>
        </div>
    </v-app>

</template>

<script>
export default {
    data: () => ({
        formData: {
            parentCategory: [],
        },
        categories: [],
        cats: [],
        sortedProducts: [],
        data: {},
        date: '',
        date2: '',
        menu: false,
        modal: false,
        menu2: false,
        duration: 0,
        count: 0,
        countForDuretion: 0,
        CsvButtonType: 0,
        reportStatuses: []
    }),
    mounted() {
        this.getReports()
        this.$store.dispatch('ajaxGetSubCategories', this.$route.params.id).then(() => {
            this.$store.dispatch('ajaxUser')
            this.$store.getters.getSubCategories.forEach(element => {
                this.categories.push(element.name)
            });
        })
    },
    methods: {
        isAuth() {
            return ("Authorized" in localStorage) ? true : false
        },
        isOnConference() {
            return this.$store.getters.getUserOnConferenceStatus
        },
        getReports(page = 1) {
            if (this.count == 0) {
                this.sortedProducts = []
                if (this.countForDuretion == 0) {
                    this.duration = ""
                }
                this.getAllReports([page, this.$route.params.id, this.duration, this.cats, this.date, this.date2])
            }
            else {
                this.sortedProducts = []
                this.$store.getters.getSubCategories.forEach(element => {
                    this.formData.parentCategory.forEach(cat => {
                        if (element.name == cat) this.cats.push(element.id)
                    })
                })
                this.cats = [...new Set(this.cats)]
                if (this.countForDuretion == 0) {
                    this.duration = ""
                }
                this.$store.dispatch("ajaxReports", [page, this.$route.params.id, this.duration, this.cats, this.date, this.date2]).then(() => {
                    this.$store.getters.getReports.data.forEach(element => {
                        this.sortedProducts.push(element);
                    })
                }).then(() => {
                    this.data = this.$store.getters.getReports
                    return this.$store.getters.getReports;
                })
                this.cats = []
            }
        },
        getAllReports(data) {
            this.sortedProducts = []
            this.$store.dispatch('ajaxReports', data).then(() => {
                this.$store.getters.getReports.data.forEach(element => {
                    if (element.zoom_meeting_id == null) {
                        this.reportStatuses.push('offline')
                        this.sortedProducts.push(element);
                    }
                    else {
                        this.$store.dispatch('ajaxGetMeeting', element.zoom_meeting_id).then(() => {
                            let status = this.$store.getters.getMeeting['data']['status']
                            this.reportStatuses.push(status)
                            this.sortedProducts.push(element);
                        })
                    }

                })
            }).then(() => {
                this.data = this.$store.getters.getReports
                return this.$store.getters.getReports;
            })
        },
        resetFilters() {
            this.$router.go()
        },
        status(id) {
            if (id == null) {
                return 'offline'
            }
            return this.getMeeting(id)
        },
        getMeeting(id) {
            this.$store.dispatch('ajaxGetMeeting', id).then(() => {
                console.log(id)
                let status = this.$store.getters.getMeeting['data']['status']
                return String(status)
            })
        }
    }
}

</script>