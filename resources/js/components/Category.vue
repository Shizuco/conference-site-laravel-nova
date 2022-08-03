<template>
    <v-app>
        <auth style="height: 80px"></auth>
        <v-row align="center" justify="center" dense>
            <v-col cols="12" sm="8" md="4" lg="4">
                <v-alert type="success" v-if="isConferences == '0'"> Number of conferences with this category is
                    {{ conferences }}
                </v-alert>
                <v-alert type="success" v-if="isReports == '0'"> Number of reports with this category is
                    {{ reports }}
                </v-alert>
                <v-card elevation="10">
                    <v-card-text>
                        <v-form>
                            <ValidationObserver tag="form" @submit.prevent="createCategory">
                                <v-col>
                                    <ValidationProvider>
                                        <v-text-field label="Category" name="category" id="category" type="text"
                                            class="rounded-0" outlined v-model="formData.category"></v-text-field>
                                    </ValidationProvider>
                                    <ValidationProvider>
                                        <h5 style="color: grey">Parent categories</h5>
                                        <v-treeview v-model="formData.parentCategory" :items="categories"
                                            :selection-type="'independent'" selectable return-object open-all>
                                        </v-treeview>
                                    </ValidationProvider>
                                </v-col>
                                <v-row>
                                    <v-col>
                                        <v-btn type="submit" class="white--text" color="success" x-big block depressed>
                                            Create</v-btn>
                                    </v-col>
                                    <v-col>
                                        <v-btn class="white--text" color="error" @click="deleteCategory" x-big block
                                            depressed>
                                            Delete</v-btn>
                                    </v-col>
                                    <v-col>
                                        <v-btn class="white--text" @click="getConferencesNumber" color="warning" x-big
                                            block depressed>
                                            Number of conferences</v-btn>
                                    </v-col>
                                    <v-col>
                                        <v-btn @click="getReportsNumber" class="white--text" color="warning" x-big block depressed>
                                            Number of reports</v-btn>
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
            parentCategory: [],
            category: '',
        },
        categories: [],
        parent_id: '',
        conferences: '',
        reports: '',
        isReports: '',
        isConferences: ''
    }),
    mounted() {
        this.$store.dispatch('ajaxGetCategories').then(() => {
            this.$store.getters.getCategories.forEach(element => {
                this.categories.push(element)
            });
        })
    },
    methods: {
        createCategory() {
            let data = {}
            if (this.formData.parentCategory.length === 0) {
                data = {
                    name: this.formData.category
                }
            }
            else {
                this.$store.getters.getCategories.forEach(element => {
                    if (this.formData.parentCategory[0] === element) {
                        this.parent_id = element.id
                    }
                })
                data = {
                    name: this.formData.category,
                    parent: this.parent_id
                }
            }

            this.$store.dispatch('ajaxCreateCategory', data).then(() => {
                this.$router.go()
            })
        },
        deleteCategory() {
            let data = {
                id: this.formData.parentCategory[0].id
            }
            this.$store.dispatch('ajaxDeleteCategory', data).then(() => {
                this.$router.go()
            })
        },
        getConferencesNumber() {
            this.$store.dispatch('ajaxGetCategoryConferenceNumber', this.formData.parentCategory[0].id).then(() => {
                this.conferences = this.$store.getters.getConferences[0].conferences.length
                this.isConferences = '0'
                this.isReports = ''
            })
        },
        getReportsNumber() {
            this.$store.dispatch('ajaxGetCategoryReportNumber', this.formData.parentCategory[0].id).then(() => {
                this.reports = this.$store.getters.getReports[0].reports.length
                this.isReports = '0'
                this.isConferences = ''
            })
        }
    }
}
</script>
