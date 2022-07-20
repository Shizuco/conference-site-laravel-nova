<template>
   <v-app>
      <v-app-bar app flat color="black">
         <v-toolbar-title class="text-uppercase grey--text">
            <span>
               <v-btn v-if="!isAuth()" depressed color="primary" small>
                  <router-link :to="{ name: 'Registration' }" class="white--text"
                     style="text-decoration: none; color: inherit;">Sign in</router-link>
               </v-btn>
            </span>
            <span>
               <v-btn v-if="!isAuth()" depressed color="primary" small>
                  <router-link :to="{ name: 'Auth' }" class="white--text"
                     style="text-decoration: none; color: inherit;">Log in</router-link>
               </v-btn>
            </span>
            <span>
               <v-btn class="white--text" depressed color="primary" small v-if="isAuth()" @click="logout">Log out
               </v-btn>
            </span>
            <span>
               <v-btn depressed color="primary" small v-if="isAdmin()">
                  <router-link class="white--text" style="text-decoration: none; color: inherit;"
                     :to="{ name: 'CreateConference' }">Create
                  </router-link>
               </v-btn>
            </span>
         </v-toolbar-title>
      </v-app-bar>
   </v-app>
</template>

<script>
export default {
   methods: {
      isAuth() {
         return ("Authorized" in localStorage) ? true : false
      },
      logout() {
         console.log(localStorage.getItem('Authorized'))
         this.$store.dispatch('logout')
         this.$router.go()
      },
      isAdmin() {
         return (this.$store.getters.getUser.role == "admin")? true : false
      },
   }
}
</script>
