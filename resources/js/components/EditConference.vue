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
                                        <v-text-field name="title" id="title" prepend-inner-icon="mdi-mail" type="text"
                                            class="rounded-0" outlined required v-model="formData.title"></v-text-field>
                                    </ValidationProvider>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <ValidationProvider rules="required" v-slot="{ errors }" name="date">
                                        <span>{{ errors[0] }}</span>
                                        <v-text-field type="date" id="date" class="rounded-0" outlined required
                                            v-model="formData.date" name="date"></v-text-field>
                                    </ValidationProvider>
                                </v-col>
                                <v-col>
                                    <ValidationProvider rules="required" v-slot="{ errors }" name="time">
                                        <span>{{ errors[0] }}</span>
                                        <v-text-field type="time" id="time" class="rounded-0" outlined required
                                            v-model="formData.time" name="time"></v-text-field>
                                    </ValidationProvider>
                                </v-col>
                            </v-row>
                            <ValidationProvider rules="required|min:-180|max:180" v-slot="{ errors }">
                                <span>{{ errors[0] }}</span>
                                <input type="number" id="lat" name="address_lat" class="form-control"
                                    v-model="formData.address_lat" style="display:none">
                            </ValidationProvider>
                            <ValidationProvider rules="required|min:-180|max:180" v-slot="{ errors }">
                                <span>{{ errors[0] }}</span>
                                <input type="number" id="lon" name="address_lon" class="form-control"
                                    v-model="formData.address_lon" style="display:none">
                            </ValidationProvider>
                            <v-row>
                                <v-col>
                                    <ValidationProvider rules="required" v-slot="{ errors }" name="country">
                                        <span>{{ errors[0] }}</span>
                                        <v-select name="country" id="country" class="rounded-0" outlined required
                                            v-model="formData.country" :items="countries">
                                        </v-select>
                                    </ValidationProvider>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <gmap-map :zoom="15" :center="{
                                        lat: Number(getConference.address_lat),
                                        lng: Number(getConference.address_lon)
                                    }" mapTypeId='roadmap' style="width:100%;height:300px" id="map"
                                        @click="change">
                                        <gmap-marker :position="{
                                            lat: Number(getConference.address_lat),
                                            lng: Number(getConference.address_lon)
                                        }" :draggable="true"></gmap-marker>
                                    </gmap-map>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-btn x-big block color="primary" :to="{ name: 'MainPage' }" class="white--text">
                                        Назад</v-btn>
                                </v-col>
                                <v-col>
                                    <v-btn x-big block color="success" class="white--text" @click="editConference()">
                                        Сохранить</v-btn>
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
        countries: ['Japan', 'Russia', 'Ukraine', 'Belarus', 'Canada', 'France', 'England', 'USA', 'China', 'Korea'],
        formData: {
            title: '',
            date: '',
            time: '',
            address_lat: '0',
            address_lon: '0',
            country: ''
        },
    }),
    mounted() {
        let id = this.$route.params.id
        this.$store.dispatch('ajaxGetConference', id).then(() => {
            this.formData.title = this.getConference.title
            this.formData.date = this.getConference.date
            this.formData.time = this.getConference.time
            this.formData.address_lat = this.getConference.address_lat
            this.formData.address_lon = this.getConference.address_lon
            this.formData.country = this.getConference.country
            //console.log(response)
        })

    },
    computed: {
        getConference() {
            return this.$store.getters.getConference
        },
        editConference() {
            let data = {
                'id': this.$route.params.id,
                'title': this.formData.title,
                'date': this.formData.date,
                'time': this.formData.time,
                'address_lat': this.formData.address_lat,
                'address_lon': this.formData.address_lon,
                'country': this.formData.country
            }
            this.$store.dispatch('ajaxConferenceEdit', data)
            this.$router.replace('/conferences')
        },
    },
    methods: {
        change(e) {
            let data = {
                'address_lat': e.latLng.lat(),
                'address_lon': e.latLng.lng(),
            }
            this.$store.dispatch('changePoint', data)
        }
    }
}
</script>

<style>
span {
    color: red;
}
</style>