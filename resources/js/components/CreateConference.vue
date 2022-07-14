<template>
   <div class="container">
        <auth style="height: 80px"></auth>
        <v-main>
            <v-container class="fill-height" fluid>
                <v-row align="center" justify="center" dense>
                <v-col cols="12" sm="8" md="4" lg="8">
                    <v-card elevation="0">
                        <v-card-text>
                            <v-form>
                                <v-row>
                                    <v-col>
                                        <ValidationProvider rules="required|alpha|min:2|max:255" v-slot="{ errors }" name="title">
                                            <v-text-field label="Введите название" name="title" id="title" type="text" class="rounded-0" min="2" max="255" outlined required v-model="formData.title"></v-text-field>
                                            <span>{{ errors[0] }}</span>
                                        </ValidationProvider>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col>
                                        <ValidationProvider rules="required" v-slot="{ errors }" name="date">
                                            <v-text-field type="date" id="date" class="rounded-0"  outlined required name="date" v-model="formData.date"></v-text-field>
                                            <span>{{ errors[0] }}</span>
                                        </ValidationProvider>
                                    </v-col>
                                    <v-col>
                                        <ValidationProvider rules="required" v-slot="{ errors }" name="time">
                                            <v-text-field type="time" id="time" class="rounded-0"  outlined required name="time" v-model="formData.time"></v-text-field>
                                            <span>{{ errors[0] }}</span>
                                        </ValidationProvider>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col>
                                        <select name="country" id="country" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" required>
                                            <option value="1">Япония</option>
                                            <option value="2">Россия</option>
                                            <option value="3">Украина</option>
                                            <option value="4">Беларусь</option>
                                            <option value="5">Китай</option>
                                            <option value="6">Сша</option>
                                            <option value="7">Франция</option>
                                            <option value="8">Англия</option>
                                        </select>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col>
                                        <gmap-map
                                                :zoom="15"
                                                :center="{
                                                lat: 0,
                                                lng: 0
                                                }"
                                                mapTypeId='roadmap'
                                                style="width:100%;height:500px"
                                                id="map"
                                                @click="change"
                                            >
                                            <gmap-marker
                                                :position="{
                                                    lat: Number(formData.address_lat),
                                                    lng: Number(formData.address_lon)
                                                }"
                                                :draggable="true"
                                            ></gmap-marker>
                                            </gmap-map>
                                    </v-col>
                                    <ValidationProvider rules="required|min:-180|max:180" v-slot="{ errors }">
                                        <input type="number" id="lat" name="address_lat" class="form-control" v-model="formData.address_lat" style="display:none">
                                        <span>{{ errors[0] }}</span>
                                    </ValidationProvider>
                                    <ValidationProvider rules="required|min:-180|max:180" v-slot="{ errors }">
                                        <input type="number" id="lon" name="address_lon" class="form-control" v-model="formData.address_lon" style="display:none">
                                        <span>{{ errors[0] }}</span>
                                    </ValidationProvider>
                                </v-row>
                                <v-row>
                                    <v-col><v-btn x-large block red color="#000000"><router-link :to="{name: 'MainPage'}" class="text-h5 white--text">Назад</router-link></v-btn></v-col>
                                    <v-col><v-btn x-large block class="text-h5 white--text" @click="createConference()">Сохранить</v-btn></v-col>
                                </v-row>
                            </v-form>
                        </v-card-text>
                    </v-card>
                </v-col>
                </v-row>
            </v-container>
        </v-main>
    </div>
</template>

<script>

    export default {
        data: ()=>({
            formData:{
                title: '',
                date: '',
                time: '',
                address_lat:'0',
                address_lon: '0'
            }
        }),
        computed: {
            createConference(){
                let data = {
                    'title' : document.getElementById('title').value,
                    'date' : document.getElementById('date').value,
                    'time' : document.getElementById('time').value,
                    'address_lat' : document.getElementById('lat').value,
                    'address_lon' : document.getElementById('lon').value,
                    'country' : document.getElementById('country').value
                }
                this.$store.dispatch('ajaxConferenceCreate', data)
                this.$store.getters.createConference
                this.$router.replace('/conferences')
            }
        },
        methods:{
            change(e){
                if(this.$store.getters.getConference == undefined){
                    let data = {
                        'address_lat' : 10,
                        'address_lon' : 10,
                    }
                    this.$store.dispatch('changePoint', data)
                }
                else{
                    let data = {
                    'address_lat' : e.latLng.lat(),
                    'address_lon' : e.latLng.lng(),
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
span{
    color: red;
}
</style>