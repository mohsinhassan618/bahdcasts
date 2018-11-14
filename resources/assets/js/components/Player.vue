<template>
    <div>
        <div :data-vimeo-id="lesson.video_id"  id="handstick" v-if="lesson"></div>
    </div>
</template>

<script>
    import Axios from 'axios';
    import Player from '@vimeo/player';
    import swal from 'sweetalert';

    export default {
        props:['default_lesson','next_lesson_url'],

        data(){
          return  {
              lesson : JSON.parse(this.default_lesson)
          }
        },
        methods : {
            displayVideoEndedAlert: function (){

                if(this.next_lesson_url) {
                    swal('Yaaay ! You completed this Lesson !')
                        .then((value) => {
                            window.location = this.next_lesson_url;
                        })

                } else {
                    swal('Yaaay ! You completed this Series !')
                }
            },

            completeLesson: function(){

                Axios.post(`/series/complete-lesson/${this.lesson.id}`,[]).then( (resp) => {
                    this.displayVideoEndedAlert();
                });
            }

        },

        mounted(){
            const player = new Player('handstick');
            player.on('ended', () => {
                this.completeLesson()
            });
        }
    }
</script>