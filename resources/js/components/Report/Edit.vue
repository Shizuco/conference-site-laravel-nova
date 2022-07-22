<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <v-row align="center" justify="center" dense>
            <v-col cols="12" sm="8" md="4" lg="10">
                <v-card elevation="10">
                    <v-card-text>
                        <v-form>
                            <ValidationObserver tag="form" @submit.prevent="setReport">
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
                                        <ValidationProvider rules="required" v-slot="{ errors }" name="start time">
                                            <span>{{ errors[0] }}</span>
                                            <h6 style="color:black">Date of start</h6>
                                            <vc-date-picker mode="dateTime" v-model="formData.start_time" :model-config="modelConfig"/>
                                        </ValidationProvider>
                                    </v-col>
                                    <v-col>
                                        <ValidationProvider rules="required" v-slot="{ errors }" name="end time">
                                            <span>{{ errors[0] }}</span>
                                            <h6 style="color:black">Date of end</h6>
                                            <vc-date-picker mode="dateTime" v-model="formData.end_time" :model-config="modelConfig" />
                                        </ValidationProvider>
                                    </v-col>
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
                                        <v-file-input label="Presentation" v-model="formData.presentation" outlined class="rounded-0"></v-file-input>
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
        },
        modelConfig:{
            type: 'string',
            mask: 'iso',
            timeAdjust: '24:00:00',
        }
    }),
    mounted() {
        if ("Authorized" in localStorage) {
            this.$store.dispatch('ajaxGetReport', [this.$route.params.id, this.$route.params.r_id]).then(() => {
                this.$store.dispatch('ajaxGetReportFile', [this.$route.params.id, this.$route.params.r_id]).then(() => {
                    this.$data.formData.thema = this.getReport.thema
                    this.$data.formData.start_time = this.getReport.start_time
                    this.$data.formData.end_time = this.getReport.end_time
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
        setReport(){
            let data = {
                'thema': this.formData.thema,
                'start_time': this.formData.start_time,
                'end_time': this.formData.end_time,
                'description': this.formData.description,
                'presentation': this.formData.presentation
            }
            this.$store.dispatch('ajaxEditReport', [data, this.$route.params.id, this.$route.params.r_id])
        }
    },
}
</script>