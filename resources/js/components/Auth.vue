<template>
   <div>
      <button v-if="!isAuth()"><router-link :to="{name: 'Registration'}">Регистарция</router-link></button>
      <button v-if="!isAuth()"><router-link :to="{name: 'Auth'}">Авторизация</router-link></button>
      <button v-if="isAuth()" @click="logout">Выйти</button>
   </div>
</template>

<script>
   export default{
        methods:{
         isAuth(){
            if("Authorized" in localStorage){
               let href = document.location.origin
               document.location.href = href
               return true
            }
               else 
                  return false
         },
         logout(){
            console.log(localStorage.getItem('Authorized'))
            this.$store.dispatch('LOGOUT')
         }
        }
    }
</script>
