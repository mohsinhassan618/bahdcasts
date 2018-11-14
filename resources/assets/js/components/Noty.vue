<template>
    <div class="alert  alert-noty" :class="type" v-show="notification">
       <p class="text-center" v-if="notification != null">{{ notification.message }}</p>
    </div>

</template>

<script>
    export default {
        created(){
            window.events.$on('notification',(payload) => {
               this.notification = payload;
                setTimeout(() => {
                    this.notification = null
                },2500)
            })
        },
        computed:{
          type:function(){
              return (this.notification != null) ? 'alert-' + this.notification.type : '';
          }
        },
        data(){
            return {
                notification: null
            }
        }
    }
</script>

<style>
    .alert-noty{
        position: fixed;
        bottom: 40px;
        right: 20px;
        left:20px;
        z-index: 1;
    }
</style>