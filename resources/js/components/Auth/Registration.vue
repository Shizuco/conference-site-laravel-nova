<template>
   <v-app>
      <v-app-bar app color="black">
        <v-list-item-group>
            <v-list-item>
               <router-link :to="{name: 'MainPage'}" class="text-h5 white--text">Конференции</router-link>
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
                           <ValidationObserver tag="form" @submit.prevent="register">
                           <v-row>
                              <v-col>
                                 <ValidationProvider rules="required|alpha|min:2|max:255" v-slot="{ errors }" name="name">
                                    <span>{{ errors[0] }}</span>
                                    <v-text-field label="Введите имя" name="name" id="name" prepend-inner-icon="mdi-mail" type="name" class="rounded-0" outlined required v-model="formData.name"></v-text-field>
                                 </ValidationProvider>
                              </v-col>
                              <v-col>
                                 <ValidationProvider rules="required|alpha|min:2|max:255" v-slot="{ errors }" name="surname">
                                    <span>{{ errors[0] }}</span>
                                    <v-text-field label="Введите фамилию" name="surname" id="surname" prepend-inner-icon="mdi-lock" type="text" class="rounded-0" outlined required  v-model="formData.surname"></v-text-field>
                                 </ValidationProvider>
                                 </v-col>
                           </v-row>
                           <ValidationProvider rules="required|email" ref="provider" v-slot="{ errors }" name="email">
                              <span>{{ errors[0] }}</span>
                              <v-text-field label="Введите адрес электронной почты" name="email" id="email" prepend-inner-icon="mdi-lock" type="email" class="rounded-0" outlined required v-model="formData.email"></v-text-field>
                           </ValidationProvider>
                           <v-row>
                              <v-col>
                                 <ValidationProvider rules="required|min:8" v-slot="{ errors }" name="password">
                                    <span>{{ errors[0] }}</span>
                                    <v-text-field label="Введите пароль" name="password" id="password" prepend-inner-icon="mdi-lock" type="password" min="8" class="rounded-0" outlined required v-model="formData.password"></v-text-field>
                                 </ValidationProvider>
                              </v-col>
                              <v-col>
                                 <ValidationProvider rules="required|confirmed:password" v-slot="{ errors }" name="password_confirmation">
                                    <span>{{ errors[0] }}</span>
                                    <v-text-field label="Повторите пароль" name="password_confirmation" id="password_confirmation" prepend-inner-icon="mdi-lock" type="password" min="8" class="rounded-0" outlined required v-model="formData.password_confirmation"></v-text-field>
                                 </ValidationProvider>
                              </v-col>
                           </v-row>
                           <ValidationProvider rules="required" v-slot="{ errors }" name="role">
                              <span>{{ errors[0] }}</span>
                              <v-select label="Введите роль" name="role" id="role" prepend-inner-icon="mdi-lock" class="rounded-0" outlined required
                                    :items="role" item-text="roleName" v-model="selectedRole">
                              </v-select>
                           </ValidationProvider>
                           <ValidationProvider rules="required" v-slot="{ errors }" name="phone">
                              <span>{{ errors[0] }}</span>
                              <v-text-field label="Введите номер телефона" v-model="form.telephone" v-mask="'+38(###)-##-###-##'" name="phone" id="phone" prepend-inner-icon="mdi-lock" type="text" class="rounded-0" outlined required></v-text-field>
                           </ValidationProvider>
                           <v-row>
                              <v-col>
                                 <ValidationProvider rules="required" v-slot="{ errors }" name="date">
                                    <span>{{ errors[0] }}</span>
                                    <v-text-field type="date" id="date" class="rounded-0"  outlined required v-model="formData.date" name="date"></v-text-field>
                                 </ValidationProvider>
                              </v-col>
                              <v-col>
                                 <ValidationProvider rules="required" v-slot="{ errors }" name="country">
                                    <span>{{ errors[0] }}</span>
                                    <v-select name="country" id="country" class="rounded-0"  outlined required v-model="formData.country" :items="countries">
                                    </v-select>
                                 </ValidationProvider>
                              </v-col>
                           </v-row>
                           <br>
                           <v-btn type="submit" class="rounded-0" color="#000000" x-large block dark>Зарегестрироваться</v-btn>
                           <v-card-actions class="text--secondary">
                              <v-spacer></v-spacer>
                              Есть аккаунта? <router-link :to="{name: 'Auth'}">Авторизация</router-link>
                           </v-card-actions>
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
    export default{
      data: () => ({
         role: [
            {value: 'listener', roleName: 'Слушатель'},
            {value: 'announcer', roleName: 'Диктор'}
         ],
         countries:['Япония', 'Россия', 'Украина', 'Беларусь', 'Канада', 'Франция', 'Англия', 'США', 'Китай', 'Корея'],
         form: {
            telephone: ''
         },
         formData:{
            name: '',
            surname: '',
            email: '',
            password:'',
            password_confirmation: '',
            date: '',
            country: '',
            phone: '',
         },
         selectedRole: null,
      }),
        methods:{
         register(){
            let data = {
                  name: this.formData.name, 
                  email: this.formData.email, 
                  password: this.formData.password, 
                  password_confirmation: this.formData.password_confirmation, 
                  country: this.formData.country, 
                  birthday: this.formData.date, 
                  role: this.selectedRole, 
                  surname: this.formData.surname, 
                  phone: this.form.telephone
            }
            this.$store.dispatch('REGISTER', data).then(()=>{
                  let data ={
                     email: document.getElementById('email').value, 
                     password: document.getElementById('password').value, 
                  }
                  this.$store.dispatch('AUTH', data).then(()=>{
                     this.$router.go({name: 'MainPage'})
                  })
               }).catch(error=>{
                  this.$refs.provider.applyResult({
                  errors: error.response.data.errors.email, // array of string errors
                  valid: false, // boolean state
                  failedRules: {} // should be empty since this is a manual error.
               });
               })
        },
      }
    }
</script>

 