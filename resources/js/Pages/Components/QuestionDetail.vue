<template>
    <div>
        <div class="border-0 card" style="border-radius: 15px">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="d-flex">
                            <img :src="imgPath+question.user.image" class="rounded-circle" alt="" srcset="" style="width: 40px ; height: 40px;">
                            <div class="ms-2">
                                <h6 class="mb-0">{{ question.user.name }}</h6>
                                <span class="text-black-50">{{ question.time }}</span>
                            </div>
                        </div>
                        <div class="me-3 d-flex align-items-center">
                             <i v-show="!question.isSaved" @click="saveQuestion(question.id)" class="py-2 text-center far fa-heart rounded-circle bg-light text-secondary" style="width: 30px; height: 30px"></i>
                            <i v-show="question.isSaved" @click="unSaveQuestion(question.id)" class="py-2 text-center fas fa-heart rounded-circle bg-light text-danger" style="width: 30px; height: 30px"></i>
                        </div>
                    </div>
                    <div class="my-4">
                        <div class="mb-2 d-flex align-items-center">
                            <div class="mb-0 h6 me-2">
                                <span v-if="question.is_solved == 'true'" class="py-0 btn btn-light text-success btn-sm">Is Solved !</span>
                                <span v-else class="py-0 btn btn-light text-danger btn-sm">Need Solved ?</span>
                            </div>
                            <h4 class="mb-0">{{ question.title }}</h4>
                        </div>
                        <p class="text-black-50">{{ question.description }}</p>
                        <div class="">
                            <div v-for="tag in question.tag" :key="tag.id" class="mb-1 badge bg-light text-secondary rounded-pill fw-normal me-1">{{tag.name}}</div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex">
                        <div class="me-3 d-flex align-items-center">
                            <i v-show="!question.isLike" @click="like(question.id)" class="py-2 text-center far fa-thumbs-up rounded-circle bg-light text-primary like" style="width: 30px; height: 30px"></i>
                            <i v-show="question.isLike" @click="like(question.id)" class="py-2 text-center fas fa-thumbs-up rounded-circle bg-light text-primary like" style="width: 30px; height: 30px"></i>
                            <span class="mb-0 ms-1 text-dark">{{ question.likeCount }}</span>
                        </div>
                        <div class="me-3 d-flex align-items-center">
                            <i class="py-2 text-center far fa-comment rounded-circle bg-light text-success" style="width: 30px; height: 30px"></i>
                            <span class="mb-0 ms-1 text-dark">{{ question.comment.length }}</span>
                        </div>
                        <div class="me-3 d-flex align-items-center">
                            <i class="py-2 text-center far rounded-circle bg-light fa-eye text-secondary" style="width: 30px; height: 30px"></i>
                            <span class="mb-0 ms-1 text-dark">{{ question.viewCount }}</span>
                        </div>
                    </div>
                    <hr>
                    <!-- comment  -->
                    <div class="">
                        <div class="mb-3 d-flex">
                            <img :src="imgPath+$page.props.auth_user.image" class="rounded-circle" alt="" srcset="" style="width: 40px ; height: 40px;">
                            <form @submit.prevent="createComment" class="d-flex w-100">
                                <input v-model="comment" type="text" class="form-control rounded-pill ms-2" placeholder="enter your question ......">
                                <button  class="ms-2 btn btn-secondary rounded-circle"><i class="fas fa-paper-plane"></i></button>
                            </form>
                        </div>
                        <!-- comment box  -->
                        <div v-for="(comment,index) in question.comment" :key="comment.id" class="mb-3 d-flex">
                            <div class="d-flex">
                                <img :src="imgPath+comment.user.image" class="rounded-circle" alt="" srcset="" style="width: 40px ; height: 40px;">
                            </div>
                            <div class="p-3 ms-2 bg-light w-100" style="border-radius: 15px">
                                <div class="mb-2 d-flex justify-content-between align-items-center">
                                    <div class=" d-flex align-items-center">
                                        <h6 class="mb-0">{{ comment.user.name }}</h6>
                                        <span class="text-black-50 ms-2">{{ comment.time }}</span>
                                    </div>
                                    <button v-if="this.$page.props.auth_user.id == comment.user.id" @click="deleteComment(comment.id,index)" class="m-0 bg-white btn btn-sm rounded-circle"><i class="fas fa-times"></i></button>
                                </div>
                                <p>{{ comment.comment }}</p>
                            </div>
                        </div>
                        <!-- end comment box  -->

                    </div>
                </div>
            </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import Swal from 'sweetalert2';
    export default {
        name: "QuestionDetail",
        components: {
            Link
        },
        props: {
            question: {
                type: Object,
            },
        },
        data () {
            return {
                comment: '',
                imgPath: '/uploads/users/',
            }
        },
        methods: {
            createComment () {
                const data = new FormData;
                data.append('comment',this.comment);
                data.append('questionId',this.question.id);
                axios.post('/question/comment',data).then((response) => {
                        if(response.data.success){
                            this.question.comment.push(response.data.comment);
                            this.comment = '';
                        }
                    })
            },

            deleteComment(id,index){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to delete comment!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.get(this.route('question.deleteComment',id)).then((response) => {
                        if(response.data.success){
                            this.question.comment.splice(index,1);
                            }
                        });
                        Swal.fire(
                            'Deleted!',
                            'Your Comment has been deleted.',
                            'success'
                        );
                    }
                })
            },
            like (id) {
                if(this.question.isLike != true){
                    this.question.isLike = true;
                    this.question.likeCount++;
                     axios.get(`/question/like/${id}`).then((response) => {
                        if(response.data.success){
                            console.log('like');
                        }
                    })
                }else{
                    this.question.isLike = false;
                    this.question.likeCount--;
                     axios.get(`/question/disLike/${id}`).then((response) => {
                        if(response.data.success){
                            console.log('dislike');
                        }
                    })
                }
            },
            saveQuestion(id){
                var data = new FormData;
                data.append('id',id);
                axios.post(this.route('question.save'),data).then((response) => {
                    if(response.data.success){
                        this.question.isSaved = true;
                         Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Question is saved',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                })
            },

            unSaveQuestion(id){
                var data = new FormData;
                data.append('id',id);
                axios.post(this.route('question.unSave'),data).then((response) => {
                    if(response.data.success){
                        this.question.isSaved = false;
                    }
                })
            }
        },
    }
</script>

<style  scoped>
    .like:hover,
    .like:focus {
        transform: scale(1.2) rotate(-30deg);
        transition: .3s linear;
    }
</style>
