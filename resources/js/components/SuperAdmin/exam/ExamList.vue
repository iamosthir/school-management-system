<template lang="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>All Exam lists</h4>
                    <router-link class="btn btn-sm btn-primary" :to="{name: 'admin.add-exam'}">Add exam</router-link>
                </div>
                <div class="card-body">
                    <div class="row" v-if="isLoading">
                        <div class="col-md-12 mb-3" v-for="n in 10" :key="n">
                            <skeleton width="100%" height="40px"></skeleton>
                        </div>
                    </div>
                    <div class="row" v-else>
                        
                        <div class="col-md-12 mb-4">
                            <form class="row" @submit.prevent="filter">
                                <div class="col-md-2 col-3">
                                    <label for="">Category</label>
                                    <select class="form-control" v-model="selectedCat">
                                        <option value="">All Categories</option>
                                        <option v-for="(cat,i) in cats" :value="cat.id">{{ cat.name }}</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label for="">Select School</label>
                                    <multiselect  v-model="selectedSchool" :options="schools" 
                                    @input="getClassList" :preserve-search="true" placeholder="Select section" label="name" track-by="id"></multiselect>
                                </div>

                                <div class="col-md-2 mb-4">
                                    <label for="">Select Class</label>
                                    <multiselect v-model="selectedClass" :options="classes" 
                                    @input="getSectionList" :preserve-search="true" placeholder="Select section" label="name" track-by="id" :loading="isLoadingClass"></multiselect>
                                </div>

                                <div class="col-md-2 mb-4">
                                    <label for="">Select Section</label>
                                    <multiselect v-model="selectedSection" :options="sections" 
                                        :preserve-search="true" placeholder="Select section" label="name" track-by="id" :loading="isLoadingSection"></multiselect>
                                </div>
                                <div class="col-md-2 mb-4">
                                    <label for="">Status</label>
                                    <select class="form-control" v-model="filterStatus">
                                        <option value="">All</option>
                                        <option value="draft">Draft</option>
                                        <option value="published">Publish</option>
                                        <option value="unpublished">Unpublish</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mb-4">
                                    <button class="btn btn-primary">Filter <i class="fas fa-search"></i></button>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-12 mb-4 text-right">
                            <button v-if="form.selectedExams.length > 0" @click="editMultiExam" class="btn btn-success">Edit <i class="fas fa-edit"></i></button>
                        </div>

                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th></th>
                                            <th>Exam ID</th>
                                            <th>Exam Title</th>
                                            <th>Category</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>School Info</th>
                                            <th>Created By</th>
                                            <th>Exam created at</th>
                                            <th>Status</th>
                                            <th>Total Questions</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(exam,i) in exams" :key="i" class="text-center" :for="`exam${exam.id}`">
                                            <td><input :id="`exam${exam.id}`" type="checkbox" v-model="form.selectedExams" :value="exam.id"></td>
                                            <td><b>#{{ exam.id }}</b></td>
                                            <td>{{ exam.title }}</td>
                                            <td>{{ exam.category.name }}</td>
                                            <td>{{ moment(exam.start_time).format("DD MMM YYYY, h:mm A") }}</td>
                                            <td>{{ moment(exam.end_time).format("DD MMM YYYY, h:mm A") }}</td>
                                            <td><b>{{ exam.school.name }}</b><br>Class : {{ exam.classes.name }} - Section : {{ exam.section.name }}</td>
                                            <td>{{ exam.created_by }}</td>
                                            <td>{{ moment(exam.created_at).format("DD MMM, YYYY - h:mm a") }}</td>
                                            <td>{{ exam.status }}</td>
                                            <td>{{ exam.question_count }}</td>
                                            <td>
                                                <router-link :to="{name: 'admin.exam-questions', params: {examId: exam.id}}" class="btn btn-sm btn-secondary" title="Add question"><i class="fas fa-plus"></i></router-link>
                                                <router-link :to="{name: 'admin.edit-exam', params: { examId: exam.id }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></router-link>
                                                <button @click="deleteExam(exam.id,i)" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                                <router-link :to="{name: 'admin.exam-report', params: { examId: exam.id }}" class="btn btn-sm btn-outline-primary">Reports</router-link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <modal name="edit-exam-modal" height="auto" :adaptive="true">
            <form action="" class="row p-5" @submit.prevent="updateMultiExam">
                <div class="col-md-12 mb-4">
                    <h6>EDIT EXAM : | <b v-for="(exam,i) in form.selectedExams">#{{ exam }} | </b></h6>
                </div>

                <div class="col-md-12 mb-4">
                    <label for="">Start Time</label>
                    <input type="datetime-local" :class="{'is-invalid' : form.errors.has('start_time')}" 
                        class="form-control" placeholder="Exam name" v-model="form.start_time">
                    <HasError :form="form" field="start_time" />
                </div>

                <div class="col-md-12 mb-4">
                    <label for="">End Time</label>
                    <input type="datetime-local" :class="{'is-invalid' : form.errors.has('end_time')}" 
                        class="form-control" placeholder="Exam name" v-model="form.end_time">
                    <HasError :form="form" field="end_time" />
                </div>

                <div class="col-md-12 mb-4">
                    <label for="">Status</label>
                    <select class="form-control" v-model="form.status">
                        <option value="draft">Draft</option>
                        <option value="published">Publish</option>
                        <option value="unpublished">Unpublish</option>
                    </select>
                    <HasError  :form="form" field="status"/>
                </div>

                <div class="col-md-12 mb-4">
                    <Button :form="form" type="submit" class="btn btn-primary">Send</Button>
                </div>
            </form>
        </modal>
    </div>
</template>
<script>
import Multiselect from "vue-multiselect";
export default {
    components: {
        "multiselect" : Multiselect,
    },
    data() {
        return {
            exams: [],
            examData: {},
            isLoading: true,
            moment: moment,
            selectedCat: "",
            schools: [],
            classes: [],
            sections: [],
            cats: [],
            isLoadingClass: false,
            isLoadingSection: false,

            selectedSchool: null,
            selectedClass: null,
            selectedSection: null,

            filterSchool : "",
            filterClass: "",
            filterSection: "",
            filterStatus : "",

            form: new Form({
                start_time: "",
                end_time: "",
                status: "published",
                selectedExams: [],
            }),
        }
        
    },
       
    
    methods: {
        async getExams() {
            this.isLoading = true;

            if(this.selectedSchool != null)
            {
                this.filterSchool = this.selectedSchool.id;
            }
            if(this.selectedClass != null)
            {
                this.filterClass = this.selectedClass.id;
            }
            if(this.selectedSection != null)
            {
                this.filterSection = this.selectedSection.id;
            }

            await axios.get("/admin/api/get-exam-list",{
                params: {
                    category: this.selectedCat,
                    school: this.filterSchool,
                    class: this.filterClass,
                    section : this.filterSection,
                    status: this.filterStatus,
                }
            }).then(resp=>{
                return resp.data;
            }).then(data=>{
                this.exams = data.data;
                this.examData = data;
                this.isLoading = false;
            }).catch(err=>{
                console.error(err.response.data);
            })
        },
        deleteExam(id,index) {
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                axios.post("/admin/api/delete-exam",{
                    examId: id,
                }).then(resp=>{
                    return resp.data;
                }).then(data=>{
                    if(data.status == "ok")
                    {
                    swal.fire("Removed",data.msg,"success");
                    this.exams.splice(index,1);
                    }
                }).catch(err=>{
                    toastr.error("Failed to delete","Internal Server error");
                    console.error(err.response.data);
                })
                }
            }); //swal
        },
        async getExamCats() {
            await axios.get("/admin/api/get-exam-categories").then(resp=>{
                return resp.data;
            }).then(data=>{
            // console.log(resp);
                this.cats = data;
                this.isLoading = false;
            }).catch(err=>{
                console.error(err.response.data);
            })
        },
        async getSchools() {
            await axios.get("/admin/api/get-all-schools").then(resp=>{
                return resp.data;
            }).then(data=>{
                this.schools = data;
            }).catch(err=>{
                console.error(err.response.data);
            });
        },

        async getClassList() {
            this.isLoadingClass = true;
            this.classes = [];
            this.sections = [];
            this.selectedSection = null;
            this.selectedClass = null;

            await axios.get("/admin/api/get-class-list?schoolId="+this.selectedSchool.id).then(resp=>{
                return resp.data;
            }).then(data=>{
                this.classes = data;
                this.isLoadingClass = false;
            }).catch(err=>{
                console.error(err.response.data);
            });
        },

        async getSectionList() {
            this.isLoadingSection = true;
            this.sections = [];
            this.selectedSection = null;
            await axios.get("/admin/api/get-section-list?classId="+this.selectedClass.id).then(resp=>{
                return resp.data;
            }).then(data=>{
                this.sections = data;
                this.isLoadingSection = false;
            }).catch(err=>{
                console.error(err.response.data);
            })
        },
        async filter() {
            await this.getExams();
        },
        editMultiExam() {
            this.$modal.show("edit-exam-modal")
        },
        updateMultiExam() {
            this.form.post("/admin/api/update-multi-exam").then(resp=>{
                return resp.data;
            }).then(data=>{
                if(data.status == "ok") {
                    swal.fire("Success",data.msg,"success").then(()=>{
                        window.location.reload();
                    });
                }
            }).catch(err=>{
                console.error(err.response.data);
            })
        }
    
    },
    async mounted() {
        await this.getExamCats();
        await this.getExams();
        await this.getSchools();
    }
}
</script>
<style lang="">
    
</style>