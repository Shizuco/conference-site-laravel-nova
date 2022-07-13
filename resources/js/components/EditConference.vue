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
                                                <v-text-field name="title" id="title" prepend-inner-icon="mdi-mail" type="text" class="rounded-0" outlined required v-model="getConference.title"></v-text-field>
                                                <span>{{ errors[0] }}</span>
                                            </ValidationProvider>
                                        </v-col>
                                    </v-row>
                                    <v-row>
                                        <v-col>
                                            <ValidationProvider rules="required" v-slot="{ errors }" name="date">
                                                <v-text-field type="date" id="date" class="rounded-0"  outlined required v-model="getConference.date" name="date"></v-text-field>
                                                <span>{{ errors[0] }}</span>
                                            </ValidationProvider>
                                        </v-col>
                                        <v-col>
                                            <ValidationProvider rules="required" v-slot="{ errors }" name="time">
                                                <v-text-field type="time" id="time" class="rounded-0"  outlined required v-model="getConference.time" name="time"></v-text-field>
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
                                        <v-col><div id="map" style="width:100%;height:400px;"></div></v-col>
                                        <ValidationProvider rules="required|min:-180|max:180" v-slot="{ errors }">
                                            <input type="number" id="lat" name="address_lat" class="form-control" v-model="getConference.address_lat">
                                            <span>{{ errors[0] }}</span>
                                            <input type="number" id="lon" name="address_lon" class="form-control" v-model="getConference.address_lon">
                                            <span>{{ errors[0] }}</span>
                                        </ValidationProvider>
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

    export default {
        mounted(){
            let id = this.$route.params.id
            this.$store.dispatch('ajaxGetConference', id)
        },
        computed: {
            getConference(){
                return this.$store.getters.getConference
            },
            editConference(){
                let data = {
                    'id': this.$route.params.id,
                    'title' : document.getElementById('title').value,
                    'date' : document.getElementById('date').value,
                    'time' : document.getElementById('time').value,
                    'address_lat' : document.getElementById('lat').value,
                    'address_lon' : document.getElementById('lon').value,
                    'country' : document.getElementById('country').value
                    }
                this.$store.dispatch('ajaxConferenceEdit', data)
                this.$store.getters.createConference
                location.reload(); 
            }
        },
        methods:{
            onSubmit(){
                console.log(this.formData)
            }
        }
    }
</script>

<style>
span{
    color: red;
}
</style>