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
                              <v-col><v-text-field label="Введите имя" name="name" id="name" prepend-inner-icon="mdi-mail" type="name" class="rounded-0" min="2" max="255" outlined required></v-text-field></v-col>
                              <v-col><v-text-field label="Введите фамилию" name="surname" id="surname" prepend-inner-icon="mdi-lock" type="text" min="2" max="255" class="rounded-0" outlined required></v-text-field></v-col>
                           </v-row>
                           <v-text-field label="Введите адрес электронной почты" name="email" id="email" prepend-inner-icon="mdi-lock" type="email" class="rounded-0" outlined required></v-text-field>
                           <v-row>
                              <v-col><v-text-field label="Введите пароль" name="password" id="password" prepend-inner-icon="mdi-lock" type="password" min="8" class="rounded-0" outlined required></v-text-field></v-col>
                              <v-col><v-text-field label="Повторите пароль" name="password_confirmation" id="password_confirmation" prepend-inner-icon="mdi-lock" type="password" min="8" class="rounded-0" outlined required></v-text-field></v-col>
                           </v-row>
                           <v-select label="Введите роль" name="role" id="role" prepend-inner-icon="mdi-lock" class="rounded-0" outlined required
                                 :items="role" item-text="roleName" v-model="selectedRole">
                           </v-select>
                           <v-text-field label="Введите номер телефона" name="phone" id="phone" prepend-inner-icon="mdi-lock" type="text" class="rounded-0" outlined required></v-text-field>
                           <v-row>
                              <v-col><v-text-field label="Введите дату рождения" name="birthday" id="birthday" prepend-inner-icon="mdi-lock" type="text" class="rounded-0" outlined required></v-text-field></v-col>
                              <v-col>
                                 <v-select label="Введите страну" name="country" id="country" prepend-inner-icon="mdi-lock" class="rounded-0" outlined required
                                 :items="items" item-text="countryName" v-model="selectedCountry">
                                 </v-select>
                              </v-col>
                           </v-row>
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
import { listenerCount } from 'process'

    export default{
      data: () => ({
         items: [
            {value: '1', countryName: 'Япония'},
            {value: '2', countryName: 'США'},
            {value: '3', countryName: 'Украина'},
            {value: '4', countryName: 'Россия'},
            {value: '5', countryName: 'Беларусь'},
            {value: '6', countryName: 'Польша'},
            {value: '7', countryName: 'Чехия'},
            {value: '8', countryName: 'Черногорие'},
            {value: '9', countryName: 'Канада'},
            {value: '10', countryName: 'Китай'},
         ],
         role: [
            {value: 'listener', roleName: 'Слушатель'},
            {value: 'announcer', roleName: 'Диктор'}
         ],
         selectedCountry: null,
         selectedRole: null
      }),
        methods:{
         register(){
            let data = {
                  name: document.getElementById('name').value, 
                  email: document.getElementById('email').value, 
                  password: document.getElementById('password').value, 
                  password_confirmation: document.getElementById('password_confirmation').value, 
                  country: this.selectedCountry, 
                  birthday: document.getElementById('birthday').value, 
                  role: document.getElementById('role').value, 
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
                     let href = document.location.origin
                     document.location.href = href
                  })
               })
         },
         valid(){
            return document.getElementById('password').value === document.getElementById('password_confirmation').value
         }
        }
    }
</script>