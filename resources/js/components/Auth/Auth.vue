<template>
   <v-app>
      <v-main>
         <v-container class="fill-height" fluid>
            <v-row align="center" justify="center" dense>
               <v-col cols="12" sm="8" md="4" lg="4">
                  <v-card elevation="0">
                     <v-card-text>
                        <v-form>
                           <v-text-field label="Enter your email" name="email" id="email" prepend-inner-icon="mdi-mail" type="email" class="rounded-0" outlined required></v-text-field>
                           <v-text-field label="Enter your password" name="password" id="password" prepend-inner-icon="mdi-lock" type="password" min="8" class="rounded-0" outlined required></v-text-field>
                           <v-btn @click="auth()" class="rounded-0" color="#000000" x-large block dark>Login</v-btn>
                           <v-card-actions class="text--secondary">
                              <v-spacer></v-spacer>
                              Нет аккаунта? <router-link :to="{name: 'Registration'}">Регистрация</router-link>
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
         auth(){
            let data = {
               email: document.getElementById('email').value, 
               password: document.getElementById('password').value,
            }
            //console.log(data)
            this.$store.dispatch('AUTH', data).then(()=>{
               this.$store.getters.getUser
               let href = document.location.origin
               document.location.href = href
            })
         }
        }
    }
</script>