<template>
    <div>
        <Master>
            <Question :question="question" ></Question>
        </Master>
    </div>
</template>

<script>
import Master from "../Layouts/Master.vue";
import { Link } from '@inertiajs/inertia-vue3';
import Swal from 'sweetalert2';
import Question from "../Components/QuestionDetail.vue"
    export default {
        name: "QuestionDetail",
        components: {
            Master,Link,Question
        },
        data () {
            return {
                question: '',
                imgPath: '/uploads/users/',
            }
        },
        created(){
            this.question = this.$page.props.question;
        }
    }
</script>

<style  scoped>

</style>
