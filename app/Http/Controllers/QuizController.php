<?php

namespace App\Http\Controllers;

use App\Events\NewQuizPublishedEvent;
use App\Events\PublishQuizResultsEvent;
use App\Quiztracker;
use Illuminate\Http\Request;
use App\Quiz;
use App\Question;
use App\Answer;

class QuizController extends Controller
{
    public function index(){
        return view('quiz.index');
    }

    public function attemptRefresh(){
        return redirect()->to('dashboard')->with('success', 'Successfully submitted!');
    }

    public function startQuiz($id) {
        $quiz = Quiz::find($id);
        if($quiz){
             if($quiz->is_published == 1 && $quiz->quiz_status == 'EXECUTION_STARTED'){
                return view('quiz.start', compact('id'));
             }
             return redirect()->back()->with('error', 'Quiz is not yet published!');
        }
        return redirect()->back()->with('error', 'No qiuz Found');
    }

    public function unpublishResults($id) {
        //sleep(1);
        $qf = \App\Quizfeedback::where('quiz_id', $id)->count();
        if($qf > 0) {
            \App\Quizfeedback::where('quiz_id', $id)->delete();
            foreach (\App\User::where('role_id', 2)->get() as $u) {
                $qfx = new \App\Quizfeedback;
                $qfx->published = 0;
                $qfx->user_id   = $u->id; 
                $qfx->quiz_id   = $id;
                $qfx->save();
            }
        }

        // Send Email
       // \App\HelperX::sendEmails('emails.quizresults_mail', 'QUIZ RESULTS ARE OUT!');
    }   

    public function publishResults($id) {
        //sleep(1);

        $quz = \App\Quiz::find($id);
        $quz->quiz_status = 'RESULTS_OUT';
        $quz->save();

        $qf = \App\Quizfeedback::where('quiz_id', $id)->count();

        if($qf == 0) {
            foreach (\App\User::where('role_id', 2)->get() as $u) {
                $qfx = new \App\Quizfeedback;
                $qfx->published = 1;
                $qfx->user_id   = $u->id; 
                $qfx->quiz_id   = $id;
                $qfx->save();
            }
        }

        event(new PublishQuizResultsEvent());
    }   

    public function seenResults($id) {
        $attempt = request('attempt');
        return view('quiz.seen', compact('id', 'attempt'));
    }

    public function seenXResults($id, $uxid) {
        
        return view('quiz.seenX', compact('id', 'uxid'));
    }

    public function checkreminders() {
        $quizId = request('quizId');

        $allusers = \App\User::where('role_id', 2)->where('active', 1)->get();
        $attendes = \App\Quiztracker::where('quiz_id', $quizId)->get();

        $none_attendes = [];

        foreach ($allusers as $u) {
            foreach ($attendes as $a) {
                if($u->id != $a->user_id) {
                    $none_attendes[] = $u;
                }
            }
        }

        return $none_attendes;

    }

    public function attempt() {

        $user_id = request('user_id');
        $quiz_id = request('quiz_id');

        $check = \App\Attempt::where('quiz_id', $quiz_id)->where('user_id', $user_id)->count();

        if($check) {
            return response()->json([
                "error" => true,
                "msg"   => "You already done this quiz"
            ]);
        }

        $answers = (request('attempts'));

        if($answers) {
            foreach ($answers as $a) {
                $aid    = $a["answer_id"];   
                $qid    = $a["question_id"]; 
                $att    = new \App\Attempt;
                $att->aid = $aid;
                $att->qid = $qid;
                $att->user_id = $user_id;
                $att->quiz_id = $quiz_id;
                $att->save();   
            }
        }else {
            $att    = new \App\Attempt;
            $att->user_id = $user_id;
            $att->quiz_id = $quiz_id;
            $att->save();   
        }

        $tracker = new Quiztracker();
        $tracker->user_id = auth()->user()->id;
        $tracker->full_name = auth()->user()->name;
        $tracker->quiz_id   =  $quiz_id;
        $tracker->depart_id = auth()->user()->department->id;
        $tracker->department_name = auth()->user()->department->name;
        $tracker->month = date('n');
        $tracker->year  = date('Y');
        $tracker->save();


       
        // Send Email to admins
        //\App\HelperX::sendEmailTOAdmins();
        
    }

    

    public function edit($id) {
        return view('quiz.edit', compact('id'));
    }

    public function preview($id) {
        return view('quiz.preview', compact('id'));
    }

    public function staff() {
        return view('quiz.staff');
    }

    public function report($id) {
        return view('quiz.report', compact('id'));
    }

    public function unpublish($id) {
        $quiz = Quiz::find($id);
        $quiz->quiz_status = 'EXECUTION_DONE';
        $quiz->save();
        // send Email here to every one
        //\App\HelperX::sendEmails('emails.quizpublished_mail', 'NEW QUIZ PUBLISHED!');
    }

    public function publish($id) {
        $quiz = Quiz::find($id);
        $quiz->is_published = true;
        $quiz->quiz_status = 'EXECUTION_STARTED';
        $quiz->save();
        // send Email here to every one
        event(new NewQuizPublishedEvent());
    }

    public function cancel($id) {
        $quizid = request('quizid');
        return view('quiz.questionsEditX', compact('id', 'quizid'));
    }

    public function destroyQuiz($id) {
        Quiz::find($id)->delete();
    }

    public function destroy($qid) {

        $q = Question::find($qid);
        $id = $q->quiz_id;
        $q->delete();
        $a = Answer::where('question_id', $qid)->delete();

        if($a) {
            $quiz = Quiz::find($id);
            $questions = $quiz->questions;

            foreach($questions as $k => $q) {
                $qn = Question::find($q->id);
                $qn->qn_no = ($k+1);
                $qn->save();
            }

        }

        return view('quiz.preview', compact('id'));
    }

    public function update($id){
        $quiz_name         = request('quiz_name');
        $quiz_no_questions = request('quiz_no_questions');
        $quiz_description  = request('quiz_description');

        $qns = Question::where('quiz_id', $id)->count();

        $q  = Quiz::find($id);

       

        if ($quiz_no_questions >= $qns) {
            $q->quiz_name = $quiz_name;
            $q->questions_no = $quiz_no_questions;
            $q->description = $quiz_description;
            $q->save();
        }

       
    }

    public function refresh() {
        return redirect()->back()->with('success', 'Successfully Updated!');
    }

     public function deleted() {
            return redirect()->back()->with('success', 'Successfully deleted!');
     }

    public function store() {
        $quiz_name         = request('quiz_name');
        $quiz_no_questions = request('quiz_no_questions');
        $quiz_description  = request('quiz_description');

        $check = Quiz::where('quiz_name', $quiz_name)->where('questions_no', $quiz_no_questions)->count();

        if($check > 0) {
            return redirect()->back()->with('error', 'Quiz exists!');
        }

        $q = new Quiz;
        $q->quiz_name = $quiz_name;
        $q->questions_no = $quiz_no_questions;
        $q->description = $quiz_description;
        $q->save();

        return redirect()->back()->with('success', 'Successfully Added!');

    }
}
