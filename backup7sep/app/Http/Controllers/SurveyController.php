<?php

namespace App\Http\Controllers;

use App\Survey;
use Illuminate\Http\Request;
use DB;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function index()
    {
    $view=Survey::distinct(['quizname'])->get();
      return view('survey/weeklysurvey',compact('view'));

    }

     public function addnewsurvey()
    {
          $random  =(rand(0,100000000));
        return view('survey/addnewsurvey',compact('random'));

    }  


       public function surveyview()
    {
        return view('survey/surveyview');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      
 /*     print_r($request->all());
      exit();*/
      $survey_id  = $request->survey_id;
      $onetime = $request->onetime;
      $weekly = $request->weekly;
      $monthly = $request->monhtly;
      $recurring = $request->recurring;
      $survey_name = $request->survey_name;
      $survey_question = $request->survey_question;
      $option_a = $request->option_a;
      $option_b = $request->option_b;
      $option_c = $request->option_c;
      $option_d = $request->option_d;
      $correct_option = $request->correct_option;

      for($count = 0; $count < count($survey_question); $count++)
      {
       $data = array(
      'survey_id' => $survey_id,
      'onetime' => $onetime,
      'weekly' => $weekly,
      'monthly' => $monthly,
      'recurring' => $recurring,
      'survey_name' => $survey_name,
      'survey_question' => $survey_question[$count],
      'option_a' => $option_a[$count],
      'option_b' => $option_b[$count],
      'option_c' => $option_c[$count],
      'option_d' => $option_d[$count],
      'correct_option' => $correct_option[$count],
      'date' =>date('Y-M-d'),
        ); 
      Survey::insert($data);
      }


       return redirect('weeklysurvey')->with('Message','Survey Added successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function show(Survey $survey)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function edit(Survey $survey)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Survey $survey)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Survey  $survey
     * @return \Illuminate\Http\Response
     */

       public function delete($id)
        {

        $survey=Survey::find($id);
        $deleted = $survey->delete();
        if ($deleted) 
        {
        return redirect('/weeklysurvey')->with ('message', 'survey successfully deleted!');
        }
        }  

}
