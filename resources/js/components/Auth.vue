<template>
   <v-app>
      <v-app-bar app fluid color="black">
         <filter />
         <v-toolbar-title class="text-uppercase grey--text">
            <span>
               <v-btn v-if="!isAuth()" depressed color="primary" small>
                  <router-link :to="{ name: 'Registration' }" class="white--text"
                     style="text-decoration: none; color: inherit;">Sign up</router-link>
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
               <v-btn depressed small v-if="isAuth()">
                  <router-link style="text-decoration: none; color: inherit;" :to="{ name: 'UserPage' }">My Page
                  </router-link>
               </v-btn>
            </span>
            <span>
               <v-btn depressed small v-if="isAuth()">
                  <router-link style="text-decoration: none; color: inherit;" :to="{ name: 'UserFavorites' }">My
                     Favorites
                  </router-link>
                  <v-badge inline :content="getFavorites()"></v-badge>
               </v-btn>
            </span>
            <span>
               <v-btn depressed small>
                  <router-link style="text-decoration: none; color: inherit;" :to="{ name: 'AllPlans' }">Plans
                  </router-link>
               </v-btn>
            </span>
            <span>
               <v-btn depressed small>
                  <router-link style="text-decoration: none; color: inherit;" :to="{ name: 'MainPage' }">To main
                  </router-link>
               </v-btn>
            </span>
         </v-toolbar-title>
      </v-app-bar>
   </v-app>
</template>

<script>
export default {
   data: () => ({
      message2: ' '
   }),
   mounted() {
      this.$store.dispatch('ajaxGetFavorites')
      this.$store.dispatch('ajaxUser')
   },
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
         return (this.$store.getters.getUser.role == "admin") ? true : false
      },
      getFavorites() {
         if (this.$store.getters.getFavorites.length > 99) {
            return '99+'
         }
         return this.$store.getters.getFavorites.length
      }
   }
}
</script>

<style scoped>
#fav {
   color: azure;
   text-transform: lowercase;
   display: inline-block;
}

#fav:first-letter {
   text-transform: uppercase;
}
</style>