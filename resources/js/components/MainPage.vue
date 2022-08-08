<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <Slide right style=" z-index: 100; position: relative; top: -100px">
         <v-subheader>Tick labels</v-subheader>

    <v-card-text>
      <v-slider
        v-model="fruits"
        :tick-labels="ticksLabels"
        :max="3"
        step="1"
        ticks="always"
        tick-size="4"
      ></v-slider>
    </v-card-text>
        </Slide>
        <div class="row justify-content-center">
            <v-select :items="categories" v-model="selected" @change="sortByCategory" class="rounded-0 col-md-8"
                outlined />
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
        </div>
    </v-app>

</template>

<script>

export default {
    data: () => ({
        categories: ["All"],
        sortedProducts: [],
        selected: "All",
        ticksLabels: [
        ],
    }),
    mounted() {
        this.$store.dispatch("ajaxConferences").then(() => {
            this.$store.dispatch("ajaxGetRootCategories").then(() => {
                this.$store.getters.getRootCategories.forEach(element => {
                    this.categories.push(element.name);
                });
                console.log(this.categories);
                this.$store.getters.getConferences.forEach(element => {
                    this.sortedProducts.push(element);
                });
            });
        });
        if (this.isAuth()) {
            this.$store.dispatch("ajaxUser");
        }
    },
    computed: {
        getConferences() {
            return this.$store.getters.getConferences;
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
        sortByCategory(category) {
            if (category != "All") {
                this.sortedProducts = [];
                this.$store.getters.getRootCategories.forEach(element => {
                    if (category == element.name) {
                        this.$store.dispatch("ajaxGetCategoryConferenceNumber", element.id).then(() => {
                            this.$store.getters.getConferences[0].conferences.forEach(element => {
                                this.sortedProducts.push(element);
                            });
                        });
                    }
                });
            }
            else {
                this.sortedProducts = [];
                this.$store.dispatch("ajaxConferences").then(() => {
                    this.$store.getters.getConferences.forEach(element => {
                        this.sortedProducts.push(element);
                    });
                });
            }
        }
    },
}
</script>