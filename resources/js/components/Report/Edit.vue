<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <v-row align="center" justify="center" dense>
            <v-col cols="12" sm="8" md="4" lg="10">
                <v-card elevation="10">
                    <v-card-text>
                        <v-form>
                            <ValidationObserver tag="form" ref="form" @submit.prevent="setReport">
                                <v-row class="pa-0" style="margin-top: 0px !important">
                                    <v-col class="pa-0">
                                        <ValidationProvider rules="required|min:2|max:255" v-slot="{ errors }"
                                            name="title">
                                            <v-col style="height: 10px">
                                                <span style="font-size:smaller">{{ errors[0] }}</span>
                                            </v-col>
                                            <v-col>
                                                <v-text-field label="Thema" name="title" id="title" type="text"
                                                    class="rounded-0" min="2" max="255" outlined
                                                    v-model="formData.thema">
                                                </v-text-field>
                                            </v-col>
                                        </ValidationProvider>
                                    </v-col>
                                </v-row>
                                <v-row class="pa-0" style="margin-top: 0px !important">
                                    <v-col class="pa-0">
                                        <ValidationProvider rules="required" v-slot="{ errors }" name="start_time">
                                            <v-col style="height: 10px"><span style="font-size:smaller">{{ errors[0]
                                            }}</span>
                                            </v-col>
                                            <v-col>
                                                <v-datetime-picker label="Date of start" v-model="formData.start_time"
                                                    name="start_time" vid="start_time"> </v-datetime-picker>
                                            </v-col>
                                        </ValidationProvider>
                                    </v-col>
                                    <v-col class="pa-0">
                                        <ValidationProvider rules="required" v-slot="{ errors }" name="end_ time">
                                            <v-col style="height: 10px">
                                                <span style="font-size:smaller">{{ errors[0] }}</span>
                                            </v-col>
                                            <v-col>
                                                <v-datetime-picker label="Date of end" v-model="formData.end_time"
                                                    name="end_time"> </v-datetime-picker>
                                            </v-col>
                                        </ValidationProvider>
                                    </v-col>
                                    <v-col class="pa-0">
                                        <ValidationProvider rules="required" v-slot="{ errors }" name="description">
                                            <v-col style="height: 10px">
                                                <span style="font-size:smaller">{{ errors[0] }}</span>
                                            </v-col>
                                            <v-col>
                                                <v-textarea label="Enter description" v-model="formData.description"
                                                    outlined class="rounded-0"></v-textarea>
                                            </v-col>
                                        </ValidationProvider>
                                    </v-col>
                                </v-row>
                                <v-row class="pa-0" style="margin-top: 0px !important">
                                    <v-col class="pa-0">
                                        <v-file-input label="Presentation" v-model="formData.presentation" outlined
                                            class="rounded-0"></v-file-input>
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
        if ("Authorized" in localStorage) {
            this.$store.dispatch('ajaxGetReport', [this.$route.params.id, this.$route.params.r_id]).then(() => {
                this.$store.dispatch('ajaxGetReportFile', [this.$route.params.id, this.$route.params.r_id]).then(() => {
                    this.$data.formData.thema = this.getReport.thema
                    this.$data.formData.start_time = new Date(new Date(this.getReport.start_time).toLocaleString())
                    this.$data.formData.end_time = new Date(new Date(this.getReport.end_time).toLocaleString())
                    this.$data.formData.description = this.getReport.description
                    this.$data.formData.presentation = this.$store.getters.getFile
                })
            })
        }
    },
    computed: {
        getReport() {
            return this.$store.getters.getReport
        },
        setReport() {
            let data = {
                'thema': this.formData.thema,
                'start_time': new Date(this.formData.start_time).toLocaleString(),
                'end_time': new Date(this.formData.end_time).toLocaleString(),
                'description': this.formData.description,
                'presentation': this.formData.presentation
            }
            this.$store.dispatch('ajaxEditReport', [data, this.$route.params.id, this.$route.params.r_id]).then(() => {
                this.$router.replace('/conferences')
            }).catch(error => {
                console.log(error.response)
                this.$refs.form.setErrors({
                    title: error.response.data.errors.thema,
                    start_time: error.response.data.errors.start_time,
                    end_time: error.response.data.errors.end_time,
                    description: error.response.data.errors.description,
                    presentation: error.response.data.errors.presentation
                });
            })
        }
    },
}
</script>