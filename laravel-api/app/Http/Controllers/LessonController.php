<?php

namespace App\Http\Controllers;
use App\Article;
use App\Lesson;
use App\Test;
 
class LessonController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index($course_id, $lesson_id)
    {
        return view('lesson/lesson')->with('id', $lesson_id);
    }

    public function getArticle($article_id)
    {
        $article = Article::select()->where('id', '=', $article_id)->get();
        return $article;
     //   return view('lesson/article');
    }
    
    public function getVideo($video_id)
    {
        $video = Video::select()->where('id', '=', $video_id)->get();
        return $video;
       // return view('lesson/video');
    }

    // public function getQuiz($quiz_id)
    // {
    //     $quiz = Test::select()->where('id', '=', $quiz_id)->get();
    //     return $quiz;
    //     return view('lesson/quiz');
    // }

    public function getLesson($lesson_id)
    {
        if($lesson_id>1)
            get('TestController@popQuiz');
        $lesson = Lesson::select()->where('id', '=', $lesson_id)->get();
        return $lesson;
    }
    

}
