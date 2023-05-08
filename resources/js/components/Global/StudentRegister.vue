
<template>
    <div class="row justify-content-center">
      <div class="col-md-12">
          <div class="card">
              <div class="card-header d-flex justify-content-between">
                  <h4>تسجيل الطالب</h4>
              </div>
              <div class="card-body">
                  <form class="row" @submit.prevent="addStudent">
                    <div class="col-md-4 mb-4">
                        <label for="">أختيار مدرستك</label>
                        <multiselect :class="{'is-invalid': form.errors.has('schoolId')}" v-model="selectedSchool" :options="schools" 
                         @input="getClassList" :preserve-search="true" placeholder="Select section" label="name" track-by="id"></multiselect>
                        <HasError  :form="form" field="sectionId"/>
                    </div>
  
                    <div class="col-md-4 mb-4">
                        <label for=""> الصف</label>
                        <multiselect :class="{'is-invalid': form.errors.has('classId')}" v-model="selectedClass" :options="classes" 
                         @input="getSectionList" :preserve-search="true" placeholder="Select section" label="name" track-by="id" :loading="isLoadingClass"></multiselect>
                        <HasError  :form="form" field="classId"/>
                    </div>
  
                    <div class="col-md-4 mb-4">
                        <label for="">الشعبة</label>
                        <multiselect :class="{'is-invalid': form.errors.has('sectionId')}" v-model="selectedSection" :options="sections" 
                          :preserve-search="true" placeholder="Select section" label="name" track-by="id" :loading="isLoadingSection"></multiselect>
                        <HasError  :form="form" field="sectionId"/>
                    </div>
  
                    <div class="col-md-4 mb-4">
                        <label for="">أسم الطالب</label>
                        <input type="text" class="form-control" :class="{'is-invalid': form.errors.has('name')}"
                         placeholder="Student name" v-model="form.name">
                         <HasError  :form="form" field="name"/>
                    </div>
  
                    <div class="col-md-4 mb-4">
                        <label for="">رقم هاتف الطالب / ولي الأمر</label>
                        <input type="tel" class="form-control" :class="{'is-invalid': form.errors.has('phone')}"
                         placeholder="Student phone..." v-model="form.phone">
                         <HasError  :form="form" field="phone"/>
                    </div>
  
                    <!-- <div class="col-md-4 mb-4">
                        <label for="">Student email <small>(Optional)</small></label>
                        <input type="email" class="form-control" :class="{'is-invalid': form.errors.has('stdEmail')}"
                         placeholder="Student email..." v-model="form.stdEmail">
                         <HasError  :form="form" field="stdEmail"/>
                    </div> -->
  
                    <div class="col-md-4 mb-4">
                        <label for="">كلمة المرور</label>
                        <input type="text" class="form-control" :class="{'is-invalid': form.errors.has('password')}"
                         placeholder="Set password.." v-model="form.password">
                         <HasError  :form="form" field="password"/>
                    </div>
  
                    <!-- <div class="col-md-7 mb-4">
                      <label for="">Address</label>
                      <textarea class="form-control" :class="{'is-invalid': form.errors.has('address')}"
                       v-model="form.address" placeholder="Student address..."></textarea>
                       <HasError  :form="form" field="address"/>
                    </div> -->
                    <div class="col-md-7 mb-4">
                      <label for="">صورة الطالب</label>
                      <input type="file" class="form-control-file" @change="fileChange" accept="image/*" id="stdFile">
                    </div>
                    <div class="col-md-12 mb-4">
                      <Button :form="form" class="btn btn-success">تسجيل</Button>
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
        selectedSchool: null,
        selectedClass: null,
        selectedSection: null,
        form: new Form({
          name: "",
          stdEmail: "",
          phone: "",
          schoolId: "",
          classId: "",
          sectionId: "",
          photo: null,
          password: "",
  
        }),
        schools: [],
        classes: [],
        sections: [],
        isLoadingClass: false,
        isLoadingSection: false,
      }
    },
    methods :{
      getSchools() {
          axios.get("/get-all-school").then(resp=>{
              return resp.data;
          }).then(data=>{
              this.schools = data;
          }).catch(err=>{
              console.error(err.response.data);
          });
      },
      fileChange(e) {
        let file = e.target.files[0];
        if(file) {
          this.form.photo = file;
        }
        else {
          this.form.photo = null;
        }
      },
  
      getClassList() {
        this.isLoadingClass = true;
        this.classes = [];
        this.sections = [];
        this.selectedSection = null;
        this.selectedClass = null;
  
        axios.get("/get-clas-list?schoolId="+this.selectedSchool.id).then(resp=>{
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
        axios.get("/get-sectio-list?classId="+this.selectedClass.id).then(resp=>{
            return resp.data;
        }).then(data=>{
            this.sections = data;
            this.isLoadingSection = false;
        }).catch(err=>{
            console.error(err.response.data);
        })
      },
      async addStudent() {
  
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
        
        await this.form.post('/created-new-student').then(resp=>{
          return resp.data;
        }).then(data=>{
          if(data.status == "ok") {
            swal.fire("Added",data.msg,"success");
            this.form.reset();
            this.selectedClass = null;
            this.selectedSection = null;
            this.selectedSchool = null;
            $("#stdFile").val("");
          }
        }).catch(err=>{
          console.error(err.response.data);
        })
      }
    },
    mounted() {
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

    .col-md-4.mb-4 {
    text-align: center;
    }

    button.btn.btn-success {
    float: right;
    width: 100%;
}
  </style>