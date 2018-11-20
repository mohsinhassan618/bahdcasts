<template>
    <!-- Modal -->
    <div class="modal fade" id="createLesson" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Lesson title" v-model="Lesson.title">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Vimeo video id" v-model="Lesson.video_id">
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Episode number" v-model="Lesson.episode_number">
                    </div>

                    <div class="form-group">
                        <textarea cols="30" rows="10" class="form-control"  v-model="Lesson.description" ></textarea>
                    </div>

                    <div class="form-group">
                        <input type="checkbox" v-model="Lesson.premium"> Premium: {{ Lesson.premium }}
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" @click="updateLesson" v-if="this.editing">Save Lesson</button>
                    <button type="button" class="btn btn-primary" @click="createLesson" v-else>Create Lesson</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import Axios from 'axios'
    class Lesson {
        constructor(Lesson){
            this.title          = Lesson.title || '';
            this.video_id       = Lesson.video_id || '';
            this.episode_number = Lesson.episode_number || '';
            this.description    = Lesson.description || '';
            this.premium        = Lesson.premium || false
        }
    }
    export default {


        mounted(){
            this.$parent.$on('create_new_lesson', (seriesId) => {
                this.editing  = false;
                this.seriesId = seriesId;
                this.Lesson = new Lesson({});
                console.log(" Hello parent, we are creating the lesson");
                $('#createLesson').modal();
            });

            this.$parent.$on('edit_lesson',({lesson,seriesId}) => {
                console.log(lesson);
                this.editing        = true;
                this.Lesson         = new Lesson(lesson)
                this.lessonId       = lesson.id;
                this.seriesId = seriesId;
                $('#createLesson').modal();
            });
        },
        data (){
            return {
                Lesson: {},
                seriesId: '',
                editing: false,
                lessonId:null,
                premium:false,
            }
        },

        methods:{
            createLesson(){
                Axios.post('/admin/' + this.seriesId + '/lessons', this.Lesson
                ).then(resp => {
                    this.$parent.$emit("lesson_created",resp.data);
                    $('#createLesson').modal('hide');
                } ).catch( (error) => {
                    window.Errorhandler(error)
                    })
            },
            updateLesson: function () {
                Axios.put('/admin/' + this.seriesId + '/lessons/' + this.lessonId, this.Lesson).then( (resp) => {
                    $('#createLesson').modal('hide');
                    this.$parent.$emit('lesson_updated',resp.data);
                }).catch( (error) => {
                    window.Errorhandler(error)
                })
            },
        }
    }
</script>