<template>
   <v-app>
      <v-app-bar app color="black">
         <v-list-item-group>
            <v-list-item>
               <router-link :to="{ name: 'MainPage' }" class="text-h5 white--text">Conferences</router-link>
            </v-list-item>
         </v-list-item-group>
      </v-app-bar>
      <v-main>
         <v-container class="fill-height" fluid>
            <v-row align="center" justify="center" dense>
               <v-col cols="12" sm="8" md="4" lg="4">
                  <v-card elevation="0">
                     <v-card-text>
                        <v-form>
                           <v-row class="pa-0" style="margin-top: 0px !important">
                              <v-col class="pa-0">
                                 <ValidationProvider rules="required|email" ref="provider" v-slot="{ errors }"
                                    name="email">
                                    <v-col style="height: 10px">
                                       <span style="font-size:smaller"> {{ errors[0] }}</span>
                                    </v-col>
                                    <v-col>
                                       <v-text-field label="Email" name="email" id="email" prepend-inner-icon="mdi-mail"
                                          type="email" class="rounded-0" outlined required v-model="formData.email">
                                       </v-text-field>
                                    </v-col>

                                 </ValidationProvider>
                              </v-col>
                           </v-row>
                           <v-row class="pa-0" style="margin-top: 0px !important">
                              <v-col class="pa-0">
                                 <ValidationProvider rules="required|min:8" v-slot="{ errors }" name="password">
                                    <v-col style="height: 10px">
                                       <span style="font-size:smaller">{{ errors[0] }}</span>
                                    </v-col>
                                    <v-col>
                                       <v-text-field label="Password" name="password" id="password"
                                          prepend-inner-icon="mdi-lock" type="password" min="8" class="rounded-0"
                                          outlined required v-model="formData.password">
                                       </v-text-field>
                                    </v-col>
                                 </ValidationProvider>
                              </v-col>
                           </v-row>
                           <v-btn @click="auth()" class="rounded-0" color="#000000" x-large block dark>Log in</v-btn>
                           <v-card-actions class="text--secondary">
                              <v-spacer></v-spacer>
                              No account?<router-link :to="{ name: 'Registration' }">Registration</router-link>
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
export default {
   data: () => ({
      formData: {
         email: '',
         password: ''
      }
   }),
   methods: {
      auth() {
         let data = {
            email: this.formData.email,
            password: this.formData.password,
         }
         this.$store.dispatch('auth', data).then(() => {
            this.$router.go()
         }).catch(error => {
            this.$refs.form.setErrors({
               email: error.response.data.errors.email,
               password: error.response.data.errors.password,
            });
         })
      },
   }
}
</script>