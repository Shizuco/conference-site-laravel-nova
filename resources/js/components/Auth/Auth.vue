<template>
   <div>
      <input type="email" name="email" id="email" placeholder="email">
      <input type="password" name="password" id="password" placeholder="password">
      <button @click="auth()">Войти</button>
   </div>
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
               this.$router.replace('/conferences')
            })
         }
        }
    }
</script>