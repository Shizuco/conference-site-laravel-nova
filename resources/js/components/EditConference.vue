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
                                    <v-col><v-text-field name="title" id="title" prepend-inner-icon="mdi-mail" type="text" class="rounded-0" min="2" max="255" outlined required :value=getConference.title></v-text-field></v-col>
                                </v-row>
                                <v-row>
                                    <v-col><v-text-field name="date" id="date" prepend-inner-icon="mdi-mail" type="text" class="rounded-0" outlined required :value=getConference.date></v-text-field></v-col>
                                    <v-col><v-text-field name="time" id="time" prepend-inner-icon="mdi-mail" type="text" class="rounded-0" min="2" max="255" outlined required :value=getConference.time></v-text-field></v-col>
                                </v-row>
                                <v-row>
                                    <v-col><v-text-field name="country" id="country" prepend-inner-icon="mdi-mail" type="text" class="rounded-0" outlined required :value=getConference.country></v-text-field></v-col>
                                </v-row>
                                <v-row>
                                    <v-col><div id="map" style="width:100%;height:400px;"></div></v-col>
                                    <input type="text" id="lat" name="address_lat" class="form-control" :value=getConference.address_lat style="display:none;">
                                    <input type="text" id="lon" name="address_lon" class="form-control" :value=getConference.address_lon style="display:none;">
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
        mounted() {
            let id = this.$route.params.id
            this.$store.dispatch('ajaxGetConference', id)
        },
        computed: {
            getConference(){
                return this.$store.getters.getConference
            },
            editConference(){
                let href = document.location.origin
                let data = {
                    'id': this.$route.params.id,
                    'title' : document.getElementById('title').value,
                    'date' : document.getElementById('date').value,
                    'time' : document.getElementById('time').value,
                    'address_lat' : document.getElementById('lat').value,
                    'address_lon' : document.getElementById('lon').value,
                    'country' : document.getElementById('country').value
                }
                console.log(data)
                this.$store.dispatch('ajaxConferenceEdit', data)
                this.$store.getters.createConference
                document.location.href = href
            }
        }
    }
</script>
