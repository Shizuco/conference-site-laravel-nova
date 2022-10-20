<template>
    <v-app>
        <v-row class="shrink">
            <v-col>
                <auth style="height: 80px; width: 800px"></auth>
            </v-col>
            <v-col>
                <v-card>
                    <v-card-title>
                        <h2>User Info</h2>
                    </v-card-title>
                    <v-card-text>
                        <v-col>
                            <h5>Name: {{formData.name}}</h5>
                            <h5>Surname: {{formData.surname}}</h5>
                            <h5>Email: {{formData.email}}</h5>
                            <h5>Birthday: {{formData.date}}</h5>
                            <h5>Country: {{formData.country}}</h5>
                            <h5>Telephone: {{formData.telephone}}</h5>
                            <h5 v-if="formData.currentPlan != 'Senior'">Left Credits: {{formData.left_joins}}</h5>
                            <h5>Current Plan: {{formData.currentPlan}}</h5>
                        </v-col>
                    </v-card-text>
                    <v-card-actions>
                        <v-btn depressed small color="primary">
                            <router-link style="text-decoration: none; color: inherit;" :to="{ name: 'UserPage' }">
                                Update Info
                            </router-link>
                        </v-btn>
                        <v-btn depressed small color="success" v-if="formData.currentPlan == 'Basic'">
                            <router-link style="text-decoration: none; color: inherit;" :to="{ name: 'AllPlans' }">
                                Update Plan
                            </router-link>
                        </v-btn>
                        <v-btn depressed small color="error" v-else @click="cancelPlan">
                            Cancel Plan 
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-app>
</template>

<script>
export default {
    data: () => ({
        formData: {
            name: '',
            surname: '',
            email: '',
            date: '',
            country: '',
            telephone: '',
            currentPlan: '',
            left_joins: ''
        },
    }),
    mounted() {
        if ("Authorized" in localStorage) {
            this.$store.dispatch('ajaxUser').then(() => {
                this.formData.name = this.getUser().name
                this.formData.surname = this.getUser().surname
                this.formData.email = this.getUser().email
                this.formData.date = this.date(this.getUser().birthday)
                this.formData.country = this.getUser().country
                this.formData.telephone = this.getUser().phone
                this.formData.left_joins = this.getUser().left_joins
                this.$store.dispatch('ajaxGetCurrentPlan').then(() => {
                    this.formData.currentPlan = this.getCurrentPlan()[0].name
                })
            })
        }
    },
    methods: {
        getUser() {
            return this.$store.getters.getUser
        },
        getCurrentPlan() {
            return this.$store.getters.getCurrentPlan
        },
        date(date) {
            return date.split('T')[0]
        },
        cancelPlan(){
            this.$store.dispatch('ajaxCancelPlan').then(()=>{
                this.$router.replace('/conferences')
            })
        }
    }
}
</script>

 