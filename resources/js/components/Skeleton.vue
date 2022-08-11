<template>
    <v-row class="justify-content-center" v-if="notificationIsShow">
        <v-col class="col-md-8">
            <v-skeleton-loader type="image">
            </v-skeleton-loader>
            <br>
            <v-skeleton-loader type="image">
            </v-skeleton-loader>
            <br>
            <v-skeleton-loader type="image">
            </v-skeleton-loader>
            <br>
            <v-skeleton-loader type="image">
            </v-skeleton-loader>
            <br>
            <v-skeleton-loader type="image">
            </v-skeleton-loader>
        </v-col>
    </v-row>
    <v-row class="justify-content-center" v-else>
        <v-col class="col-md-8">
            <h1>Sorry, I coundn`t find such notations</h1>
        </v-col>
    </v-row>
</template>
<script>

export default {
    data () {
      return {
        notificationIsShow: true
      }
    },


    methods:{
      hideNotification () {
        setTimeout(() => {
          this.notificationIsShow = false
        }, 3000);
      },
    },

    mounted () {
      this.hideNotification()
    }
}
</script>