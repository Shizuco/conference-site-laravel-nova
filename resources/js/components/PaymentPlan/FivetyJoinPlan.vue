<template>
    <div>
      <stripe-checkout ref="checkoutRef" mode="subscription" :pk="publishableKey" :line-items="lineItems"
        :success-url="successURL" :cancel-url="cancelURL" @loading="v => loading = v" />
      <v-btn @click="submit" depressed color="primary">Choose
      </v-btn>
    </div>
  </template>
    
  <script>
  import { StripeCheckout } from '@vue-stripe/vue-stripe';
  export default {
    components: {
      StripeCheckout,
    },
    data() {
      this.publishableKey = 'pk_test_51LkjfTFS63sMTPizJMgEFUPNUy0IGwnI5cVMNoK9IGYnkb7OkNr72fgyarNc2TIk0SEASuXMzQPceST3KBdMoEBG008jbEuFpJ';
      return {
        loading: false,
        lineItems: [
          {
            price: 'price_1LkrNyFS63sMTPizlB8Psf6w', // The id of the recurring price you created in your Stripe dashboard
            quantity: 1,
          }
        ],
        successURL: 'http://127.0.0.1:8000/#/conferences',
        cancelURL: 'http://127.0.0.1:8000/#/plans',
      };
    },
    methods: {
      submit() {
        // You will be redirected to Stripe's secure checkout page
        this.$refs.checkoutRef.redirectToCheckout();
      },
    },
  };
  </script>