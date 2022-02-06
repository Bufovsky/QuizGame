<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\Score;

class QuizController extends Controller
{
    //session
    private $sendQuestions = [];
    private $questions = [['question' => 'Program apache to:', 
                        'answears' => ['serwer do parsowania kodu stron', 'biblioteka php', 'samochód'], 
                        'answear' => 'serwer do parsowania kodu stron'],
                    ['question' => 'Jaki paradygmat ma język PHP:',
                        'answears' => ['objektowy', 'strukturalny', 'wieloparadygmatowy'], 
                        'answear' => 'wieloparadygmatowy'],
                        ['question' => 'Jakiego koloru jest niebieski maluch?',
                            'answears' => ['czerwony', 'niebieski', 'żółty'], 
                            'answear' => 'niebieski']
                    ];

    /**
     * Generate question function
     * 
     * @return question array
     */
    public function generateQuestion() {
        return $this->questions[array_rand($this->questions)];
    }

    /**
     * Function find answer in array
     * 
     * @return correct bollean
     */
    function searchArray($id, $array, $table) {
        foreach ($array as $key => $val) {
            if ($val[$table] == $id) {
                return $val;
            }
        }
        return false;
    }

    /**
     * Function check answer by value
     * 
     * @return correct bollean
     */
    function searchAnswear($answear) {
        $id = $this->searchArray($answear, $this->questions, 'answear');
        return $id ? 1 : 0;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(string $id)
    {
        return view('quiz.question')->with('question', $this->questions[$id]);
    }

    /**
     * Game start funciotnality
     *
     * @return \Illuminate\Http\Response
     */
    public function newGame()
    {
        //
        $game = Score::select('game_id')->orderByDesc('id')->limit(1)->get()->toArray();
        $game_id = intval($game[0]['game_id']);
        
        return redirect(route('quiz.game', ['id' => $game_id]));
    }

    /**
     * Game algorithm.
     *
     * @param id game id
     * @return \Illuminate\Http\Response
     */
    public function game($id)
    {
        $result = Score::where('game_id', $id)->get();

        if ( $result->count() < 3 ) {
            $question = $this->questions[$result->count()];
            return view('quiz.question')->with('question', $question)->with('game_id', $id);
        } else {
            $result = Score::where('game_id', $id)->where('score', 1)->count();
            $share = $this->view($result);
            return view('quiz.result')->with('result', $result)->with('game_id', $id)->with('share', $share);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function view($result)
    {
        
            $api = new ApiController;
            return $api->index($result);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id, Request $request)
    {
        $result = Score::where('game_id', $id)->get();

        if ( $result->count() <=  3 ) {
            $this->validate($request, [
                'submit' => 'required|string'
            ]);

            $user_id = auth()->user()->id;
            $score = $this->searchAnswear($request->all()['submit']);

            Score::create([
                'user_id' => $user_id,
                'game_id' => $id,
                'score' => $score,
            ]);

            return redirect( route('quiz.game', ['id' => $id]) );
        }

    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user_id = auth()->user()->id;
        $results = Score::where('user_id', $user_id)->groupBy('game_id')->get()->toArray();
        
        return view('scores')->with('results', $results);
    }
}
