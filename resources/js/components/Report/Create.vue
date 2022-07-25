<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <v-row align="center" justify="center" dense>
            <v-col cols="12" sm="8" md="4" lg="10">
                <v-card elevation="10">
                    <v-card-text>
                        <v-form>
                            <ValidationObserver tag="form" @submit.prevent="onSubmit">
                                <v-row>
                                    <v-col>
                                        <ValidationProvider rules="required|alpha|min:2|max:255" v-slot="{ errors }"
                                            name="title">
                                            <span>{{ errors[0] }}</span>
                                            <v-text-field label="Thema" name="title" id="title" type="text"
                                                class="rounded-0" min="2" max="255" outlined required
                                                v-model="formData.thema"></v-text-field>
                                        </ValidationProvider>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col>
                                        <ValidationProvider rules="required" ref="provider" v-slot="{ errors }" name="start_time">
                                            <span>{{ errors[0] }}</span>
                                            <v-datetime-picker label="Date of start" v-model="formData.start_time"> </v-datetime-picker>
                                            
                                        </ValidationProvider>
                                    </v-col>
                                    <v-col>
                                        <ValidationProvider rules="required" v-slot="{ errors }" name="end_time">
                                            <span>{{ errors[0] }}</span>
                                            <v-datetime-picker label="Date of end" v-model="formData.end_time"> </v-datetime-picker>
                                        </ValidationProvider>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col>
                                        <ValidationProvider rules="required" v-slot="{ errors }" name="description">
                                            <span>{{ errors[0] }}</span>
                                            <v-textarea label="Enter description" v-model="formData.description"
                                                outlined required class="rounded-0"></v-textarea>
                                        </ValidationProvider>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col>
                                        <v-file-input label="Presentation" v-model="formData.presentation" outlined required class="rounded-0"></v-file-input>
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
            thema: '',
            start_time: '',
            end_time: '',
            description: '',
            presentation: ''
        }
    }),
    mounted() {
        this.$store.dispatch('ajaxUser')
    },
    computed: {
},
methods:{
    onSubmit() {
            let data = {
                'thema': this.formData.thema,
                'start_time': new Date(this.formData.start_time).toLocaleString(),
                'end_time': new Date(this.formData.end_time).toLocaleString(),
                'description': this.formData.description,
                'presentation': this.formData.presentation
            }
            this.$store.dispatch('ajaxCreateReport', [data, this.$route.params.id]).then(()=>{
                this.$router.replace('/conferences')
            }).catch(error => {
            this.$refs.provider.applyResult({
               errors: error.response.data.errors.start_time,
               valid: false, 
               failedRules: {} 
            });
            console.log(error.response.data)
            })
    },
}
}
</script>