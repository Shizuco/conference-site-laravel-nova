<template>
    <v-app>
        <v-row class="shrink">
            <FlashMessage :position="'right bottom'"></FlashMessage>
            <v-col>
                <auth style="height: 80px; width: 800px"></auth>
            </v-col>
            <v-col align="right" style=" z-index: 100; position: relative; height: fit-content; top: -3px;">
                <v-text-field @click="toggleMarker" @click:append="toggleMarker" clickable solo color="white"
                    placeholder="Search..." v-model="title"
                    :append-icon="marker ? 'mdi-map-marker' : 'mdi-map-marker-off'">
                </v-text-field>
            </v-col>
            <v-col align="right" style=" z-index: 100; height: 94px; width: 30px; top: -20px; position: relative;">
                <Slide right width="550" v-if="isAuth()" v-on:lazy="true">
                    <div v-for="name in categories" :value="name" :key="name.id">
                        <v-checkbox v-model="formData.parentCategory" :label="name" :value="name"
                            @change="count++; getConferences()">
                        </v-checkbox>
                    </div>
                    <v-row>
                        <v-col cols="4" sm="4" md="6">
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
                                    <v-btn text color="primary"
                                        @click="$refs.menu.save(date); count++; getConferences()">
                                        OK
                                    </v-btn>
                                </v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col cols="12" sm="10" md="26">
                            <v-menu ref="menu2" v-model="menu2" :close-on-content-click="false"
                                :return-value.sync="date2" transition="scale-transition" offset-y min-width="auto">
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field v-model="date2" label="Date to" prepend-icon="mdi-calendar" readonly
                                        v-bind="attrs" v-on="on"></v-text-field>
                                </template>
                                <v-date-picker v-model="date2" no-title scrollable>
                                    <v-spacer></v-spacer>
                                    <v-btn text color="primary" @click="menu2 = false">
                                        Cancel
                                    </v-btn>
                                    <v-btn text color="primary"
                                        @click="$refs.menu2.save(date2); count++; getConferences()">
                                        OK
                                    </v-btn>
                                </v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col cols="12">
                            <v-slider v-model="numberOfReports" :thumb-size="24" thumb-label="always"
                                @change="count++; number++; getConferences()" max="10"></v-slider>
                        </v-col>
                        <v-col>
                            <v-btn x-big depressed color="error" @click="resetFilters">Reset</v-btn>
                        </v-col>
                    </v-row>
                </Slide>
            </v-col>
        </v-row>
        <v-card v-if="menuOpen">
            <v-card-title>
                <v-text-field label="Search" single-line solo style="padding-right: 20px" @change="getSearch()"
                    v-model="title"></v-text-field>
                <v-divider :vertical="true" color="black" class="mx-2"></v-divider>
                <v-radio-group v-model="category" @change="getSearch()"
                    style="width: 400px; padding-left: 20px; padding-bottom: 20px;">
                    <v-radio v-for="name in tools" :key="name" :label="name" :value="name"></v-radio>
                </v-radio-group>
            </v-card-title>
            <v-card-text>
                <div class="row justify-content-center">
                    <div class="col-md-12" v-for="conference in conferences" :value="conference.id"
                        :key="conference.id">
                        <v-card elevation="3">
                            <v-card-title>{{ conference.title }}</v-card-title>
                            <v-card-text>
                                Appointed time: {{ conference.date }} {{ conference.time }}
                                <br>
                                <span>
                                    <v-btn x-big depressed color="success">
                                        <router-link class="white--text" style="text-decoration: none; color: inherit;"
                                            :to="{ name: 'ConferenceDetails', params: { id: conference.id } }"
                                            target="_blank">
                                            More
                                        </router-link>
                                    </v-btn>
                                </span>
                            </v-card-text>
                            Conference
                        </v-card>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-12" v-for="report in reports" :value="report.id" :key="report.id">
                        <v-card elevation="3">
                            <v-card-title>{{ report.thema }}</v-card-title>
                            <v-card-text>
                                Duration: {{ report.start_time }} to {{ report.end_time }}
                                <br>
                                <span>
                                    <v-btn x-big depressed color="success">
                                        <router-link class="white--text" style="text-decoration: none; color: inherit;"
                                            :to="{ name: 'Details', params: { id: report.conference_id, rep_id: report.id } }"
                                            target="_blank">
                                            More
                                        </router-link>
                                    </v-btn>
                                </span>
                            </v-card-text>
                            Report
                        </v-card>
                    </div>
                </div>
            </v-card-text>
        </v-card>
        <div class="row justify-content-center">
            <skeleton v-if="sortedProducts.length == 0 && search == 0"></skeleton>
            <div class="col-md-12" v-for="conference in sortedProducts" :value="conference.id" :key="conference.id">
                <v-card elevation="3">
                    <v-card-title>{{ conference.title }}</v-card-title>
                    <v-card-text>
                        Appointed time: {{ conference.date }} {{ conference.time }}
                        <br>
                        <span>
                            <v-btn x-big depressed color="success">
                                <router-link class="white--text" style="text-decoration: none; color: inherit;"
                                    v-if="isAuth()" :to="{ name: 'ConferenceDetails', params: { id: conference.id } }">
                                    More
                                </router-link>
                                <router-link class="white--text" style="text-decoration: none; color: inherit;" v-else
                                    :to="{ name: 'Registration' }">More</router-link>
                            </v-btn>
                        </span>
                    </v-card-text>
                </v-card>
            </div>
            <Pagination :data="data" @pagination-change-page="getConferences" />
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
        numberOfReports: null,
        count: 0,
        number: 0,
        menuOpen: false,
        disable: false,
        marker: true,
        tools: ['Report', 'Conference'],
        category: '',
        conferences: [],
        reports: [],
        title: '',
        search: 0,
        CsvButtonType: 0
    }),
    mounted() {
        if (this.$route.params.plan == 'success') {
            this.flashMessage.show({
                status: 'success',
                title: 'Success',
                message: 'Paid successfully!'
            });
        }
        this.getConferences()
        this.$store.dispatch('ajaxGetCategories').then(() => {
            console.log(this.$store.getters.getCategories)
            this.$store.getters.getCategories.forEach(element => {
                this.categories.push(element.name)
            });
        })
        if (this.isAuth()) {
            this.$store.dispatch("ajaxUser");
        }
    },
    methods: {
        isAuth() {
            return ("Authorized" in localStorage) ? true : false;
        },
        deleteConference(id) {
            this.$store.dispatch("ajaxConferenceDelete", id);
            this.$store.getters.deleteConference;
            this.$router.go();
        },
        sortByReportsNumber() {
            this.sortedProducts = [];
            this.getConferences.forEach(element => {
                if (element.reports.length == this.reportsNumber) {
                    this.sortedProducts.push(element)
                }
            });
        },
        getConferences(page = 1) {
            if (this.count == 0) {
                this.sortedProducts = []
                if (this.number == 0) {
                    this.numberOfReports = ""
                }
                this.getAllConferences([page, this.numberOfReports, this.cats, this.date, this.date2])
            }
            else {
                this.sortedProducts = []
                this.$store.getters.getCategories.forEach(element => {
                    this.formData.parentCategory.forEach(cat => {
                        if (element.name == cat) this.cats.push(element.id)
                    })
                })
                this.cats = [...new Set(this.cats)]
                if (this.number == 0) {
                    this.numberOfReports = ""
                }
                this.$store.dispatch("ajaxConferences", [page, this.numberOfReports, this.cats, this.date, this.date2]).then(() => {
                    this.$store.getters.getConferences.data.forEach(element => {
                        this.sortedProducts.push(element);
                    })
                }).then(() => {
                    this.data = this.$store.getters.getConferences
                    return this.$store.getters.getConferences;
                })
                this.cats = []
            }
        },
        getAllConferences(page) {
            this.sortedProducts = []
            this.$store.dispatch("ajaxConferences", page).then(() => {
                this.$store.getters.getConferences.data.forEach(element => {
                    this.sortedProducts.push(element);
                })
            }).then(() => {
                this.data = this.$store.getters.getConferences
                return this.$store.getters.getConferences
            })
        },
        resetFilters() {
            this.$router.go()
        },
        toggleMarker() {
            this.menuOpen = !this.menuOpen
            this.marker = !this.marker
        },
        getSearch() {
            this.search++
            if (this.title != '') {
                this.sortedProducts = []
                if (this.category == 'Conference') {
                    this.getConfByName()
                }
                else if (this.category == 'Report') {
                    this.getRepByName()
                }
                else {
                    this.getConfByName()
                    this.getRepByName()
                }
            }
            else {
                this.conferences = []
                this.reports = []
            }
        },
        getConfByName(page = 1) {
            this.conferences = []
            this.reports = []
            this.$store.dispatch('ajaxGetConferenceByName', [page, this.title]).then(() => {
                this.$store.getters.getConferences.data.forEach(element => {
                    this.conferences.push(element);
                })
            })
        },
        getRepByName(page = 1) {
            this.conferences = []
            this.reports = []
            this.$store.dispatch('ajaxGetReportsByName', [page, this.title]).then(() => {
                this.$store.getters.getReports.data.forEach(element => {
                    this.reports.push(element);
                })
            })
        },
    }
}
</script>