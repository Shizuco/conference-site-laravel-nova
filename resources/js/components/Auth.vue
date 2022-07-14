<template>
   <v-app>
      <v-app-bar app flat color="black">
            <v-toolbar-title class="text-uppercase grey--text">
               <span><button v-if="!isAuth()"><router-link :to="{name: 'Registration'}" class="text-h5 white--text">Регистарция</router-link></button></span>
               <span><button v-if="!isAuth()"><router-link :to="{name: 'Auth'}" class="text-h5 white--text">Авторизация</router-link></button></span>
               <span><button class="text-h5 white--text" v-if="isAuth()" @click="logout">Выйти</button></span>
               <span><button><router-link class="text-h5 white--text" v-if="isAdmin()" :to="{name: 'CreateConference'}">Создать</router-link></button></span>
            </v-toolbar-title>
      </v-app-bar>
   </v-app>
</template>

<script>
   export default{
      methods:{
         isAuth(){
            if("Authorized" in localStorage){
               return true
            }
            else{
               return false
            }
         },
         logout(){
            console.log(localStorage.getItem('Authorized'))
            this.$store.dispatch('LOGOUT')
            this.$router.go()
         },
         isAdmin(){
                if(this.$store.getters.getUser.role == "admin"){
                    return true
                }
                else{
                    return false
                }
         },
      }
    }
</script>