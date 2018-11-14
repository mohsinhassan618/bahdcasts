<template>
    <div class="container" style="color:black; font-weight: bold;">
        <h1 class="text-center">
            <button class="btn btn-primary"  @click="createNewLesson()">
                Create New Lesson
            </button>
        </h1>
        <ul class="list-group">
            <li class="list-group-items" v-for="lesson,index in this.lessons" >
                <p>{{ lesson.title }}</p>
                <p>
                    <button class="btn btn-primary btn-xs" @click="editLesson(lesson)"> Edit</button>
                    <button class="btn btn-danger btn-xs" @click="deleteLesson(lesson.id,index)"> Delete</button>
                </p>

            </li>
        </ul>
        <create-lesson></create-lesson>
    </div>
</template>

<script>

    import Axios from "axios"
    export default {
        props:['default_lessons','series_id'],
        mounted(){

          this.$on('lesson_created', (lesson) => {
              this.lessons.push(lesson);
              window.noty({
                  message:'Lesson Created Successfully',
                  type:'success',
              })
          });



          this.$on('lesson_updated', (lesson) => {
             let lessonIndex = this.lessons.findIndex( (L) => {
                 return lesson.id = L.id;
             });
             this.lessons.splice(lessonIndex,1,lesson)
          });
        },
        data(){
            return {
                lessons: JSON.parse(this.default_lessons)
            }
        },
        computed:{

        },
        methods:{
            createNewLesson:function (){
                this.$emit('create_new_lesson',this.series_id);
            },
            deleteLesson:function (id,index) {
                if(confirm('Are you sure you wanna delete ?')){
                    Axios.delete('/admin/' + this.series_id + '/lessons/' + id ).then( (resp) => {
                        this.lessons.splice(index,1);
                        console.log(resp)
                    } ).catch(error => {
                        window.Errorhandler(error)
                    })
                }
            },
            editLesson:function (lesson ){
                let seriesId = this.series_id;
                this.$emit('edit_lesson',{lesson,seriesId })
            }
        },
        components: {
          'create-lesson' : require('./children/CreateLesson.vue')
        },
    }
</script>