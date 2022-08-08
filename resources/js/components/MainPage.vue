<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <Slide right style=" z-index: 100; position: relative; top: -100px">
            <div v-for="name in categories" :value="name" :key="name.id">
                <v-checkbox v-model="formData.parentCategory" :label="name" :value="name" @change="getConferences"></v-checkbox>
            </div>    
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
        sortedProducts: [],
        data: {},
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
            if(this.formData.parentCategory.length != 0){
               this.getConferencesWithCat(page, this.formData.parentCategory) 
            }
            else{
                this.getAllConferences(page)
            }
        },
        getConferencesWithCat(page, cat) {
            this.sortedProducts = []
            let selectedCat = []
            this.$store.getters.getCategories.forEach(element => {
                cat.forEach(cat=>{
                    if(cat == element.name){
                        selectedCat.push(element.id)
                    }
                })
            });
            this.$store.dispatch("ajaxGetConferencesWithCat", [page, selectedCat]).then(() => {
                this.$store.getters.getConferences.data.forEach(element => {
                    this.sortedProducts.push(element);
                })
            })
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
        }
    },
}
</script>