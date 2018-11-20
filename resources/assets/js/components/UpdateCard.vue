<template>
    <div id="main">
        <button class="btn btn-success" v-on:click="update_card">Update Card Details</button>
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

                    swal({text: 'Please wait while we update card details ...', buttons:false});

                    Axios.post('/card/update',{
                        stripeToken:token.id,
                    }).then((resp) => {
                        swal({text: 'Success updated card details', icon:'success'}).then(() => {
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
                handler: null
            }
        },
        methods: {
            update_card:function(){
                this.handler.open({
                    name: 'Bahdcasts',
                    description: 'Bahdcasts Subscription',
                    email: this.email,
                    panelLabel: 'Update card details',
                    allowRememberMe: false
                });
            }
        }
    }
</script>