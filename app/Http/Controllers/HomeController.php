<?php

namespace App\Http\Controllers;

use DB;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{

  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
      $this->middleware('auth');
  }

  /**
  * Show the application dashboard.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
      return view('home');
  }

    public function questions()
    {
        $questions = DB::table('questions as q')->select('q.id as question_id', 'q.content', 'q.updated_at as question_update', 'u.id as user_id', 'u.name as username')
        ->join('users as u', 'u.id', '=', 'q.user_id')
        ->orderBy('q.id', 'desc')
        ->get();
        $array_questions = [];
        foreach ($questions as $question) {
            $answer_count = DB::table('answers')->where('question_id', $question->question_id)->count();
            $array_questions[] = array(
        "content" => $question->content,
        "username" => $question->username,
        "question_id" => $question->question_id,
        "answer_count" => $answer_count,
        "question_update" => $question->question_update
        );
        }
        $countall = count($array_questions);

        return view('questions', compact('array_questions', 'countall'));
    }

    public function myquestions()
    {
        $userid = Auth::id();
        $array_questions = [];
        $questions = DB::table('questions')->where('user_id', $userid)->orderBy('id', 'desc')->get();
        if ($questions != "") {
            foreach ($questions as $question) {
                $answer_count = DB::table('answers')->where('question_id', $question->id)->count();
                $array_questions[] = array(
            "content" => $question->content,
            "id" => $question->id,
            "answer_count" => $answer_count,
            "created_at" => $question->created_at,
            "updated_at" => $question->updated_at,
          );
            }
        }
        $countall = count($array_questions);
        return view('myquestions', compact('array_questions', 'countall'));
    }

    public function add_submit_question(Request $request)
    {
        $userid = Auth::id();
        $question = $request->question;
        $input = array(['user_id' => $userid,'content' => $question]);
        $add =  DB::table('questions')->insert($input);
        return redirect('myquestions')->with('add_success', 'Question successfully added!');
    }

    public function get_question(Request $request, $id)
    {
        $question_id = $id;
        $userid = Auth::id();
        $detail = DB::table('questions as q')
      ->select('q.id as question_id', 'q.content', 'q.updated_at as question_update', 'u.id as user_id', 'u.name as username', 'q.created_at as question_date')
      ->join('users as u', 'u.id', '=', 'q.user_id')->where('q.id', $id)->get();
        $detail = $detail[0];
        $answers = DB::table('answers as a')
      ->select('a.id as id_answer', 'a.content', 'a.updated_at as update_answer', 'u.id as user_id', 'u.name as username')
      ->join('users as u', 'u.id', '=', 'a.user_id')
      ->where('a.question_id', $id)
      ->get();
        $count = count($answers);
        return view('detailquestion', compact('count', 'detail', 'answers'));
    }

    public function edit_submit_question(Request $request)
    {
        $id = $request->question_id;
        $question = $request->new_question;
        $update_data = ['content' => $question];
        $edit = DB::table('questions')->where('id', $id)->update($update_data);
        echo json_encode('OK');
    }

    public function delete_submit_question(Request $request)
    {
        $id = $request->question_delete_id;
        $answer_del = DB::table('answers')->where('question_id', $id)->delete();
        $question_del = DB::table('questions')->where('id', $id)->delete();
        return redirect('myquestions')->with('add_success', 'Question successfully deleted!');
    }

    public function comment_submit_question(Request $request)
    {
        $userid = Auth::id();
        $question_id = $request->question_id;
        $comment = $request->new_comment;
        $input = array(['user_id' => $userid,'content' => $comment, 'question_id' => $question_id]);
        $add =  DB::table('answers')->insert($input);
        if ($add) {
            echo json_encode('OK');
        } else {
            echo json_encode('FAIL');
        }
    }

    public function comment_refresh($id)
    {
        $id = $id;
        $answers = DB::table('answers as a')->select('a.id as id_answer', 'a.content', 'a.updated_at as update_answer', 'u.id as user_id', 'u.name as username')->join('users as u', 'u.id', '=', 'a.user_id')->where('a.question_id', $id)->get();
        echo json_encode($answer);
    }

    public function comment_update_submit_question(Request $request)
    {
        $answer_id = $request->answer_id;
        $new_comment = $request->new_comment;
        $update_data = ['content' => $new_comment];
        $edit = DB::table('answers')->where('id', $answer_id)->update($update_data);
        echo json_encode('OK');
    }

    public function comment_delete($id)
    {
        $answer_id = $id;
        $del = DB::table('answers')->where('id', $answer_id)->delete();
        echo json_encode('OK');
    }
}
