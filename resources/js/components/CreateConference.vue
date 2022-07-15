<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <v-row align="center" justify="center" dense>
            <v-col cols="12" sm="8" md="4" lg="10">
                <v-card elevation="10">
                    <v-card-text>
                        <v-form>
                            <v-row>
                                <v-col>
                                    <ValidationProvider rules="required|alpha|min:2|max:255" v-slot="{ errors }"
                                        name="title">
                                        <span>{{ errors[0] }}</span>
                                        <v-text-field label="Title" name="title" id="title" type="text"
                                            class="rounded-0" min="2" max="255" outlined required
                                            v-model="formData.title"></v-text-field>
                                    </ValidationProvider>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <ValidationProvider rules="required" v-slot="{ errors }" name="date">
                                        <span>{{ errors[0] }}</span>
                                        <v-text-field type="date" id="date" class="rounded-0" outlined required
                                            name="date" v-model="formData.date"></v-text-field>
                                    </ValidationProvider>
                                </v-col>
                                <v-col>
                                    <ValidationProvider rules="required" v-slot="{ errors }" name="time">
                                        <span>{{ errors[0] }}</span>
                                        <v-text-field type="time" id="time" class="rounded-0" outlined required
                                            name="time" v-model="formData.time"></v-text-field>
                                    </ValidationProvider>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <ValidationProvider rules="required" v-slot="{ errors }" name="country">
                                        <span>{{ errors[0] }}</span>
                                        <v-select label="Country" name="country" id="country" class="rounded-0" outlined
                                            required v-model="formData.country" :items="countries">
                                        </v-select>
                                    </ValidationProvider>
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
                                <ValidationProvider rules="required|min:-90|max:90" v-slot="{ errors }">
                                    <span>{{ errors[0] }}</span>
                                    <input type="number" id="lat" name="address_lat" class="form-control"
                                        v-model="formData.address_lat" style="display:none">
                                </ValidationProvider>
                                <ValidationProvider rules="required|min:-180|max:180" v-slot="{ errors }">
                                    <span>{{ errors[0] }}</span>
                                    <input type="number" id="lon" name="address_lon" class="form-control"
                                        v-model="formData.address_lon" style="display:none">
                                </ValidationProvider>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-btn x-big block color="primary" class="white--text" :to="{ name: 'MainPage' }">Back
                                    </v-btn>
                                </v-col>
                                <v-col>
                                    <v-btn x-big block color="success" class="white--text" @click="createConference()">
                                        Save</v-btn>
                                </v-col>
                            </v-row>
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
            address_lat: '0',
            address_lon: '0',
            country: ''
        },
        countries: ['Japan', 'Russia', 'Ukraine', 'Belarus', 'Canada', 'France', 'England', 'USA', 'China', 'Korea']
    }),
    computed: {
        createConference() {
            let data = {
                'title': this.formData.title,
                'date': this.formData.date,
                'time': this.formData.time,
                'address_lat': this.formData.address_lat,
                'address_lon': this.formData.address_lon,
                'country': this.formData.country
            }
            this.$store.dispatch('ajaxConferenceCreate', data)
            this.$router.replace('/conferences')
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