<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <v-row align="center" justify="center" dense>
            <v-col cols="12" sm="8" md="4" lg="10">
                <v-card elevation="10">
                    <v-card-title>
                        <h3>{{ formData.thema }}</h3>
                    </v-card-title>
                    <v-card-text>
                        <p>Duration: {{ formData.start_time }} to {{ formData.end_time }}</p>
                        <h4>About</h4>
                        <p>{{ formData.description }}</p>
                        <a @click="onClick()">{{ formData.presentation }}</a>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-btn depressed color="warning" big
                :to="{ name: 'Edit', params: { id: getReport.conference_id, r_id: getReport.id } }">Edit</v-btn>
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
        link: []
    }),
    mounted() {
        if ("Authorized" in localStorage) {
            this.$store.dispatch('ajaxGetReport', [this.$route.params.id, this.$route.params.rep_id]).then(() => {
                this.$store.dispatch('ajaxGetReportFile', [this.$route.params.id, this.$route.params.rep_id]).then(() => {
                    this.$data.formData.thema = this.getReport.thema
                    this.$data.formData.start_time = this.getReport.start_time
                    this.$data.formData.end_time = this.getReport.end_time
                    this.$data.formData.description = this.getReport.description
                    this.$data.formData.presentation = this.getReport.presentation
                })
            })
        }
    },
    computed: {
        getReport() {
            return this.$store.getters.getReport
        },
    },
    methods: {
        onClick() {
            const url = window.URL.createObjectURL(new Blob([this.$store.getters.getFile]))
            const link = document.createElement('a')
            link.href = url
            link.setAttribute('download', 'file.json') //or any other extension
            document.body.appendChild(link)
            link.click()
        }
    }
}
</script>