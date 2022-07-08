<template>
  <nav class="navbar sticky-top navbar-light bg-light">
      <button class="navbar-brand" v-if="!isAuth()"><router-link :to="{name: 'Registration'}">Регистарция</router-link></button>
      <button class="navbar-brand" v-if="!isAuth()"><router-link :to="{name: 'Auth'}">Авторизация</router-link></button>
      <button class="navbar-brand" v-if="isAuth()" @click="logout">Выйти</button>
</nav>
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
            let href = document.location.origin
            document.location.href = href
         }
      }
    }
</script>
