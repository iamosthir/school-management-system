<template>
  <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h4>Add super visor</h4>
            </div>
            <div class="card-body">
                <form @submit.prevent="addSupervisor" class="row">
                    <div class="col-md-4 mb-4">
                        <label for="">Supervisor Name</label>
                        <input type="text" class="form-control" :class="{'is-invalid' : form.errors.has('name')}" v-model="form.name" placeholder="Supervisor name">
                        <HasError :form="form" field="name" />
                    </div>
                    <div class="col-md-4 mb-4">
                      <label for="">Select Manager</label>
                      <multiselect :class="{'is-invalid': form.errors.has('manager')}" v-model="selectedManager" :options="managers" 
                      :preserve-search="true" placeholder="Select manager" label="name" track-by="id"></multiselect>
                      <HasError  :form="form" field="manager"/>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label for="">Phone number</label>
                        <input type="tel" class="form-control" :class="{'is-invalid' : form.errors.has('phone')}" v-model="form.phone" placeholder="Phone number...">
                        <HasError :form="form" field="phone" />
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="">Email <small>(optional)</small></label>
                        <input type="email" name="email" class="form-control" :class="{'is-invalid' : form.errors.has('email')}" v-model="form.email" placeholder="Email...">
                        <HasError :form="form" field="email" />
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="">Password</label>
                        <input type="text" class="form-control" :class="{'is-invalid' : form.errors.has('password')}" 
                        v-model="form.password" placeholder="Set password...">
                        <HasError :form="form" field="password" />
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="">Set profile picture <small>(optional)</small></label>
                        <input type="file" class="form-control-file" 
                        :class="{'is-invalid' : form.errors.has('pp')}" @change="fileChange" accept="image/*">
                        <HasError :form="form" field="pp" />
                    </div>

                    <div class="col-md-12 mb-4">
                        <label for="access"><input type="checkbox" id="access" v-model="form.accessToLeave"> &nbsp; Can access teachers leave applications</label>
                    </div>

                    <div class="col-12 mb-4">
                        <Button class="btn btn-success" :form="form">Add user</Button>
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
            form : new Form({
                school: this.$route.params.schoolId,
                name: '',
                phone: '',
                email: '',
                role: 'supervisor',
                password: 'school2022',
                pp: null,
                accessToLeave: false,
                manager: "",
            }),
            selectedManager: null,
            managers: [],
        }
    },
    methods: {
        getManagerList() {
            axios.get("/admin/api/get-manager-list").then(resp=>{
                return resp.data;
            }).then(data=>{
                this.managers = data;
            }).catch(err=>{
                console.error(err.response.data);
            })
        },
        async addSupervisor() {
            if(this.selectedManager != null)
            {
                this.form.manager = this.selectedManager.id;
            }
            await this.form.post("/admin/api/add-supervisor").then(resp=>{
                return resp.data;
            }).then(data=>{
                if(data.status == "ok") {
                    swal.fire("Supervisor added",data.msg,"success").then(()=>{
                        this.form.reset();
                    })
                }
            }).catch(err=>{
                toastr.error("Failed","Internal server error");
                console.error(err.response.data);
            })
        },
        fileChange(e) {
            let file = e.target.files[0];
            if(file) {
                this.form.pp = file;
            }
            else {
                this.form.pp = null;
            }
        }
    },
    mounted() {
        this.getManagerList();
    }
}
</script>

<style>

</style>