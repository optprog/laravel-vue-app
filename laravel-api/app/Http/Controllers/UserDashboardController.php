<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\User_course;

class UserDashboardController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $user_courses = User_course::select()->where([['user_id', '=', $user->id], ['isDeleted', '=', '0']])->with('Course')->get();
        return view('layouts.user_dashboard')->with(['user' => $user, 'user_courses' => $user_courses]);
    }

    public function enrollCourse(Request $request){
        //return $user_id = Auth::User()->id;
        $user_id = $request['user_id'];
        $course_id = $request['course_id']; 
        //check if the user had a record in the user_course table or not.
        $exists = User_course::select()->where([['user_id', '=', $user_id],['course_id', '=', $course_id]])->get();
        if(empty($exists[0]) != true){//this condition means the record is already in the table
            //check if the course is already registered or not.
            $isDeleted = User_course::select('isDeleted')->where([['user_id', '=', $user_id],['course_id', '=', $course_id]])->get();
            if($isDeleted[0] == true){//true means 1
                $update_query = User_course::select('isDeleted')->where([['user_id', '=', $user_id],['course_id', '=', $course_id]])->update(['isDeleted' => 0]);
                return ['success' => true, 'data' => [], 'message' => "You have been successfully enrolled in the course!"];
            }
            else if ($isDeleted[0] == false){//false means 0
                return ['success' => false, 'data' => [], 'message' => "YOU ARE ALREADY REGISTERED TO THIS COURSE!"];  
            }
            
        }else{//this condition means the record is new to the table
            try{
                $user_courses = User_course::insert([
                    'user_id' => $user_id,
                    'course_id' => $course_id,
                    'created_at' => \Carbon\Carbon::now(),  //since you are using QueryBuilder (insert method) you have to create the timestamp manually, because Fields created_at,update_at and deleted_at are "part" of Eloquent and you cannot use them in QueryBuilder
                    'updated_at' => \Carbon\Carbon::now(),
                ]);
                return ['success' => true, 'data' => [], 'message' => "You have been successfully enrolled in the course!"];
            }catch (\Illuminate\Database\QueryException $e) {
                if ($e->getCode() == '42S22') {
                    return ['success' => false, 'data' => [], 'message' => $e->errorInfo[2]];
                } else if ($e->getCode() == '22007') {
                    return ['success' => false, 'data' => [], 'message' => "WRONG FORMAT!"];
                } else if ($e->getCode() == '23000') {
                        return ['success' => false, 'data' => [], 'message' => "YOU ARE ALREADY REGISTERED TO THIS COURSE!"];  
                } else {
                    return ['success' => false, 'data' => [], 'message' => "CHECK YOUR INPUTS!"];
                }
            }
        }
    }

    public function dropCourse(Request $request){
        try{
            $user_id = $request['user_id'];
            $course_id = $request['course_id'];
            $update_query = User_course::select()->where([['user_id', '=',$user_id],['course_id', '=', $course_id]])->get();
            
            if($update_query != null){//this condition means the query is valid
                $isDeleted = User_course::select('isDeleted')->where([['user_id', '=', $user_id], ['course_id', '=', $course_id]])->get();
                if($isDeleted[0]['isDeleted'] === 1){//true means 1
                    return ['success' => false, 'data' => [], 'message' => "YOU HAVE ALREADY DROPPED THIS COURSE!"];  
                }
                else if ($isDeleted[0]['isDeleted'] === 0){//false means 0
                     return $update_query = User_course::select('isDeleted')->where([['user_id', '=',$user_id],['course_id', '=', $course_id]])->update(['isDeleted' => '1']);
                    if($update_query == true)//no error occured
                        return ['success' => true, 'data' => [], 'message' => "The Course has been dropped successfully!"];
                    else//an error has occured
                        return ['success' => false, 'data' => [], 'message' => "CHECK YOUR INPUTS!"];
                }
            }else{// this condition means the query is invalid
                return ['success' => false, 'data' => [], 'message' => "CHECK YOUR INPUTS!"];
            }
        }catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '42S22') {
                return ['success' => false, 'data' => [], 'message' => $e->errorInfo[2]];
            } else if ($e->getCode() == '22007') {
                return ['success' => false, 'data' => [], 'message' => "WRONG FORMAT!"];
            } else if ($e->getCode() == '23000') {
                    return ['success' => false, 'data' => [], 'message' => "YOU HAVE ALREADY DROPPED THIS COURSE!"];  
            } else {
                return ['success' => false, 'data' => [], 'message' => "CHECK YOUR INPUTS!"];
            }
        }
        
    }
}