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
                                                <v-text-field name="title" id="title" prepend-inner-icon="mdi-mail" type="text" class="rounded-0" outlined required v-model="formData.title"></v-text-field>
                                                <span>{{ errors[0] }}</span>
                                            </ValidationProvider>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col>
                                            <ValidationProvider rules="required" v-slot="{ errors }" name="date">
                                                <v-text-field type="date" id="date" class="rounded-0" outlined required v-model="formData.date" name="date"></v-text-field>
                                                <span>{{ errors[0] }}</span>
                                            </ValidationProvider>
                                        </v-col>
                                        <v-col>
                                            <ValidationProvider rules="required" v-slot="{ errors }" name="time">
                                                <v-text-field type="time" id="time" class="rounded-0"  outlined required v-model="formData.time" name="time"></v-text-field>
                                                <span>{{ errors[0] }}</span>
                                            </ValidationProvider>
                                        </v-col>
                                    </v-row>
                                    <ValidationProvider rules="required|min:-180|max:180" v-slot="{ errors }">
                                        <input type="number" id="lat" name="address_lat" class="form-control" v-model="formData.address_lat" style="display:none">
                                        <span>{{ errors[0] }}</span>
                                    </ValidationProvider>
                                    <ValidationProvider rules="required|min:-180|max:180" v-slot="{ errors }">
                                        <input type="number" id="lon" name="address_lon" class="form-control" v-model="formData.address_lon" style="display:none">
                                        <span>{{ errors[0] }}</span>
                                    </ValidationProvider>
                                    <v-row>
                                        <v-col>
                                            <ValidationProvider rules="required" v-slot="{ errors }" name="country">
                                            <span>{{ errors[0] }}</span>
                                            <v-select name="country" id="country" class="rounded-0"  outlined required v-model="formData.country" :items="countries">
                                            </v-select>
                                        </ValidationProvider>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col>
                                            <gmap-map
                                                :zoom="15"
                                                :center="{
                                                lat: Number(getConference.address_lat),
                                                lng: Number(getConference.address_lon)
                                                }"
                                                mapTypeId='roadmap'
                                                style="width:100%;height:500px"
                                                id="map"
                                                @click="change"
                                            >
                                            <gmap-marker
                                                :position="{
                                                    lat: Number(getConference.address_lat),
                                                    lng: Number(getConference.address_lon)
                                                }"
                                                :draggable="true"
                                            ></gmap-marker>
                                            </gmap-map>   
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col><v-btn x-large block red color="#000000"><router-link :to="{name: 'MainPage'}" class="text-h5 white--text">Назад</router-link></v-btn></v-col>
                                        <v-col><v-btn x-large block class="text-h5 white--text" @click="editConference()">Сохранить</v-btn></v-col>
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
import { gmapApi } from 'vue2-google-maps';
    export default {
        data:()=>({
            countries:['Япония', 'Россия', 'Украина', 'Беларусь', 'Канада', 'Франция', 'Англия', 'США', 'Китай', 'Корея'],
            formData:{
                title: '',
                date: '',
                time: '',
                address_lat:'0',
                address_lon: '0',
                country: ''
            },
        }),
        mounted(){
            let id = this.$route.params.id
            this.$store.dispatch('ajaxGetConference', id).then(()=>{
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
            getConference(){
                return this.$store.getters.getConference
            },
            editConference(){
                let data = {
                    'id': this.$route.params.id,
                    'title' : this.formData.title,
                    'date' : this.formData.date,
                    'time' : this.formData.time,
                    'address_lat' : this.formData.address_lat,
                    'address_lon' : this.formData.address_lon,
                    'country' : this.formData.country
                    }
                this.$store.dispatch('ajaxConferenceEdit', data)
                this.$router.replace('/conferences') 
            },
        },
        methods:{
            change(e){
                let data = {
                    'address_lat' : e.latLng.lat(),
                    'address_lon' : e.latLng.lng(),
                }
                this.$store.dispatch('changePoint', data)
            }
        }
    }
</script>

<style>
span{
    color: red;
}
</style>