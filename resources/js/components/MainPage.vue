<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <Slide right style=" z-index: 100; position: relative; top: -100px;" width="550">
            <div v-for="name in categories" :value="name" :key="name.id">
                <v-checkbox v-model="formData.parentCategory" :label="name" :value="name"
                    @change="count++; getConferences()">
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
                        <v-btn text color="primary" @click="$refs.menu.save(date); count++; getConferences()">
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
                        <v-btn text color="primary" @click="$refs.menu2.save(date2); count++; getConferences()">
                            OK
                        </v-btn>
                    </v-date-picker>
                </v-menu>
            </v-col>
            <v-col cols="12">
                <v-slider v-model="numberOfReports" :thumb-size="24" thumb-label="always"
                    @change="count++; getConferences()" max="10"></v-slider>
            </v-col>
            <v-col>
                <v-btn x-big depressed color="error" @click="resetFilters">Reset</v-btn>
            </v-col>
        </Slide>
        <div class="row justify-content-center">
            <div class="col-md-8" v-for="conference in sortedProducts" :value="conference.id" :key="conference.id">
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
                        <span>
                            <v-btn v-if="isAdmin()" depressed color="warning" big
                                :to="{ name: 'EditConference', params: { id: conference.id } }">Edit</v-btn>
                        </span>
                        <span>
                            <v-btn v-if="isAdmin()" x-big depressed color="error"
                                @click="deleteConference(conference.id)">Delete</v-btn>
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
        numberOfReports: 0,
        count: 0
    }),
    mounted() {
        this.getConferences()
        this.$store.dispatch('ajaxGetCategories').then(() => {
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
        isAdmin() {
            return (this.$store.getters.getUser.role == "admin") ? true : false;
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
                this.getAllConferences(page)
            }
            else {
                this.sortedProducts = []
                this.$store.getters.getCategories.forEach(element => {
                    this.formData.parentCategory.forEach(cat => {
                        if (element.name == cat) this.cats.push(element.id)
                    })
                })
                this.cats = [...new Set(this.cats)]
                this.$store.dispatch("ajaxGetConferencesF", [page, this.numberOfReports, this.cats, this.date, this.date2]).then(() => {
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
                return this.$store.getters.getConferences;
            })
        },
        resetFilters() {
            this.$router.go()
        }
    }
}
</script>