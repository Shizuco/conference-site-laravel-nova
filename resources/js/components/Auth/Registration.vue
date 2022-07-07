<template>
   <div>
      <input type="text" name="name" id="name" placeholder="name" required>
      <input type="text" name="surname" id="surname" placeholder="surname" required>
      <input type="email" name="email" id="email" placeholder="email" required>
      <input type="password" name="password" id="password" placeholder="password" required>
      <input type="password" name="password_confirmation" id="password_confirmation" placeholder="password_confirmation" required>
      <input type="role" name="role" id="role" placeholder="role" required>
      <input type="phone" name="phone" id="phone" placeholder="phone" required>
      <input type="text" name="birthday" id="birthday" placeholder="birthday" required>
      <input type="text" name="country" id="country" placeholder="country" required>
      <button @click="register()">Зарегестрироваться</button>
   </div>
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
            console.log(data)
            if(this.valid())
               this.$store.dispatch('REGISTER', data).then(()=>{
                  this.$store.getters.getUser
                  let href = document.location.origin
                  document.location.href = href
               })
         },
         valid(){
            return document.getElementById('password').value === document.getElementById('password_confirmation').value
         }
        }
    }
</script>