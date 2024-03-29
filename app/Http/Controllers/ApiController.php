<?php

/**
 * @SWG\Swagger(
 *   basePath="/qapp/public/api",
 *   @SWG\Info(
 *     title="QuizApp API",
 *     version="1.0.0"
 *   )
 * )
 */


namespace App\Http\Controllers;
// Useful Link: https://github.com/zircote/swagger-php/tree/2.0.9/Examples/petstore.swagger.io
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\Quiz;
use App\Question;
use App\Answer;

class ApiController extends Controller
{
	/**
 * @SWG\Post(
 *   path="/auth",
 *   summary="Authentication Users!",
 *   operationId="authenticate",
 *  @SWG\Parameter(
 *         name="body",
 *         in="body",
 *         description="User object",
 *         required=true,
 *         @SWG\Schema(ref="#/definitions/User"),
 *     ),
 *   @SWG\Response(response=200, description="successful operation"),
 *   @SWG\Response(response=406, description="not acceptable"),
 *   @SWG\Response(response=500, description="internal server error")
 * )
 *
 */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
            
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }


        $user = auth()->user();

       
        

        return response()->json(compact('token' ,'user'));
    }

    public function attempt($qid) {
        $user_id = auth()->user()->id;
        $quiz_id = $qid;
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

        return response()->json([
            "error" => false,
            "msg"   => "Successfully submitted"
        ]);
    }


	public function getAuthenticatedUser()
    {
            try {

                    if (! $user = JWTAuth::parseToken()->authenticate()) {
                            return response()->json(['user_not_found'], 404);
                    }

            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

                    return response()->json(['token_expired'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

                    return response()->json(['token_invalid'], $e->getStatusCode());

            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

                    return response()->json(['token_absent'], $e->getStatusCode());

            }

            return response()->json(compact('user'));
    }

/**
 * @SWG\Post(
 *   path="/register",
 *   summary="Register Users!",
 *   operationId="register",
 *  @SWG\Parameter(
 *         name="body",
 *         in="body",
 *         description="User object",
 *         required=true,
 *         @SWG\Schema(ref="#/definitions/User"),
 *     ),
 *   @SWG\Response(response=200, description="successful operation"),
 *   @SWG\Response(response=406, description="not acceptable"),
 *   @SWG\Response(response=500, description="internal server error")
 * )
 *
 */
    public function register(Request $request)
    {

   			// name: Test Man
			// email: test@email.com
			// password: secret
			// password_confirmation: secret

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);

            if($validator->fails()){
                    return response()->json($validator->errors(), 400);
            }

            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json(compact('user','token'),201);
    }

    private function myscore($quid) {
        return \DB::table('answers')->join('attempts', 'attempts.aid', '=', 'answers.id')
        ->select('*')
        ->where('attempts.quiz_id', $quid)->where('answers.correct', 1)
        ->where('attempts.user_id', auth()->user()->id)
        ->count();
    }


    private function ansx($id) {
       return \DB::table('answers')->join('questions', 'questions.id', '=', 'answers.question_id')->select('*')->where('questions.quiz_id', $id)->where('answers.correct', 1)->count();
    }


    private function rankMe($quid) {
        $r = 1;

        foreach (\App\User::where('role_id', 2)->get() as $u) {
            $rank = \DB::table('answers')->join('attempts', 'attempts.aid', '=', 'answers.id')
                ->select('*')
                ->where('attempts.quiz_id', $quid)->where('answers.correct', 1)
                ->where('attempts.user_id', $u->id)
                ->count();
            $ranks[$u->id] = $r;    
            $r++;   
        }

        arsort($ranks);

        return $ranks[auth()->user()->id] . "/" . \App\User::where('role_id', 2)->count();
    }

    public function myQuizes() {

        $quizes = \DB::table('quiz')->select('*')->where('is_published', 1)->orderBy('id', 'desc')->get();

        $dataX = [];

        if(count($quizes)) {

            foreach ($quizes as $q) {
                $c = \App\Attempt::where('user_id', auth()->user()->id)->where('quiz_id', $q->id)->orderBy('id', 'asc')->get();
                if(count($c) != 0) {
                    if($q->quiz_status != 'RESULTS_OUT') {
                        $data = [];
                        $data["quiz_id"]      = $q->id;
                        $data["quiz_name"]    = $q->quiz_name;
                        $data["description"]  = $q->description;
                        $data["questions_no"] = $q->questions_no;
                        $data["result"]       = [];
                        $dataX[] = $data;
                    }else {
                        $data = [];
                        $data["quiz_id"]      = $q->id;
                        $data["quiz_name"]    = $q->quiz_name;
                        $data["description"]  = $q->description;
                        $data["questions_no"] = $q->questions_no;
                        $data["result"]       = [];
                        $dataX[] = $data;
                    }
                }
            }

        }

        if(count($dataX) == 0) {
            return response()->json([
                "data" => ["quizes" => []]
            ], 200);
        }else {
            return response()->json([
                "data" => $dataX
            ], 200);
        }

    }

    public function startQuiz($id) {
        $quiz = Quiz::find($id);
        if($quiz){
             if($quiz->is_published == 1 && $quiz->quiz_status == 'EXECUTION_STARTED'){

                $questions = \App\Question::where('quiz_id', $quiz->id)->orderBy('qn_no', 'ASC')->get();

                $questions_ = [];

                foreach($questions as $q) {
                    $answers = \App\Answer::where('question_id', $q->id)->get();
                    $data = [];
                    $data["question_id"]         = $q->id;
                    $data["question_number"]     = $q->qn_no; 
                    $data["question_body"]       = $q->question; 
                    $data["question_category"]   = $q->category; 
                    $data["question_photo_location"]   = $q->qn_photo_location; 
                   
                    $questions_answers = [];
                    foreach($answers as $a) {
                        $datax = [];
                        $datax["answer_id"] = $a->id;
                        $datax["answer"]    = $a->answer;
                        //$datax["correct"]   = $a->correct == 0 ? false : true;
                        $questions_answers[] = $datax;
                        $data["questions_answers"] = $questions_answers;
                    }
                    $questions_[] = $data;
                }

                

                return response()->json([
                   "data" => [
                        "quiz" => ["quiz"=>$quiz, "detail"=>$questions_],
                        "message" => null,
                        "error" => false  
                   ]
                ], 200);
             }
            
            return response()->json([
               "data" => [
                    "quiz" => null,
                    "message" => "Quiz is not yet published!",
                    "error" => true 
               ]
            ], 400);
            
        }
        
        return response()->json([
               "data" => [
                    "quiz" => $quiz,
                    "message" => "No Quiz found",
                    "error" => true  
               ]
            ], 404);
    }

    public function changepassword(){
        $cnewPassword = request('password');
        $user = User::find(auth()->user()->id);
        $user->password = bcrypt($cnewPassword);
        $user->save();
        return response()->json(["message"=>"Password was changed successfully"], 200); 
    }

    /**
 * @SWG\Get(
 *   path="/dashboard",
 *   summary="Get User Dashboard",
 *   operationId="dashboard",
 *   @SWG\Response(response=200, description="successful operation"),
 *   @SWG\Response(response=406, description="not acceptable"),
 *   @SWG\Response(response=500, description="internal server error")
 * )
 *
 */
    public function dashboard() {
        $quizes = \App\Quiz::where('is_published', 1)->orderBy('id', 'DESC')->get();

        if(count($quizes)) {

            $dataX = [];

            foreach ($quizes as $q) {

                $c = \App\Quizfeedback::where('user_id', auth()->user()->id)->where('quiz_id', $q->id)->where('seen', 0)->get();

                $c1 = \App\Quizfeedback::where('quiz_id', $q->id)->where('published', 1)->where('user_id', auth()->user()->id)->count();

                $c2 = \App\Attempt::where('quiz_id', $q->id)->where('user_id', auth()->user()->id)->count(); 

                if($c2 == 0) {
                    $data = [];
                    $data["quiz_id"]      = $q->id;
                    $data["quiz_name"]    = $q->quiz_name;
                    $data["description"]  = $q->description;
                    $data["questions_no"] = $q->questions_no;
                    $data["result"]       = [];
                    $dataX[] = $data;
                }

                if(count($c)) {
                    $data = [];
                    $data["quiz_id"]      = $q->id;
                    $data["quiz_name"]    = $q->quiz_name;
                    $data["description"]  = $q->description;
                    $data["questions_no"] = $q->questions_no;
                    $data["result"]       = ["score" => ( $this->myscore($q->id) / $this->ansx($q->id) ), "rank" =>  $this->rankMe($q->id) ];
                    $dataX[] = $data;
                }

                
            }

            if(count($dataX) == 0) {
                return response()->json([
               "data" => ["quizes" => []]
            ], 200);
            }

            return response()->json([
               "data" => ["quizes" => $dataX]
            ], 200);
        }else {
            return response()->json([
               "data" => []
            ], 200);
        }
    }
}
