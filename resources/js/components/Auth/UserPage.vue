<template>
    <v-app>
        <v-app-bar app color="black">
            <v-list-item-group>
                <v-list-item>
                    <router-link :to="{ name: 'MainPage' }" class="text-h5 white--text">Conferences</router-link>
                </v-list-item>
            </v-list-item-group>
        </v-app-bar>
        <v-main>
            <v-container class="fill-height" fluid>
                <v-row align="center" justify="center" dense>
                    <v-col cols="12" sm="8" md="4" lg="8">
                        <v-card elevation="0">
                            <v-card-text>
                                <v-form>
                                    <ValidationObserver tag="form" ref="form" @submit.prevent="register">
                                        <v-row>
                                            <v-col>
                                                <ValidationProvider rules="required|alpha|min:2|max:255"
                                                    v-slot="{ errors }" name="name">
                                                    <span>{{ errors[0] }}</span>
                                                    <v-text-field label="Name" name="name" id="name"
                                                        prepend-inner-icon="mdi-mail" type="name" class="rounded-0"
                                                        outlined v-model="formData.name">
                                                    </v-text-field>
                                                </ValidationProvider>
                                            </v-col>
                                            <v-col>
                                                <ValidationProvider rules="required|alpha|min:2|max:255"
                                                    v-slot="{ errors }" name="surname">
                                                    <span>{{ errors[0] }}</span>
                                                    <v-text-field label="Surname" name="surname" id="surname"
                                                        prepend-inner-icon="mdi-mail" type="text" class="rounded-0"
                                                        outlined v-model="formData.surname"></v-text-field>
                                                </ValidationProvider>
                                            </v-col>
                                        </v-row>
                                        <ValidationProvider rules="required|email" v-slot="{ errors }" name="email">
                                            <span>{{ errors[0] }}</span>
                                            <v-text-field label="Email" name="email" id="email" vid="email"
                                                prepend-inner-icon="mdi-email" type="email" class="rounded-0" outlined
                                                v-model="formData.email">
                                            </v-text-field>
                                        </ValidationProvider>
                                        <v-row>
                                            <v-col>
                                                <ValidationProvider rules="required|min:8" v-slot="{ errors }"
                                                    name="password">
                                                    <span>{{ errors[0] }}</span>
                                                    <v-text-field label="New password" name="password" id="password"
                                                        prepend-inner-icon="mdi-lock" type="password" min="8"
                                                        class="rounded-0" outlined v-model="formData.password">
                                                    </v-text-field>
                                                </ValidationProvider>
                                            </v-col>
                                            <v-col>
                                                <ValidationProvider rules="required|confirmed:password"
                                                    v-slot="{ errors }" name="password_confirmation">
                                                    <span>{{ errors[0] }}</span>
                                                    <v-text-field label="New password again" name="password_confirmation"
                                                        id="password_confirmation" prepend-inner-icon="mdi-lock"
                                                        type="password" min="8" class="rounded-0" outlined
                                                        v-model="formData.password_confirmation">
                                                    </v-text-field>
                                                </ValidationProvider>
                                            </v-col>
                                        </v-row>
                                        <ValidationProvider rules="required" v-slot="{ errors }" name="phone">
                                            <span>{{ errors[0] }}</span>
                                            <v-text-field label="Phone" v-model="form.telephone"
                                                v-mask="'+38(###)-##-###-##'" name="phone" id="phone"
                                                prepend-inner-icon="mdi-phone" type="text" class="rounded-0" outlined>
                                            </v-text-field>
                                        </ValidationProvider>
                                        <v-row>
                                            <v-col>
                                                <ValidationProvider rules="required" v-slot="{ errors }" name="date">
                                                    <span>{{ errors[0] }}</span>
                                                    <v-text-field type="date" id="date" class="rounded-0" outlined
                                                        v-model="formData.date" name="date"></v-text-field>
                                                </ValidationProvider>
                                            </v-col>
                                            <v-col>
                                                <ValidationProvider rules="required" v-slot="{ errors }" name="country">
                                                    <span>{{ errors[0] }}</span>
                                                    <v-select name="country" id="country" class="rounded-0" outlined
                                                        v-model="formData.country" :items="countries">
                                                    </v-select>
                                                </ValidationProvider>
                                            </v-col>
                                        </v-row>
                                        <br>
                                        <v-btn type="submit" class="rounded-0" color="#000000" x-large block dark>
                                            update</v-btn>
                                    </ValidationObserver>
                                </v-form>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>

<script>
export default {
    data: () => ({
        countries: ['Japan', 'Russia', 'Ukraine', 'Belarus', 'Canada', 'France', 'England', 'USA', 'China', 'Korea'],
        form: {
            telephone: ''
        },
        formData: {
            name: '',
            surname: '',
            email: '',
            password: '',
            password_confirmation: '',
            date: '',
            country: '',
        },
    }),
    mounted() {
        if ("Authorized" in localStorage) {
            this.$store.dispatch('ajaxUser').then(()=>{
                this.formData.name = this.getUser().name
                this.formData.surname = this.getUser().surname
                this.formData.email = this.getUser().email
                this.formData.date = this.getUser().birthday
                this.formData.country = this.getUser().country
                this.form.telephone = this.getUser().phone
            })
        }
    },
    methods: {
        getUser() {
            return this.$store.getters.getUser
        },
        register() {
            let data = {
                name: this.formData.name,
                email: this.formData.email,
                password: this.formData.password,
                password_confirmation: this.formData.password_confirmation,
                country: this.formData.country,
                birthday: this.formData.date,
                surname: this.formData.surname,
                phone: this.form.telephone
            }
            this.$store.dispatch('changeUserData', data).catch(error => {
            console.log(error.response)
                this.$refs.form.setErrors({
                    email: error.response.data.errors.email,
                    name: error.response.data.errors.name,
                    surname: error.response.data.errors.surname,
                    password: error.response.data.errors.password,
                    password_confimation: error.response.data.errors.password_confimation,
                    country: error.response.data.errors.country,
                    date: error.response.data.errors.birthday,
                    phone: error.response.data.errors.phone
                });
            })
        },
    }
}
</script>

 