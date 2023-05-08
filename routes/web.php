<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get("/clear-cache",function(){
    Artisan::call("optimize:clear");
    dd(Artisan::output());
});

Route::get('/', function () {
    return redirect()->route("student-login");
});


// student register



Route::get("/get-all-school","Student\RegisterController@getAllSchool");
Route::get("/get-clas-list","Student\RegisterController@getList");
Route::get("/get-sectio-list","Student\RegisterController@getListsection");
Route::post("/created-new-student","Student\RegisterController@store");



//end 


// User login
Route::get("/user-login","Auth\LoginController@loginForm")->name("user-login");

Route::post("/attemp-login","Auth\LoginController@attemptLogin")->name('user.login');

Route::post("/user-logout","Auth\LoginController@logout")->name("user-logout");
// End

// Student
// login
Route::get('/student-register',"Student\RegisterController@registerPage")->name("student-register");
Route::get('/student-login',"Auth\LoginController@studentLoginForm")->name("student-login");

Route::group(["prefix" => "student","middleware" => "auth:student"],function(){

    Route::get("/{any}","Student\StudentPagesController@index")->where("any", "^(?!api/.*$).*$");

    Route::group(["prefix" => "api"],function(){

        Route::get("/get-upcoming-exams","Student\ExamController@getUpcoming");

        Route::get("/attend-exam","Student\ExamController@attendExam");

        Route::post("/submit-answer","Student\ExamController@submitAnswer");

        Route::get("/get-my-exams","Student\ExamController@getMyExam");

        Route::get("/get-result","Student\ExamController@getResult");

        Route::get("/get-exam-result-datatable","Student\ExamController@getResultData");

        // Profile
        Route::get('/get-my-profile-data',"Student\DashboardController@myProfile");

        Route::post('/update-my-profile',"Student\DashboardController@updateMyProfile");

        Route::get("/get-dashboard-data","Student\DashboardController@getDashboardData");

    });
    
});
// End

// Storage resources
Route::group(["prefix" => "storage"],function(){
    Route::get("/user/photos/{filename}","Storage\StorageController@getUserPhoto")->name("user-photo");
    Route::get('/student/photo/{filename}',"Storage\StorageController@getStudentPhoto")->name("student-photo");
    Route::get("/bank/photo/{filename}","Storage\StorageController@getBankPhoto")->name("bank-attachment");
});
// end

// Admin actions
Route::group(["prefix"=>"admin","middleware"=>"auth"],function(){

    Route::get("/{any}","Admin\AdminPagesController@index")->where("any", "^(?!api/.*$).*");

    Route::group(["prefix" => "api"], function(){

        // Schools
        Route::post("/store-school","Admin\SchoolController@store");

        Route::get("/get-school-list","Admin\SchoolController@getList");

        Route::post("/update-school","Admin\SchoolController@update");

        Route::post("/delete-school","Admin\SchoolController@delete");

        Route::get("/get-school-data","Admin\SchoolController@getData");

        Route::get("/get-all-schools","Admin\SchoolController@getAllSchool");

        Route::post("/add-supervisor","Admin\SuperVisorController@addSupervisor");

        Route::post("/update-supervisor-profile","Admin\SuperVisorController@updateSupervisor");

        Route::post("/delete-supervisor","Admin\SuperVisorController@deleteSupervisor");

        Route::get("/get-supervisors","Admin\SuperVisorController@get");

        Route::post("/add-teacher","Admin\SuperVisorController@addTeacher");

        Route::get("/get-all-supervisors","Admin\SuperVisorController@getList");

        Route::get("/get-user-data","Admin\SuperVisorController@getUser");

        Route::get("/get-teacher-list-by-supervisor","Admin\TeacherController@getTeacherBySuperv");
        // 

        // class
        Route::post('/add-new-class',"Admin\ClassController@add");

        Route::get("/get-class-list","Admin\ClassController@getList");

        Route::post("/delete-class","Admin\ClassController@delete");

        Route::post('/update-class',"Admin\ClassController@update");
        // 

        // Section
        Route::post("/add-new-section","Admin\SectionController@store");

        Route::get("/get-section-list","Admin\SectionController@getList");

        Route::post("/delete-section","Admin\SectionController@delete");

        Route::get("/get-section-data","Admin\SectionController@getData");

        Route::post("/update-section","Admin\SectionController@update");
        // End

        // Students

        Route::post("/create-new-student","Admin\StudentController@store");

        Route::get("/get-student-list","Admin\StudentController@getStudentList");

        Route::post("/delete-student","Admin\StudentController@delete");

        Route::get("/get-student-data","Admin\StudentController@getData");

        Route::post("/update-student","Admin\StudentController@update");

        Route::post("/import-student","Admin\StudentController@importStudent");

        Route::get("/get-student-ratings","Admin\StudentController@getRatings");

        Route::get("/get-student-count","Admin\StudentController@countStudent");

        Route::post("/change-student-status","Admin\StudentController@changeStudentStatus");
        // End

        // Teacher
        Route::get("/get-teacher-list","Admin\TeacherController@list");

        Route::post("/delete-teacher","Admin\TeacherController@delete");

        Route::get('/get-edit-teacher-data',"Admin\TeacherController@getTeacherInfo");

        Route::post("/update-teacher","Admin\SuperVisorController@updateTeacher");

        Route::get("/get-teacher-ratings","Admin\TeacherController@getTeacherRating");

        Route::get("/get-teacher-data","Admin\SuperVisorController@getTeacherData");

        Route::get("/get-teachers-students","Admin\SuperVisorController@getStudentByTeacher");

        // End

        // Dashboard
        
        Route::get("/get-dashboard-data","Admin\AdminPagesController@getDashboardData");

        Route::get("/get-admin-list","Admin\AdminPagesController@adminList");

        Route::get("/get-manager-list","Admin\AdminPagesController@managerList");

        Route::post('/create-admin',"Admin\AdminPagesController@createAdmin");

        Route::post("/delete-admin","Admin\AdminPagesController@deleteAdmin");

        Route::get("/get-admin-data","Admin\AdminPagesController@getAdminData");

        Route::post("/update-admin","Admin\AdminPagesController@updateAdmin");

        Route::get("/get-my-profile-data","Admin\AdminPagesController@myProfile");

        Route::post('/update-my-profile',"Admin\AdminPagesController@updateMyProfile");

        Route::post('/create-manager',"Admin\AdminPagesController@createManager");

        Route::post("/update-manager","Admin\AdminPagesController@updateManager");

        // End

        // Leave request

        Route::get("/get-leave-request","Admin\LeaveRequestController@getList");

        // end

        // Salary

        Route::get("/get-salary-info","Admin\SalaryController@getSalaryData");
        
        Route::post("/submit-payment","Admin\SalaryController@submitPayment");

        Route::get("/payment-list","Admin\SalaryController@getPaymentList");

        Route::get("/filter-salary-data","Admin\SalaryController@filterSalaryData");
        // End

        // Tree data
        Route::get("/get-tree-data","Admin\AdminPagesController@getTreeData");

        // Setting Data
        Route::get("/get-setting-data","Admin\SettingsController@getSettingData");

        Route::post('/update-setting',"Admin\SettingsController@updateData");
        // 

        // Exam 

        Route::get("/get-exam-categories","Admin\ExamCategoryController@getCategory");

        Route::post('/save-exam',"Admin\ExamController@saveExam");

        Route::post("/update-exam","Admin\ExamController@update");

        Route::post("/copy-exam","Admin\ExamController@copy");

        Route::post("/delete-exam","Admin\ExamController@deleteExam");

        Route::get("/get-exam-list","Admin\ExamController@getExamList");

        Route::post("/store-question","Admin\QuestionController@storeQuestion");

        Route::post('/update-question',"Admin\QuestionController@update");

        Route::get("/get-exam-data","Admin\ExamController@getExamData");

        Route::post("/delete-question","Admin\QuestionController@delete");

        Route::get('/check-exam-data',"Admin\ExamController@checkExamData");

        Route::get("/get-question-data","Admin\QuestionController@getData");

        Route::post('/save-exam-cat',"Admin\ExamCategoryController@save");

        Route::post('/update-exam-cat',"Admin\ExamCategoryController@update");

        Route::post("/delete-exam-cat","Admin\ExamCategoryController@delete");

        Route::get("/get-exam-report","Admin\ExamController@getReport");

        Route::get("/get-result","Admin\ExamController@getResult");

        Route::get("/get-exam-result-datatable","Admin\ExamController@getResultData");

        Route::post('/update-multi-exam',"Admin\ExamController@updateMulti");

        // End

        // Assing Class
        
        Route::get("/get-assign-class-data","Admin\TeacherController@getAssignData");

        Route::post('/assign-teacher-class',"Admin\TeacherController@assignClass");

        Route::post("/remove-teacher-class","Admin\TeacherController@removeClass");

        // end


    });
});
// End

// Supervisors
Route::group(["prefix" => "supervisor", "middleware" => "auth:supervisor"],function(){

    Route::get("/{any}","Supervisor\SupervisorController@index")->where("any", "^(?!api/.*$).*$");

    Route::group(["prefix" => "api"],function() {

        Route::get("/get-teacher-list","Supervisor\SupervisorController@getTeacher");

        Route::get("/check-user-review","Supervisor\SupervisorController@checkUser");

        Route::post("/submit-review","Supervisor\RatingController@submit");

        Route::get("/get-leave-request","Supervisor\LeaveRequestController@getList");

        Route::post("/update-application-status","Supervisor\LeaveRequestController@updateStatus");

        Route::get("/get-teacher-ratings","Supervisor\RatingController@getTeacherRating");

        Route::get("/get-dashboard-data","Supervisor\DashboardController@getDashboardData");

        Route::get('/get-my-profile-data',"Supervisor\DashboardController@myProfile");

        Route::post('/update-my-profile',"Supervisor\DashboardController@updateMyProfile");

        Route::get("/get-teacher-data","Supervisor\SupervisorController@getTeacherData");

        Route::get("/get-teachers-students","Supervisor\SupervisorController@getStudentByTeacher");

        Route::get("/get-student-ratings","Supervisor\StudentController@getRatings");

    });

});
// End

// Teachers
Route::group(["prefix" => "teacher", "middleware" => "auth:teacher"], function(){

    Route::get("/{any}","Teacher\TeacherController@index")->where("any", "^(?!api/.*$).*$");
    
    Route::group(["prefix" => "api"],function(){

        Route::post("/submit-leave-request","Teacher\LeaveRequestController@submit");

        Route::get("/get-leave-data","Teacher\LeaveRequestController@getData");

        Route::get('/get-my-students',"Teacher\StudentController@getStudents");

        Route::get("/check-student-review","Teacher\StudentController@checkStudent");

        Route::post("/submit-review","Teacher\StudentController@submitReview");

        Route::get("/get-student-ratings","Teacher\StudentController@getRatings");

        Route::get("/get-dashboard-data","Teacher\DashboardController@getDashboardData");

        Route::get('/get-my-profile-data',"Teacher\DashboardController@myProfile");

        Route::post('/update-my-profile',"Teacher\DashboardController@updateMyProfile");

        Route::get("/get-payment-history","Teacher\SalaryController@getPaymentHistory");

        Route::post("/accept-payment","Teacher\SalaryController@acceptPayment");
    });
});
// End

// Manager
Route::group(["prefix" => "manager","middleware" => "auth:manager"],function(){

    Route::get("/{any}","Manager\ManagerController@index")->where("any", "^(?!api/.*$).*$");

    Route::group(["prefix" => "api"],function(){

        Route::get("/get-all-supervisors","Manager\SupervisorController@getList");

        Route::get("/get-my-students","Manager\StudentController@getStudents");

        Route::get("/get-teacher-list","Manager\TeacherController@getTeacher");

        Route::get("/get-teacher-data","Manager\TeacherController@getTeacherData");

        Route::get("/get-teachers-students","Manager\TeacherController@getStudentByTeacher");

        Route::get("/get-teacher-list-by-supervisor","Manager\TeacherController@getTeacherBySuperv");

        Route::get("/get-leave-request","Manager\LeaveRequestController@getList");

        Route::post("/update-application-status","Manager\LeaveRequestController@updateStatus");

        Route::get("/get-teacher-ratings","Manager\TeacherController@getTeacherRating");

        Route::get("/get-student-ratings","Manager\StudentController@getRatings");

        Route::get('/get-my-profile-data',"Manager\DashboardController@myProfile");

        Route::post('/update-my-profile',"Manager\DashboardController@updateMyProfile");

    });
    
});
// End

// User notification
Route::get("/user-notifications","NotificationController@getList");
// End