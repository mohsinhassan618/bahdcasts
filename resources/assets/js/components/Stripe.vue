<template>
    <div class="text-center">
    <button class="btn btn-success" v-on:click="subscribe('monthly')">Subscribe to $9.99 Monthly</button>
    <button class="btn btn-info" v-on:click="subscribe('yearly')">Subscribe to $99.99 Yearly</button>
    </div>
</template>

<script>
    import Axios from 'axios'
    import swal from 'sweetalert'
    export default {
        props:['email'],
        mounted(){
             this.handler = StripeCheckout.configure({
                key: 'pk_test_hE6MWFKsCY1PrcxcKloAve7f',
                image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
                locale: 'auto',
                token: function(token) {
                    // You can access the token ID with `token.id`.
                    // Get the token ID to your server-side code for use.

                    swal({text: 'Please wait while we subscribe you to a plan...', buttons:false});

                    Axios.post('/subscribe',{
                        stripeToken:token.id,
                        plan: window.stripePlan
                    }).then((resp) => {
                        swal({text: 'Success Subscribed', icon:'success'}).then(() => {
                            window.location = '';
                        });
                        console.log(resp)
                    });
                    console.log(token.id)
                }
            });
        },
        data(){
          return {
              plan: '',
              amount: 0,
              handler: null
          }
        },
        methods: {
            subscribe:function(plan){

                if(plan== 'monthly'){
                    window.stripePlan  = 'monthly-plan';
                    this.amount = 999
                } else {
                    window.stripePlan = 'yearly-plan';
                    this.amount = 9999
                }

                this.handler.open({
                    name: 'Bahdcasts',
                    description: 'Bahdcasts Subscription',
                    amount: this.amount,
                    email: this.email
                });

            }
        }
    }
</script>