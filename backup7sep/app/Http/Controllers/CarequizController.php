<?php

namespace App\Http\Controllers;

use App\Carequiz;
use Illuminate\Http\Request;
use DB;
class CarequizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {



        $view=Carequiz::distinct(['quizname'])->get();
        return view('carequiz/carequiz', compact('view'));

    }
    public function addnew()
    {
             $random  =(rand(0,100000000));
        return view('carequiz/addnewcarequiz',compact('random'));

    }
        public function quizview($id)
    {
      $quizupdate=Carequiz::where('id',$id) ->get();
        return view('carequiz/quizview',compact('quizupdate'));

    }

        public function createcarequiz(Request $request)
    {   


      $quiz_id  = $request->quiz_id;
      $quizname  = $request->quizname;
      $question = $request->question;
      $answer_a = $request->answer_a;
      $answer_b = $request->answer_b;
      $answer_c = $request->answer_c;
      $answer_d = $request->answer_d;
      $correct_answer = $request->correct_answer;

      for($count = 0; $count < count($question); $count++)
      {
       $data = array(

      'quizname' => $quizname,
      'quiz_id' => $quiz_id,
      'question' => $question[$count],
      'answer_a' => $answer_a[$count],
      'answer_b' => $answer_b[$count],
      'answer_c' => $answer_c[$count],
      'answer_d' => $answer_d[$count],
      'correct_answer' => $correct_answer[$count],
      'date' =>date('Y-M-d'),
        ); 
      Carequiz::insert($data);
      }


       return redirect('carequiz')->with('Message','Quiz Added successfully');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */



    /**
     * Display the specified resource.
     *
     * @param  \App\Carequiz  $carequiz
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        //
    }


    public function show(Carequiz $carequiz)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Carequiz  $carequiz
     * @return \Illuminate\Http\Response
     */
    public function edit(Carequiz $carequiz)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Carequiz  $carequiz
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Carequiz $carequiz)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Carequiz  $carequiz
     * @return \Illuminate\Http\Response
     */


       public function delete($id)
        {

        $Carequiz=Carequiz::find($id);
        $deleted = $Carequiz->delete();
        if ($deleted) 
        {
        return redirect('/carequiz')->with ('message', 'quiz successfully deleted!');
        }
        }  

}
