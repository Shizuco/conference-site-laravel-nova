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
                           <v-row>
                              <v-col>
                                 <ValidationProvider rules="required|alpha|min:2|max:255" v-slot="{ errors }" name="name">
                                    <v-text-field label="Введите имя" name="name" id="name" prepend-inner-icon="mdi-mail" type="name" class="rounded-0" outlined required v-model="formData.name"></v-text-field>
                                    <span>{{ errors[0] }}</span>
                                 </ValidationProvider>
                              </v-col>
                              <v-col>
                                 <ValidationProvider rules="required|alpha|min:2|max:255" v-slot="{ errors }" name="surname">
                                    <v-text-field label="Введите фамилию" name="surname" id="surname" prepend-inner-icon="mdi-lock" type="text" class="rounded-0" outlined required  v-model="formData.surname"></v-text-field>
                                    <span>{{ errors[0] }}</span>
                                 </ValidationProvider>
                                 </v-col>
                           </v-row>
                           <ValidationProvider rules="required|email" v-slot="{ errors }" name="email">
                              <v-text-field label="Введите адрес электронной почты" name="email" id="email" prepend-inner-icon="mdi-lock" type="email" class="rounded-0" outlined required v-model="formData.email"></v-text-field>
                              <span>{{ errors[0] }}</span>
                           </ValidationProvider>
                           <v-row>
                              <v-col>
                                 <ValidationProvider rules="required|min:8" v-slot="{ errors }" name="password">
                                    <v-text-field label="Введите пароль" name="password" id="password" prepend-inner-icon="mdi-lock" type="password" min="8" class="rounded-0" outlined required v-model="formData.password"></v-text-field>
                                    <span>{{ errors[0] }}</span>
                                 </ValidationProvider>
                              </v-col>
                              <v-col>
                                 <ValidationProvider rules="required|confirmed:password" v-slot="{ errors }" name="password_confirmation">
                                    <v-text-field label="Повторите пароль" name="password_confirmation" id="password_confirmation" prepend-inner-icon="mdi-lock" type="password" min="8" class="rounded-0" outlined required v-model="formData.password_confirmation"></v-text-field>
                                    <span>{{ errors[0] }}</span>
                                 </ValidationProvider>
                              </v-col>
                           </v-row>
                           <ValidationProvider rules="required" v-slot="{ errors }" name="role">
                              <v-select label="Введите роль" name="role" id="role" prepend-inner-icon="mdi-lock" class="rounded-0" outlined required
                                    :items="role" item-text="roleName" v-model="selectedRole">
                              </v-select>
                              <span>{{ errors[0] }}</span>
                           </ValidationProvider>
                           <ValidationProvider rules="required" v-slot="{ errors }" name="phone">
                              <v-text-field label="Введите номер телефона" v-model="form.telephone" v-mask="'+38(###)-##-###-##'" name="phone" id="phone" prepend-inner-icon="mdi-lock" type="text" class="rounded-0" outlined required></v-text-field>
                              <span>{{ errors[0] }}</span>
                           </ValidationProvider>
                           <v-row>
                              <v-col>
                                 <ValidationProvider rules="required" v-slot="{ errors }" name="date">
                                    <v-text-field type="date" id="date" class="rounded-0"  outlined required v-model="formData.date" name="date"></v-text-field>
                                    <span>{{ errors[0] }}</span>
                                 </ValidationProvider>
                              </v-col>
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
                           <br>
                           <v-btn @click="register()" class="rounded-0" color="#000000" x-large block dark>Зарегестрироваться</v-btn>
                           <v-card-actions class="text--secondary">
                              <v-spacer></v-spacer>
                              Есть аккаунта? <router-link :to="{name: 'Auth'}">Авторизация</router-link>
                           </v-card-actions>
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
         form: {
            telephone: ''
         },
         formData:{
            name: '',
            surname: '',
            email: '',
            password:'',
            password_confirmation: '',
            date: ''
         },
         selectedRole: null
      }),
        methods:{
         register(){
            let data = {
                  name: document.getElementById('name').value, 
                  email: document.getElementById('email').value, 
                  password: document.getElementById('password').value, 
                  password_confirmation: document.getElementById('password_confirmation').value, 
                  country: document.getElementById('country').value, 
                  birthday: document.getElementById('date').value, 
                  role: this.selectedRole, 
                  surname: document.getElementById('surname').value, 
                  phone: document.getElementById('phone').value
            }
            if(this.valid())
               this.$store.dispatch('REGISTER', data).then(()=>{
                  let data ={
                     email: document.getElementById('email').value, 
                     password: document.getElementById('password').value, 
                  }
                  this.$store.dispatch('AUTH', data).then(()=>{
                     this.$store.getters.getUser
                     this.$router.go({name: 'MainPage'})
                  })
               })
         },
         valid(){
            return document.getElementById('password').value === document.getElementById('password_confirmation').value
         }
        }
    }
</script>