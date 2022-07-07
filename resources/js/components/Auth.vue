<template>
   <div>
    <button><router-link :to="{name: 'Registration'}">Регистарция</router-link></button>
    <button><router-link :to="{name: 'Auth'}">Авторизация</router-link></button>
    <button @click="logout">Выйти</button>
   </div>
</template>

<script>
   export default{
        methods:{
         logout(){
            this.$store.dispatch('LOGOUT')
         }
        }
    }
</script>
