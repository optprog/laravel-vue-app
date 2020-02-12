<?php

namespace App\Http\Controllers;

use App\Test;
use App\Test_question;
use App\Test_type;
use App\User_course;
use App\User_test_answer;
use Illuminate\Http\Request;

class TestController extends Controller
{


    //need to check all conditions to make the code robust enough
    public function getPlacementTest($course_id)
    {

        $test_type_id = 3; // placement_test;
      //  $test_type_id = Test_type::select('id')->where('test_type', '=', $test_type)->get();

        $placementTest = Test::select()->where([['course_id', '=', $course_id], ['test_type_id', '=', $test_type_id]])->with('Test_question.Question.Choice')->get();

        for ($i = 0; $i < sizeof($placementTest[0]['test_question']); $i++) {
            $questions[$i] = $placementTest[0]['test_question'][$i]['Question'];
        }
      //  return $questions[$i]->Choice[$j]['choice_content'];
        return $questions; // in the view you should print the content in a nested loop, $questions[$i]->Choice[$j]['choice_content'];

    }

    public function getTest($course_id, $lesson_id, $test_type)
    {
       // $course_id, $lesson_id, $test_type

        //$test_type_id = 3; // placement_test;
        $test_type_id = Test_type::select('id')->where('test_type', '=', $test_type)->get();
        $test_id = Test::select('id')->where([['test_type_id', '=', $test_type_id[0]['id']],['course_id', '=', $course_id]])->get();
        return $test_questions = Test_question::select()->where([['course_id', '=', $course_id], ['lesson_id', '=', $lesson_id], ['test_id', '=', $test_id[0]['id']] ])->with('Question.Choice')->get();
 
    //     for ($i = 0; $i < sizeof($test_questions[0]); $i++) {
    //         $questions[$i] = $test_questions[0]['test_question'][$i]['Question'];
    //     }
    //    // return $questions[$i]->Choice[$j]['choice_content'];
    //     return $questions; // in the view you should print the content in a nested loop, $questions[$i]->Choice[$j]['choice_content'];

    }
    public function getTest2(Request $request)
    {
        /** The method accept the following Json: 
                      {
	                    "course_id": "1",
	                    "lesson_id" : ["1"],
                        "test_type": "placement_test"
                        }
        **/

        // $user_id = Auth::user()->id;   
        $user_id = $request['user_id'];            
        $course_id = $request['course_id'];
        $lesson_id = $request['lesson_id'];
        $test_type = $request['test_type'];
    //put in the form (post request) the type of the data sent, content-type : application/json
        if($course_id == null){
            return "please select a course first";
        }
        try { 
        if($test_type == "placement_test"){
            $isFirstTime = User_course::select('isFirstTime')->where([['user_id', '=', $user_id],['course_id', '=',$course_id]])->get();
            if($isFirstTime[0]['isFirstTime'] == true){
                  //retrieve the placement test if it was the first time enrolled in the course
                 $test_type_id = Test_type::select('id')->where('test_type', '=', $test_type)->get();
                    
                 if($test_type_id[0]['id'] != '0'){
                 $test_id = Test::select('id')->where([['test_type_id', '=', $test_type_id[0]['id']],['course_id', '=', $course_id]])->get();
            return $test_questions = Test_question::select()->where([ ['course_id', '=', $course_id], ['test_id', '=', $test_id[0]['id']],['test_type_id', '=', $test_type_id[0]['id']] ])->with('Question.Choice')->get();
            } else{
                return ['success' => false, 'data' => [], 'message' => "THERE ARE NO PLACEMENT TEST FOR THIS COURSE!"];

            }
             }else{
                return ['success' => false, 'data' => [], 'message' => "THE PLACEMENT TEST HAS BEEN ALREADY TAKEN!"];
            }
          
        }else if($test_type != null  && $lesson_id != null){
                       
                //returns questions of multiple lessons
                $test_type_id = Test_type::select('id')->where('test_type', '=', $test_type)->get();
                //return $test_id = Test::select('id')->where([['test_type_id', '=', $test_type_id[0]['id']],['course_id', '=', $course_id]])->get();
                return $test_questions = Test_question::select()->where([['course_id', '=', $course_id], ['test_type_id', '=',$test_type_id[0]['id']]])->whereIn('lesson_id', $lesson_id)->with('Question.Choice')->get();
                

                //return ['success' => true, 'data' => [], 'message' => "your test has been saved"];
   
        }else{
            return "please fill all the required inputs";
        }

    } catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() == '42S22') {
            return ['success' => false, 'data' => [], 'message' => $e->errorInfo[2]];
        } else if ($e->getCode() == '22007') {
            return ['success' => false, 'data' => [], 'message' => "WRONG FORMAT!"];
        } else if ($e->getCode() == '23000') {
            return ['success' => false, 'data' => [], 'message' => "YOU CANNOT CREATE A TEST"];
        } else {
            return ['success' => false, 'data' => [], 'message' => "CHECK YOUR INPUTS!"];
        }
    }
        //$test_type_id = 3; // placement_test;
      
    //     for ($i = 0; $i < sizeof($test_questions[0]); $i++) {
    //         $questions[$i] = $test_questions[0]['test_question'][$i]['Question'];
    //     }
    //    // return $questions[$i]->Choice[$j]['choice_content'];
    //     return $questions; // in the view you should print the content in a nested loop, $questions[$i]->Choice[$j]['choice_content'];

    }
 
    public function savePlacementTestAnswers(Request $request)
    {

        // {
        //     "Question_ids": {
        //         "1": {
        //             "Choice_ids": [
        //                 "1",
        //                 "2"
        //             ]
        //         },
        //         "2": {
        //             "Choice_ids": [
        //                 "2",
        //                 "5"
        //             ]
        //         }
        //     }
        // }
        //$user_id = Auth::User()->id; 
        $user_id = $request['user_id'];
        $test_id = $request['test_id'];
        $questions = $request['test_question'];

        try {
            for ($i = 0; $i < sizeof($questions); $i++) {
                $user_test_answers = User_test_answer::insert([
                    'user_id' => $user_id,
                    'test_id' => $test_id,
                    'question_id' => $questions[$i]['question_id'],
                    'choice_id' => $questions[$i]['choice_id']['0'],
                    'created_at' => \Carbon\Carbon::now(),  //since you are using QueryBuilder (insert method) you have to create the timestamp manually, because Fields created_at,update_at and deleted_at are "part" of Eloquent and you cannot use them in QueryBuilder
                    'updated_at' => \Carbon\Carbon::now(),

                ]);
            }
            return ['success' => true, 'data' => [], 'message' => "your test has been submitted"];
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '42S22') {
                return ['success' => false, 'data' => [], 'message' => $e->errorInfo[2]];
            } else if ($e->getCode() == '22007') {
                return ['success' => false, 'data' => [], 'message' => "WRONG FORMAT!"];
            } else if ($e->getCode() == '23000') {
                return ['success' => false, 'data' => [], 'message' => "YOU HAVE ALREADY ANSWERED THIS QUESTION!"];
            } else {
                return ['success' => false, 'data' => [], 'message' => "CHECK YOUR INPUTS!"];
            }
        }
    }

    public function saveTestAnswers(Request $request)
    {
        $user_id = $request['user_id'];
        $course_id = $request['course_id'];
        $test_id = $request['test_id'];
        $questions = $request['test_question'];

        try {

            for ($i = 0; $i < sizeof($questions); $i++) {
                $user_test_answers = User_test_answer::insert([
                    'user_id' => $user_id,
                    'test_id' => $test_id,
                    'question_id' => $questions[$i]['question_id'],
                    'choice_id' => $questions[$i]['choice_id']['0'],
                    'created_at' => \Carbon\Carbon::now(),  //since you are using QueryBuilder (insert method) you have to create the timestamp manually, because Fields created_at,update_at and deleted_at are "part" of Eloquent and you cannot use them in QueryBuilder
                    'updated_at' => \Carbon\Carbon::now(),

                ]);
            }
            $isPlacementTest = Test::select('test_type_id')->where('id', $test_id)->get();
            if($isPlacementTest[0]['test_type_id'] == 3){
                $setFirstTimeToZero = User_course::select()->where([['user_id', $user_id], ['course_id', $course_id]])->update(['isFirstTime' => '0']);
     
            }
            return ['success' => true, 'data' => [], 'message' => "your test has been submitted"];
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == '42S22') {
                return ['success' => false, 'data' => [], 'message' => $e->errorInfo[2]];
            } else if ($e->getCode() == '22007') {
                return ['success' => false, 'data' => [], 'message' => "WRONG FORMAT!"];
            } else if ($e->getCode() == '23000') {
                return ['success' => false, 'data' => [], 'message' => "YOU HAVE ALREADY ANSWERED THIS QUESTION!"];
            } else {
                return ['success' => false, 'data' => [], 'message' => "CHECK YOUR INPUTS!"];
            }
        }
    }


    public function popQuiz(){
        $change = rand(1,100);
        if($change > 0 && $change < 50)
            redirect('/questions');

    }

    public function calculateResult(Request $request)
    {
        
    }
}
