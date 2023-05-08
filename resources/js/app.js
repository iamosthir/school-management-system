// All Imports

import Vue from "vue";

import {
    Button,
    HasError,
    AlertError,
    AlertErrors,
    AlertSuccess
  } from 'vform/src/components/bootstrap4'

import Form from "vform";

import VueToastr2 from "vue-toastr-2";
import "vue-toastr-2/dist/vue-toastr-2.min.css";
import VueProgressBar from "vue-progressbar";

import SuperAdminRouter from "./routes/super-admin";
import SuperVisorRouter from "./routes/supervisor";
import TeacherRouter from "./routes/teacher";
import ManagerRouter from "./routes/manager";
import StudentRouter from "./routes/student";

import Swal from "sweetalert2";

import moment from "moment";

// 

import vueAwesomeCountdown from 'vue-awesome-countdown';
Vue.use(vueAwesomeCountdown, 'vac');

// Modal
import VModal from 'vue-js-modal/dist/index.nocss.js'
import 'vue-js-modal/dist/styles.css'

Vue.use(VModal)


// Moment
window.moment = moment;
// 
// Pagination
Vue.component("pagination",require("laravel-vue-pagination"));
// 

// Skeleton
Vue.component('skeleton', require('./components/Partials/CustomSkeleton.vue').default);
// End

// Notification
Vue.component('notification', require("./components/Partials/Notification.vue").default);

// 

// Sweet alert
window.swal = Swal;
// 

// Progress bar
Vue.use(VueProgressBar, {
    color: '#0015b5',
    failedColor: 'red',
    thickness: '5px'
});
// End

// Vue
window.Vue = Vue;
// 

// Toastr
window.toastr = require("toastr");
Vue.use(VueToastr2);
// End

// Axios
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// end

// Vform
window.Form = Form;
Vue.component(Button.name, Button)
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component(AlertErrors.name, AlertErrors)
Vue.component(AlertSuccess.name, AlertSuccess)
// End


// Login
if(document.getElementById("loginForm"))
{
    const login = new Vue({
        el: "#loginForm",
        data(){
            return {
                loginForm: new Form({
                    email: "",
                    password: "",
                    remember: false,
                    loginType: null,
                })
            }
        },
        methods: {
            userLogin(loginType = null){
                this.loginForm.loginType = loginType;
                this.loginForm.post("/attemp-login").then(resp=>{
                    return resp.data;
                }).then(data=>{
                    console.log(data);
                    if(data.status == "ok")
                    {
                        toastr.success("Login scuccess")
                        window.location.href = data.redirect_url;
                    }
                    else
                    {
                        toastr.error("Login failed",data.msg);
                    }

                }).catch(err=>{
                    toastr.error("Login failed","Internal server error");
                    console.error(err.response.data);
                })
            }
        }
    })
}
// End

if(document.getElementById("super_app"))
{
    const superAdmin = new Vue({
        el: "#super_app",
        router: SuperAdminRouter,
    })
}

if(document.getElementById('supervisor_app'))
{
    const superVisor = new Vue({
        el: "#supervisor_app",
        router: SuperVisorRouter
    })
}

if(document.getElementById("teacher_app"))
{
    const teacherApp = new Vue({
        el: "#teacher_app",
        router: TeacherRouter,
    })
}

if(document.getElementById("manager_app"))
{
    const managerApp = new Vue({
        el: "#manager_app",
        router: ManagerRouter,
    })
}

if(document.getElementById("student_app"))
{
    const studentApp = new Vue({
        el: "#student_app",
        router: StudentRouter,
    })
}

if(document.getElementById('studentRegister'))
{
    const studentRegister = new Vue({
        el: "#studentRegister",
        components: {
            "student-register" : ()=>import("./components/Global/StudentRegister.vue")
        }
    })
}
