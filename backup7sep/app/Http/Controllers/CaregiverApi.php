<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
use App\IntakeCordinator;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Client;
use App\Http\Controllers\MailController;
use Carbon\Carbon;


class CaregiverApi extends Controller
{

public $successStatus = 200;

//get APIs

public function getfaq()
{

$data = DB::table('faqs')
->select('*')
->get();

return response()->json(['FAQs' => $data], $this-> successStatus);

}

public function getquiz()
{

$data = DB::table('carequizzes')
->select('*')
->get();

return response()->json(['Quiz' => $data], $this-> successStatus);

}

public function quiz(Request $request ,$id)
{

$result = DB::table('carequizzes')
->select('*')
->where('id',$id)
->first();


if($result)
{
	// $ans = $result->correct_answer;
	

	$data = array(
			'caregiver_id' => $request->caregiver_id,
			'quiz_id' => $result->quiz_id,
			'answer' => $request->answer,
			'question_id' => $result->id,
			'created_at' =>date('Y-m-d'),
			);
		if($data)
		{
		DB::table('quiz')->insert($data);
		return response()->json(['success' => 'Quiz Uploaded Successfully'], $this-> successStatus);
		}
}

else
{
return response()->json(['error'=>'No Quiz Found'], 401);
}
}


public function getcertificate($id)
{

$certificate = DB::table('certificates')
->select('*')
->where('caregiver_id',$id)
->first();


if($certificate)
{
$data = DB::table('certificates')
->select('*')
->where('caregiver_id',$id)
->get();
return response()->json(['success' => $data], $this-> successStatus);
}

else
{
return response()->json(['error'=>'No Certificate Found'], 401);
}
}
 
 public function getworkhistory($id)
{

$history = DB::table('work_history')
->select('*')
->where('schedule_id',$id)
->first();


if($history)
{
$data = DB::table('work_history')
->select('*')
->where('schedule_id',$id)
->get();
return response()->json(['success' => $data], $this-> successStatus);
}

else
{
return response()->json(['error'=>'No History Found'], 401);
}
}

public function getcaregiverOtp(Request $request)
{
$otp = $request->otp;

$data = DB::table('caregivers')
->select('id')
->where('otp',$otp)
->first();

if($data)
{
return response()->json(['success' => $data], $this-> successStatus);
}
else
{
return response()->json(['error'=>'No Such User Exist'], 401);
}

}


public function getcaregivernotification($id)
{

$caregiver_id = DB::table('notifications')
->select('caregiver_id')
->where('caregiver_id',$id)
->first();


if($caregiver_id)
{
$notification = DB::table('notifications')
->select('description')
->where('caregiver_id',$id)
->where('from','=','admin')
->orderByDesc('id')
->get();
return response()->json(['success' => $notification], $this-> successStatus);
}

else
{
return response()->json(['error'=>'No Notifications Found'], 401);
}
}


public function getcaregiverprofile(Request $request,$id)
{

$user = DB::table('caregivers')
->select('*')
->where('id',$id)
->first();

if($user)
{
return response()->json(['success' => $user], $this-> successStatus);
}

else
{
return response()->json(['error'=>'No User Found'], 401);
}
}


//post APIs

public function caregiverlogin(Request $request){

$validator = Validator::make($request->all(), [

'email' => 'required|email',
'password' => 'required',

]);
if ($validator->fails()) {
return response()->json(['error'=>$validator->errors()], 401);
}
$email = $request->email;
$password = $request->password;

$caregiver = DB::table('caregivers')
->select('*')
->where('email', $email)
->first();
$registeredEmail=$caregiver->email;
$registeredpwd=$caregiver->password;
$id['Caregiver_Id']=$caregiver->id;


if($registeredEmail){

if($registeredpwd == $password){
return response()->json(['success' => $caregiver], $this-> successStatus);
}
else{
return response()->json(['error'=>'Invalid Username and Password'], 401);

}

}
else{
return response()->json(['error'=>'Invalid Username and Password'], 401);

}
}


public function caregiverresetpassword(Request $request){



$email=$request->email;


$result = DB::table('caregivers')
->select('*')
->where('email', $email)
->first();

if($result){
$id =$result->id;
MailController::caregiverresetPasswordApp($email,$id);
return response()->json(['success'=>'Please check your email To verify Otp code'], $this-> successStatus);

}
else
{
return response()->json(['error'=>'No User Found'], 401);
}
}


public function caregiverupdatepassword(Request $request,$id)
{

$intakeco = DB::table('caregivers')
->select('*')
->where('id', $id)
->first();
if($intakeco)
{
$validator = Validator::make($request->all(), [
'password' => 'required',
'c_password' => 'required|same:password',

]);
if ($validator->fails()) {
return response()->json(['error'=>$validator->errors()], 401);
}
$input = $request->all();
$update = DB::table('caregivers')
->where('id', $id)
->update(['password' => $input['password']]);
return response()->json(['success'=>'Password Updated'], $this-> successStatus);

}
else
{
return response()->json(['error'=>"No such User exist"], 401);
}


}

public function updatecaregiverprofile(Request $request, $id)
{
 
if ($request->hasFile('image')) 
{
$extension=$request->image->extension();
$filename=time()."_.".$extension;
$request->image->move(public_path('/clients'),$filename);
$update = DB::table('caregivers')
->where('id', $id)
->update([
'dob' => $request->dob,
'phone' => $request->phone,
'image' => $filename

]);
return response()->json(['success' => 'Profile Updated Succesfully'], $this-> successStatus);

} else{
 $update = DB::table('caregivers')
->where('id', $id)
->update([
'dob' => $request->dob,
'phone' => $request->phone,

]);
return response()->json(['success' => 'Profile Updated Succesfully'], $this-> successStatus);
 
 
}

}

//other Developer Api


public function getCaregiverSchedules($id){

$schedules = DB::table('schedule')
->join('caregivers','caregivers.id','=','schedule.caregiver_id')
 ->join('clients','clients.id','=','schedule.client_id')
->select('caregivers.*','clients.*','schedule.*')
->where('schedule.caregiver_id', $id)
->get();


if($schedules)
{
return response()->json(['success' => $schedules], $this-> successStatus);
}

else
{
return response()->json(['error'=>'No Such Schedule Exist'], 401);
}

}

public function getschedulebyid($id){

		$schedule = DB::table('schedule')
		->select('*')
		->where('id', $id)
		->first();

		

		if($schedule)
		{
		    $client_id =  $schedule->client_id;
		$meetings = DB::table('meetings')
		 ->join('clients','clients.id','=','meetings.client_id')
		->select('clients.*','meetings.*')
		->where('meetings.client_id', $client_id)
		->get();

		return response()->json(['success' => $schedule,$meetings], $this-> successStatus);
		}

		else
		{
		return response()->json(['error'=>'No Such Schedule Exist'], 401);
		}

}

		public function checkinuser($id){

		$schedule = DB::table('schedule')
		->select('*')
		->where('id', $id)
		->first();
		if($schedule)
		{
		    date_default_timezone_set("UTC");
			$t=time();
			$time = date("Y-m-d H:i:s",$t);
			$update = DB::table('schedule')
					->where('id', $id)
					->update(['lastcheckin' => $time]);
		return response()->json(['message' => 'Checkin successful'], $this-> successStatus);
		}
		else
		{
		return response()->json(['error'=>'Checkin not successful'], 401);
		}

		}


		public function checkoutuser($id){

		$schedule = DB::table('schedule')
		->select('*')
		->where('id', $id)
		->first();
		if($schedule)
		{
		    date_default_timezone_set("UTC");
			$t=time();
			$time = date("Y-m-d H:i:s",$t);
			$update = DB::table('schedule')
					->where('id', $id)
					->update(['lastcheckout' => $time]);
		return response()->json(['message' => 'Checkout successful'], $this-> successStatus);
		}
		else
		{
		return response()->json(['error'=>'Checkout not successful'], 401);
		}

		}


public function addreminder(Request $request){

$data = array(
'caregiver_id' => $request->caregiver_id,
'title' => $request->title,
'date' => $request->date,
'details' => $request->details,
'created_at' =>date('Y-m-d'),
);


if($data)
{
DB::table('reminders')->insert($data);
return response()->json(['success' => 'Reminder Added Succesfully'], $this-> successStatus);
}
else
{
return response()->json(['error'=>'Fill all the fields'], 401);
}

}

public function getreminder(Request $request,$id){

$caregiver_id = DB::table('reminders')
->select('caregiver_id')
->where('caregiver_id',$id)
->first();

if($caregiver_id)
{
	$data = DB::table('reminders')
				->select('*')
				->where('caregiver_id',$id)
				->get();
return response()->json(['success' => $data], $this-> successStatus);
}
else
{
return response()->json(['error'=>'No Reminder Found'], 401);
}

}

public function editreminder(Request $request,$id){

$reminders = DB::table('reminders')
->select('*')
->where('id', $id)
->first();


if($reminders)
{


$data = array(
'caregiver_id' => $request->caregiver_id,
'title' => $request->title,
'date' => $request->date,
'details' => $request->details,
'created_at' =>date('Y-m-d'),
);


if($data)
{
$id = $reminders->id;
$update = DB::table('reminders')
->where('id', $id)
->update(['caregiver_id' => $request->caregiver_id,
'title' => $request->title,
'date' => $request->date,
'details' => $request->details,
'created_at' =>date('Y-m-d'),
]);
return response()->json(['success' => 'Reminder Updated Succesfully'], $this-> successStatus);
}
else
{
return response()->json(['error'=>'Fill all the fields'], 401);
}
}
else
{
return response()->json(['error'=>'No Such Reminder Exist'], 401);
}

}

public function deletereminder($id){

$reminders = DB::table('reminders')
->select('*')
->where('id', $id)
->first();


if($reminders)
{


$id = $reminders->id;
$delete = DB::table('reminders')
->where('id', $id)
->delete();

return response()->json(['success' => 'Reminder Deleted Succesfully'], $this-> successStatus);
}

else
{
return response()->json(['error'=>'No Such Reminder Exist'], 401);
}

}


public function uploadnotes(Request $request,$id){

			$schedule = DB::table('schedule')
						->select('*')
						->where('id', $id)
						->first();

			if($schedule)
			{
				$fileName = rand().'.'.$request->file->extension();  
 		// 	$data = array('caregiver_id' => $request->caregiver_id,
			// 'file' => $fileName,
			// 'notes' => $request->notes,
			// 'created_at' => date('Y-m-d '),        
 		// 	);

 			$update = DB::table('schedule')
					->where('id', $id)
					->update([
						'file' => $fileName,
						'notes' => $request->notes,
						'created_at' => date('Y-m-d '),
					]);
    
			$notes =$request->file->move(public_path('caregivernotes'), $fileName);
			if($notes)
			{
			return response()->json(['message' => 'Notes uploaded successful'], $this-> successStatus);
			}
			else
			{
			return response()->json(['error'=>'Notes not uploaded, Please try again'], 401);
			}

			}

			else
			{
			return response()->json(['error'=>'No Schedule Exist'], 401);
			}
			
		}
		
		
		
		public function uploadincidentreports(Request $request){


			$fileName = rand().'.'.$request->notes->extension();  
 			$data = array('caregiver_id' => $request->caregiver_id,
			'title' => $request->title,
			'message' => $request->message,
			'notes' => $fileName,
			'created_at' => date('Y-m-d '),        
 			);
			DB::table('incidentreports')->insert($data);         
			$notes =$request->notes->move(public_path('caregivernotes'), $fileName);
			if($notes)
			{
			return response()->json(['message' => 'Notes uploaded successful'], $this-> successStatus);
			}
			else
			{
			return response()->json(['error'=>'Notes not uploaded, Please try again'], 401);
			}
		}
		
		
		
		public function getnotes($id){
			$notes = DB::table('schedule')
			->select('notes','file')
			->where('id', $id)
			->get();
			if($notes)
			{
			return response()->json(['notes' => $notes], $this-> successStatus);
			}
			else
			{
			return response()->json(['message'=>'No notes is uploaded yet'], 401);
			}

		}
		
		
		
        public function getincidentreports($id){
        $notes = DB::table('incidentreports')
        ->select('*')
        ->where('caregiver_id', $id)
        ->get();
        if($notes)
        {
        return response()->json(['notes' => $notes], $this-> successStatus);
        }
        else
        {
        return response()->json(['message'=>'No notes is uploaded yet'], 401);
        }
        
        }



public function signup(Request $request){

$validator = Validator::make($request->all(), [

'email' => 'required|email',
'password' => 'required',
'c_password' => 'required|same:password',

]);
if ($validator->fails()) {
return response()->json(['error'=>$validator->errors()], 401);
}

$fileName = rand().'.'.$request->file->extension();  
$file =$request->file->move(public_path('caregiverdocuments'), $fileName);

$docName = rand().'.'.$request->doc->extension();  
$doc =$request->doc->move(public_path('caregiverdocuments'), $docName);

 			$data = array('name' => $request->name,
			'email' => $request->email,
			'password' => $request->password,
			'phone' => $request->phone,
			'city' => $request->city,
			'address' => $request->address,
			'zipcode' => $request->zipcode,
			'doc_name' => $request->doc_name,
			'doc_type' => $request->doc_type,
			'expiry_date' => $request->expiry_date,
			'file' => $fileName,
			'document' => $docName,
			'approved' => "0",
			'created_at' => date('Y-m-d '),        
 			);

 			if($data)
			{
				DB::table('caregivers')->insert($data); 
			return response()->json(['message' => 'Registration successful'], $this-> successStatus);
			}
			else
			{
			return response()->json(['error'=>'Error, Sign-up again'], 401);
			}
}


public function caregiverpayrollrequest(Request $request)
{

$data = array('title' => 'Caregiver Payroll Request',
'description' => ' wants to increase in payroll ',
'type' =>'Caregiver payrollrequest',
'from' => 'caregiver',
'to' => 'admin',
'payroll' => $request->payroll,
'admin' =>3,
'caregiver_id' =>$request->caregiver_id,
'date' =>date('Y-m-d '),
'time' => date('H:i:s')
);

DB::table('notifications')->insert($data);
return response()->json(['success'=>'Your Payroll Request Send Sucessfully'], $this-> successStatus);

}


public function workhistory($id){
$schedule = DB::table('schedule')
->select('*')
->where('caregiver_id', $id)
->first();

if($schedule)
{

$checkintime = $schedule->lastcheckin;
$checkout = $schedule->lastcheckout;


$start  = new Carbon($checkintime);
$end    = new Carbon($checkout);

$time = $start->diffInHours($end) . ':' . $start->diff($end)->format('%I:%S');

if(isset($checkintime) && isset($checkout))
{
$data = array(
'schedule_id' => $schedule->id,
'working_hrs' => $time,
'created_at' =>date('Y-m-d'),
);


DB::table('work_history')->insert($data);
return response()->json(['success' => 'Data Added Succesfully'], $this-> successStatus);
}
}

else
{
return response()->json(['error'=>'No Data Found'], 401);
}

}

public function certificate(Request $request,$id){

			$caregiver = DB::table('caregivers')
					->select('*')
					->where('id',$id)
					->first();


					if($caregiver)
					{
						$result = DB::table('certificates')
						->select('*')
						->where('caregiver_id',$id)
						->first();

						if($result)
						{
							$fileName = rand().'.'.$request->certificate->extension();  
				 			
							$update = DB::table('certificates')
									->where('caregiver_id', $id)
									->update(['certificate' => $fileName]);

							$certificate =$request->certificate->move(public_path('caregivercertificate'), $fileName);
							if($certificate)
							{
							return response()->json(['message' => 'Certificate uploaded successful'], $this-> successStatus);
							}
							else
							{
							return response()->json(['error'=>'Not uploaded, Please try again'], 401);
							}
						}
						else
						{
							$fileName = rand().'.'.$request->certificate->extension();  
				 			$data = array(
				 			'caregiver_id' => $caregiver->id,
							'certificate' => $fileName,
							'created_at' => date('Y-m-d '),        
				 			);
							DB::table('certificates')->insert($data); 

							$certificate =$request->certificate->move(public_path('caregivercertificate'), $fileName);
							if($certificate)
							{
							return response()->json(['message' => 'Certificate uploaded successful'], $this-> successStatus);
							}
							else
							{
							return response()->json(['error'=>'Not uploaded, Please try again'], 401);
							}
						}
					}

					else
					{
					return response()->json(['error'=>'No Data Found'], 401);
					}
			
		}









//end line
}