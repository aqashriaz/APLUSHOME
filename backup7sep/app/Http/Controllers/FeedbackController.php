<?php

namespace App\Http\Controllers;

use App\Feedback;
use Illuminate\Http\Request;
use DB;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
                $client = Feedback::distinct()->get(['client_id']);  
                $caregiver = Feedback::distinct()->get(['caregiver_id']);  

     if ($caregiver != NULL) {
                foreach ($caregiver as $row ) {
                $id= $row->caregiver_id;       
                $caregiver_feedback[] = Feedback::join('caregivers','caregivers.id','=','feedback.caregiver_id')
                ->select('caregivers.image','caregivers.name', 'feedback.*')
                ->get();
                }       
            }
         /*  print_r($caregiver_feedback);
exit();  */
/*         $caregiver_feed[]=$caregiver_feedback;
*/       /*                print_r($caregiver_feed);
                exit();
                  if ($client != NULL) {
                foreach ($client as $row ) {
                $id= $row->client_id;       
                $client_feedback[] = Feedback::join('clients','clients.id','=','feedback.client_id')
                ->select('clients.image','clients.client_name', 'feedback.*')
                ->where('feedback.client_id', $id)
                ->get();
                } */
  /*          print_r($client_feedback);
}
            exit();*/
    $view=Feedback::all();
        return view('feedback/feedback',compact('view','caregiver_feed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function show(feedback $feedback)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function edit(feedback $feedback)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, feedback $feedback)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function destroy(feedback $feedback)
    {
        //
    }
}
