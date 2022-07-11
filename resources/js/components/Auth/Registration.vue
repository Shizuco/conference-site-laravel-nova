<template>
   <v-app>
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
                           <v-text-field label="Введите роль" name="role" id="role" prepend-inner-icon="mdi-lock" type="text" class="rounded-0" outlined required></v-text-field>
                           <v-text-field label="Введите номер телефона" name="phone" id="phone" prepend-inner-icon="mdi-lock" type="text" class="rounded-0" outlined required></v-text-field>
                           <v-row>
                              <v-col><v-text-field label="Введите дату рождения" name="birthday" id="birthday" prepend-inner-icon="mdi-lock" type="text" class="rounded-0" outlined required></v-text-field></v-col>
                              <v-col><v-text-field label="Введите страну местопнаходжения" name="country" id="country" prepend-inner-icon="mdi-lock" type="text" class="rounded-0" outlined required></v-text-field></v-col>
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
    export default{
        methods:{
         register(){
            let data = {
                  name: document.getElementById('name').value, 
                  email: document.getElementById('email').value, 
                  password: document.getElementById('password').value, 
                  password_confirmation: document.getElementById('password_confirmation').value, 
                  country: document.getElementById('country').value, 
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