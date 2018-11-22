<template>

</template>

<script>

    import swal from "sweetalert";
    export default {
        created(){
            window.events.$on('notification',(payload) => {
                swal(payload.message);
            })
        },
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