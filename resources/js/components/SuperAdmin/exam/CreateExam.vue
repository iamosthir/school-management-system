<template>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Create an exam</h4>
                </div>
                <div class="card-body">
                    <form class="row" @submit.prevent="addExam">
                        <div class="col-md-12 mb-4">
                            <label for="">Exam title</label>
                            <input type="text" :class="{'is-invalid' : form.errors.has('name')}" class="form-control" placeholder="Exam name" v-model="form.name">
                            <HasError :form="form" field="name" />
                        </div>

                        <div class="col-md-12 mb-4">
                            <label for="">Formla</label>
                            <input type="text" :class="{'is-invalid' : form.errors.has('formla')}" class="form-control" placeholder="Exam formla" v-model="form.formla">
                            <HasError :form="form" field="formla" />
                        </div>

                        <div class="col-md-12 mb-4">
                            <label for="">Exam Category</label>
                            <select :class="{'is-invalid' : form.errors.has('category')}" class="form-control" v-model="form.category">
                                <option value="" hidden selected>Select Exam Category</option>
                                <option v-for="(cat,i) in cats" :key="i" :value="cat.id">{{ cat.name }}</option>
                            </select>
                            <HasError :form="form" field="category" />
                        </div>



                        <!-- <div class="col-md-12 mb-4">
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
                        </div> -->

                        <div class="col-md-12 mb-4">
                            <label for="">Select School</label>
                            <multiselect :class="{'is-invalid': form.errors.has('schoolId')}" v-model="selectedSchool" :options="schools" 
                            @input="getClassList" :preserve-search="true" placeholder="Select section" label="name" track-by="id"></multiselect>
                            <HasError  :form="form" field="schoolId"/>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label for="">Select Class</label>
                            <multiselect :class="{'is-invalid': form.errors.has('classId')}" v-model="selectedClass" :options="classes" 
                            @input="getSectionList" :preserve-search="true" placeholder="Select section" label="name" track-by="id" :loading="isLoadingClass"></multiselect>
                            <HasError  :form="form" field="classId"/>
                        </div>

                        <div class="col-md-12 mb-4">
                            <label for="">Select Section</label>
                            <multiselect :class="{'is-invalid': form.errors.has('sectionId')}" v-model="selectedSection" :options="sections" 
                                :preserve-search="true" placeholder="Select section" label="name" track-by="id" :loading="isLoadingSection"></multiselect>
                            <HasError  :form="form" field="sectionId"/>
                        </div>

                        <div class="col-md-12 mb-4">
                            <Button type="submit" class="btn btn-success" :form="form">Save</Button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
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
            form: new Form({
                name: "",
                start_time: "",
                end_time: "",
                schoolId: "",
                classId: "",
                sectionId: "",
                category: "",
                formla: "",
            }),

            schools: [],
            classes: [],
            sections: [],
            cats: [],

            isLoadingClass: false,
            isLoadingSection: false,

            selectedSchool: null,
            selectedClass: null,
            selectedSection: null,
        }
    },
    methods: {
        getSchools() {
            axios.get("/admin/api/get-all-schools").then(resp=>{
                return resp.data;
            }).then(data=>{
                this.schools = data;
            }).catch(err=>{
                console.error(err.response.data);
            });
        },

        getClassList() {
            this.isLoadingClass = true;
            this.classes = [];
            this.sections = [];
            this.selectedSection = null;
            this.selectedClass = null;

            axios.get("/admin/api/get-class-list?schoolId="+this.selectedSchool.id).then(resp=>{
                return resp.data;
            }).then(data=>{
                this.classes = data;
                this.isLoadingClass = false;
            }).catch(err=>{
                console.error(err.response.data);
            });
        },

        getSectionList() {
            this.isLoadingSection = true;
            this.sections = [];
            this.selectedSection = null;
            axios.get("/admin/api/get-section-list?classId="+this.selectedClass.id).then(resp=>{
                return resp.data;
            }).then(data=>{
                this.sections = data;
                this.isLoadingSection = false;
            }).catch(err=>{
                console.error(err.response.data);
            })
        },
        getCategory() {
            axios.get("/admin/api/get-exam-categories").then(resp=>{
                return resp.data;
            }).then(data=>{
                this.cats = data;
            }).catch(err=>{
                console.error(err.response.data);
            });
        },
        addExam() {
            if(this.selectedSchool != null)
            {
                this.form.schoolId = this.selectedSchool.id;
            }
            if(this.selectedClass != null)
            {
                this.form.classId = this.selectedClass.id;
            }
            if(this.selectedSection != null)
            {
                this.form.sectionId = this.selectedSection.id;
            }

            this.form.post("/admin/api/save-exam").then(resp=>{
                return resp.data;
            }).then(data=>{
                if(data.status == "ok") {
                    swal.fire("Success",data.msg,"success").then(()=>{
                        this.$router.push({
                            name: "admin.exam-questions",
                            params: {
                                examId: data.examId
                            }
                        })
                    });
                }
            }).catch(err=>{
                console.error(err.response.data);
            })
            
        }
    },
    mounted() {
        this.getCategory();
        this.getSchools();
    }
}
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
    .multiselect__option--highlight {
        background: #6170df !important;
    }
    .multiselect__option--highlight::after{
    background: #6170df !important;
    }
    .multiselect__tag{
    background: #6170df !important;
    }
    .multiselect__option--selected{
    background-color: #e58787 !important;
    }
    .multiselect__option--selected::after{
    background-color: #e58787 !important;
    }
    .is-invalid{
    border: 1px solid red;
    }
</style>