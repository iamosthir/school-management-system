<?php

namespace App\Http\Controllers\Student;

use App\Models\User;
use App\Models\School;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\AssignedSupervisor;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function __construct()
    {
       $this->middleware("auth");
    }   

    public function getAllSchool()
    {
        $data = School::orderBy("name","asc")->get(["id","name"]);
        return response()->json($data);
    }

    public function getList(Request $req)
    {
        if($req->schoolId == "")
        {
            $classes = Classes::orderBy("id","desc")
            ->with("school:id,name")
            ->paginate(20);
        }
        else
        {
            $classes = Classes::orderBy("id","desc")
            ->where("school_id",$req->schoolId)->get(["id","name"]);
        }
        
        return response()->json($classes);
    }


    public function getListsection(Request $req)
    {
        if($req->classId == "")
        {
            $sections = Section::with("school:id,name")
            ->with("classes:id,name")->orderBy("name","asc")
            ->paginate(15);
        }
        else
        {
            $sections = Section::where("class_id",$req->classId)->get(["id","name"]);
        }

        return response()->json($sections);
    }


    public function store(Request $req)
    {
        $this->validate($req,[
            "name" => "required",
            "phone" => "required|unique:students,phone",
            "email" => "nullable|unique:students,email",
            "schoolId" => "required|exists:schools,id",
            "classId" => "required|exists:classes,id",
            "sectionId" => "required|exists:sections,id",
            "password" => "required|min:6",
        ]);

        $student = new Student();
        $student->name = $req->name;
        $student->phone = $req->phone;
        $student->email = $req->stdEmail;
        $student->school_id = $req->schoolId;
        $student->class_id = $req->classId;
        $student->section_id = $req->sectionId;
        $student->address = $req->address;
        $student->password = bcrypt($req->password);

        if($req->hasFile("photo"))
        {
            $file = $req->file("photo");
            $new_name = time()."_".rand().".".$file->getClientOriginalExtension();
            $file_path = $file->storeAs("",$new_name,"StudentPhotos");
            $photo_url = route("student-photo",["filename"=>$new_name]);
            $student->photo = $new_name;
            $student->photo_url = $photo_url;
        }

        $student->save();

        return [
            "status" => "ok",
            "msg" => "Student was added successfully"
        ];
    }

    public function registerPage()
    {
        return view("auth.student-register");
    }

    
    
}
