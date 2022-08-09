<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <div class="row justify-content-center">
            <v-select :items="categories" v-model="selected" @change="sortByCategory" class="rounded-0 col-md-8"
                outlined />
            <div class="col-md-8" v-for="report in sortedProducts" :value="report.id" :key="report.id">
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
        categories: ['All'],
        sortedProducts: [],
        selected: 'All'
    }),
    mounted() {
        this.$store.dispatch('ajaxReports', this.$route.params.id)
        this.$store.dispatch('ajaxGetSubCategories', this.$route.params.id).then(() => {
            this.$store.getters.getSubCategories.forEach(element => {
                this.categories.push(element.name)
            });
            this.$store.getters.getReports.forEach(element => {
                this.sortedProducts.push(element)
            })
        })
    },
    computed: {
        getReports() {
            return this.$store.getters.getReports
        }
    },
    methods: {
        isAuth() {
            return ("Authorized" in localStorage) ? true : false
        },
        sortByCategory(category) {
            if (category != 'All') {
                this.sortedProducts = []
                this.$store.getters.getSubCategories.forEach(element => {
                    if (category == element.name) {
                        this.$store.dispatch('ajaxGetCategoryReportNumber', element.id).then(() => {
                            this.$store.getters.getReports[0].reports.forEach(element => {
                                if(element.conference_id == this.$route.params.id){
                                    this.sortedProducts.push(element)
                                }   
                            })
                        })
                    }
                });
            }
            else {
                this.sortedProducts = []
                this.$store.dispatch('ajaxReports', this.$route.params.id).then(() => {
                    this.$store.getters.getReports.forEach(element => {
                        this.sortedProducts.push(element)
                    })
                })
            }
        }
    }
}

</script>