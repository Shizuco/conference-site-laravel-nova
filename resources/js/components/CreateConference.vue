<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <v-row align="center" justify="center" dense>
            <v-col cols="12" sm="8" md="4" lg="10">
                <v-card elevation="10">
                    <v-card-text>
                        <v-form>
                            <ValidationObserver tag="form" @submit.prevent="createConference">
                                <v-row class="pa-0" style="margin-top: 0px !important">
                                    <v-col class="pa-0">
                                        <ValidationProvider rules="required|alpha|min:2|max:255" v-slot="{ errors }"
                                            name="title">
                                            <v-col style="height: 10px">
                                                <span style="font-size:smaller">{{ errors[0] }}</span>
                                            </v-col>
                                            <v-col>
                                                <v-text-field label="Title" name="title" id="title" type="text"
                                                    class="rounded-0" min="2" max="255" outlined required
                                                    v-model="formData.title"></v-text-field>
                                            </v-col>
                                        </ValidationProvider>
                                    </v-col>
                                </v-row>
                                <v-row class="pa-0" style="margin-top: 0px !important">
                                    <v-col class="pa-0">
                                        <ValidationProvider rules="required" v-slot="{ errors }" name="date">
                                            <v-col style="height: 10px">
                                                <span style="font-size:smaller">{{ errors[0] }}</span>
                                            </v-col>
                                            <v-col>
                                                <v-text-field type="date" id="date" class="rounded-0" outlined required
                                                    name="date" v-model="formData.date"></v-text-field>
                                            </v-col>
                                        </ValidationProvider>
                                    </v-col>
                                    <v-col class="pa-0">
                                        <ValidationProvider rules="required" v-slot="{ errors }" name="time">
                                            <v-col style="height: 10px">
                                                <span style="font-size:smaller">{{ errors[0] }}</span>
                                            </v-col>
                                            <v-col>
                                                <v-text-field type="time" id="time" class="rounded-0" outlined required
                                                    name="time" v-model="formData.time"></v-text-field>
                                            </v-col>

                                        </ValidationProvider>
                                    </v-col>
                                </v-row>
                                <v-row class="pa-0" style="margin-top: 0px !important">
                                    <v-col class="pa-0">
                                        <ValidationProvider rules="required" v-slot="{ errors }" name="country">
                                            <v-col style="height: 10px">
                                                <span style="font-size:smaller">{{ errors[0] }}</span>
                                            </v-col>
                                            <v-col>
                                                <v-select name="country" id="country" class="rounded-0" outlined
                                                    v-model="formData.country" :items="countries">
                                                </v-select>
                                            </v-col>
                                        </ValidationProvider>
                                    </v-col>
                                </v-row>
                                <v-row class="pa-0" style="margin-top: 0px !important">
                                    <v-col class="pa-0">
                                        <v-select name="category" id="category" class="rounded-0" outlined
                                            v-model="formData.category" :items="categories">
                                        </v-select>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col>
                                        <gmap-map :zoom="15" :center="{
                                            lat: 0,
                                            lng: 0
                                        }" mapTypeId='roadmap' style="width:100%;height:300px" id="map"
                                            @click="change">
                                            <gmap-marker :position="{
                                                lat: Number(formData.address_lat),
                                                lng: Number(formData.address_lon)
                                            }" :draggable="true"></gmap-marker>
                                        </gmap-map>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col>
                                        <v-btn x-big block color="primary" class="white--text"
                                            :to="{ name: 'MainPage' }">Back
                                        </v-btn>
                                    </v-col>
                                    <v-col>
                                        <v-btn type="submit" class="white--text" color="success" x-big block>
                                            Save</v-btn>
                                    </v-col>
                                </v-row>
                            </ValidationObserver>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-app>
</template>

<script>

export default {
    data: () => ({
        formData: {
            title: '',
            date: '',
            time: '',
            category: 'IT',
            address_lat: '0',
            address_lon: '0',
            country: 'Japan'
        },
        countries: ['Japan', 'Russia', 'Ukraine', 'Belarus', 'Canada', 'France', 'England', 'USA', 'China', 'Korea'],
        categories: []
    }),
    mounted() {
        this.$store.dispatch('ajaxGetRootCategories').then(() => {
            this.$store.getters.getRootCategories.forEach(element => {
                this.categories.push(element.name)
            });
        })
    },
    computed: {
        createConference() {
            this.$store.getters.getRootCategories.forEach(element => {
                if (this.formData.category == element.name) {
                    this.formData.category = element.id
                }
            });
            let data = {
                'title': this.formData.title,
                'date': this.formData.date,
                'time': this.formData.time,
                'category_id': this.formData.category,
                'address_lat': this.formData.address_lat,
                'address_lon': this.formData.address_lon,
                'country': this.formData.country
            }
            this.$store.dispatch('ajaxConferenceCreate', data).then(() => {
                this.$router.replace('/conferences')
            })

        }
    },
    methods: {
        change(e) {
            if (this.$store.getters.getConference == undefined) {
                let data = {
                    'address_lat': 10,
                    'address_lon': 10,
                }
                this.$store.dispatch('changePoint', data)
            }
            else {
                let data = {
                    'address_lat': e.latLng.lat(),
                    'address_lon': e.latLng.lng(),
                }
                this.$store.dispatch('changePoint', data)
                this.formData.address_lat = e.latLng.lat()
                this.formData.address_lon = e.latLng.lng()
            }
        }
    }
}
</script>

<style>
span {
    color: red;
}
</style>