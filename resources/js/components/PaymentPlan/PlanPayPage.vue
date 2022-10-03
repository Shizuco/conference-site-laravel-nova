<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            You will be charged {{this.$route.params.plan}} Plan
          </div>
          <div class="card-body">

            <form id="payment-form">
              <div class="row">
                <div class="col-xl-4 col-lg-4">
                  <div>
                    <v-text-field label="Name" name="name" id="name" type="text" class="rounded-0" outlined required
                      v-model="name">
                    </v-text-field>
                  </div>
                </div>
                <div class="col-xl-4 col-lg-4">
                  <div>
                    <label for="">Card details</label>
                    <div id="card-element"></div>
                  </div>
                </div>
                <div class="col-xl-12 col-lg-12">
                  <hr>
                  <v-btn @click="pay()">Purchase</v-btn>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
  
<script>
const stripe = Stripe('pk_test_51LkjfTFS63sMTPizJMgEFUPNUy0IGwnI5cVMNoK9IGYnkb7OkNr72fgyarNc2TIk0SEASuXMzQPceST3KBdMoEBG008jbEuFpJ')
const elements = stripe.elements()
const cardElement = elements.create('card')

export default {
  data: () => ({
    intent: '',
    setupIntent: '',
    name: ''
  }),
  mounted() {
    this.$store.dispatch('ajaxGetIntent').then(() => {
      this.intent = this.$store.getters.getIntent.client_secret;
      this.$store.dispatch('ajaxUser').then(() => {
        this.name = this.$store.getters.getUser.name + ' ' + this.$store.getters.getUser.surname
      })
    })
    cardElement.mount('#card-element')
  },
  methods: {
    pay() {
      stripe.createPaymentMethod(
        'card', cardElement, {
        billing_details: { name: this.name }
      }
      ).then((result) => {
        let data = {
          'token': result.paymentMethod,
          'plan': this.$route.params.plan
        }
        this.$store.dispatch('ajaxSubscribe', data)
        this.$router.replace({ name: 'MainPage', params: { plan: 'success' } })
      });

    }
  },
}
</script>