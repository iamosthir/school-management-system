<template>
  <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>Assign Class to <b>"{{ teacherData.name }}"</b></h4>
            </div>
            <div class="card-body">
                <div class="row mb-4" v-if="!isLoading">
                    <div class="col-md-12 text-center">
                      <img v-if="teacherData.photo_url == null" class="user-thumb-100" src="/image/portrait-placeholder.png" alt="">
                      <img v-else class="user-thumb-100" :src="teacherData.photo_url" alt="">
                      <h4 class="mt-5 text-success">{{ teacherData.name }}</h4>
                      <p class="text-muted">({{ teacherData.school.name }})</p>
                      <hr>
                    </div>
                </div>
                <div class="row mb-4">
                  <div class="col-md-6 col-lg-5 col-6">
                    <h5 class="mb-4">Assign new class</h5>
                    <form @submit.prevent="assignClass">
                      <div class="form-group mb-4">
                          <label for="">Select Class</label>
                          <multiselect @input="getSectionList" :class="{'is-invalid': form.errors.has('classId')}" :options="classes" 
                          :preserve-search="true" placeholder="Select class" label="name" track-by="id" v-model="selectedClass"></multiselect>
                          <HasError  :form="form" field="classId"/>
                      </div>
                      <div class="form-group mb-4">
                          <label for="">Select section</label>
                          <multiselect :class="{'is-invalid': form.errors.has('sectionId')}" v-model="selectedSection" :options="sections" 
                          :preserve-search="true" placeholder="Select section" label="name" track-by="id" :loading="isLoadingSec"></multiselect>
                          <HasError  :form="form" field="sectionId"/>
                      </div>
                      <div class="form-group mb-4">
                        <Button class="btn btn-success" :form="form">Assign</Button>
                      </div>
                    </form>

                  </div>

                  <div class="col-md-6 col-lg-5 col-6">
                    <h5>Assigned Class and sections</h5>
                    <table class="table table-bordered table-hover">
                      <tr v-if="assignedClass.length == 0">
                        <td colspan="3" class="text-danger text-center">Not assigned in any class</td>
                      </tr>
                      <tr v-for="(assign,i) in assignedClass" :key="i">
                        <td><strong>{{ assign.classes.name }}</strong></td>
                        <td><strong>{{ assign.section.name }}</strong></td>
                        <td><button @click="removeClass(assign.id,i)" title="Remove" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button></td>
                      </tr>

                    </table>
                  </div>
                </div>
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
      isLoading: true,
      teacherData: {},
      classes: [],
      sections: [],
      form: new Form({
        teacherId: this.$route.params.teacherId,
        classId: "",
        sectionId: "",
      }),
      isLoadingSec: false,
      selectedClass: null,
      selectedSection: null,
      assignedClass: [],
    }
  },
  methods: {
    async getTeacherInfo() {
        await axios.get("/admin/api/get-edit-teacher-data",{
            params: {
                teacherId : this.$route.params.teacherId
            }
        }).then(resp=>{
            return resp.data;
        }).then(data=>{
            if(data.status == "ok") {
              this.isLoading = false;
              this.teacherData = data.teacher;
            }
            else {
                this.$router.push({
                    name: 'admin.all-teacher'
                });
            }
        }).catch(err=>{
            this.$router.push({
                name: 'admin.all-teacher'
            });
            console.error(err.response.data);
        })
    },
    async getAssignClassData() {
      await axios.get("/admin/api/get-assign-class-data",{
        params: {
          teacherId: this.$route.params.teacherId,
        }
      }).then(resp=>{
        return resp.data;
      }).then(data=>{
        if(data.status == "ok") {
          console.log(data);
          this.classes = data.classes;
          this.assignedClass = data.assigned;
        }
      }).catch(err=>{
        console.error(err.response.data);
      })
    },
    getSectionList() {
        this.sections = [];
        this.selectedSection = null;
        this.isLoadingSec = true;
        axios.get("/admin/api/get-section-list?classId="+this.selectedClass.id).then(resp=>{
            return resp.data;
        }).then(data=>{
            this.sections = data;
            this.isLoadingSec = false;
        }).catch(err=>{
            console.error(err.response.data);
        })
    },

    async assignClass() {
      if(this.selectedClass != null) {
        this.form.classId = this.selectedClass.id;
      }
      if(this.selectedSection != null) {
        this.form.sectionId  = this.selectedSection.id;
      }
      await this.form.post("/admin/api/assign-teacher-class").then(resp=>{
        return resp.data;
      }).then(data=>{
        if(data.status == "ok") {
          swal.fire("Assigned",data.msg,"success");
          this.assignedClass.push(data.data);
        }
        else if(data.status == "fail") {
          swal.fire("Can't assign",data.msg,"error");
        }
      }).catch(err=>{
        console.error(err.response.data);
      })
    },
    removeClass(id,index) {
      swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Remove'
      }).then((result) => {
          if (result.isConfirmed) {
          axios.post("/admin/api/remove-teacher-class",{
              assignId: id,
          }).then(resp=>{
              return resp.data;
          }).then(data=>{
              if(data.status == "ok")
              {
                swal.fire("Class removed",data.msg,"success");
                this.assignedClass.splice(index,1);
              }
          }).catch(err=>{
              toastr.error("Failed to delete","Internal Server error");
              console.error(err.response.data);
          })
          }
      });//
    }

  },
  async mounted() {
    await this.getTeacherInfo();
    await this.getAssignClassData();

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