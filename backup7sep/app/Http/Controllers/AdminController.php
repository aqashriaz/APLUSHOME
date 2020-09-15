<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Admin;
use App\User;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;
use App\Faq;
use Auth;
use App\IntakeCordinator;
use App\Caregiver;
class AdminController extends Controller
{
    public function changepassword(){ return view('admin.changepassword'); }
    public function myprofile(){return view('admin.myprofile');}
    public function accounting(){return view('admin.accounting');}
     public function test($id){
      
     }


    public function viewprofile(){
        $id= Auth::user()->id;
        $viewprofile = DB::table('users')->select('*')->where('id', $id)->first();
        return view('admin.viewprofile',compact('viewprofile'));  
    }

    public function updateprofile(Request $request){
        $id=Auth::user()->id;
        $oldimage = $request->oldimage;
        if ($request->hasFile('image')) 
        {
        $extension=$request->image->extension();
        $filename=time()."_.".$extension;

        $aa = $request->image->move(public_path('/admin'),$filename);
        } else{
        $filename = $oldimage;
        } 
        $update = DB::table('users')
        ->where('id', $id)
        ->update(['name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'address' => $request->address,
        'image' => $filename
        ]);
        return redirect('/viewprofile')->with('message','Profile Updated Sucessfully'); 
     }

    public function addtask(){
         $activecaregivers = DB::table('caregivers')->select('*')->where('status', 1)->get();
         $activeclients = DB::table('clients')->select('*')->where('status', 1)->get();
         $intakecordinator = DB::table('intakecordinator')->select('*')->where('status', 1)->get();
         return view('admin.addnewtask',compact('activecaregivers','activeclients','intakecordinator'));   
    }

    public function addmeeting(Request $request){

        $data = array('client_id' => $request->client_id,
        'intakeco_id' => $request->intakeco_id,
        'time' => $request->time,
        'date' => $request->date
         );
        DB::table('meetings')->insert($data);
        return back();
    }


    public function addschedule(Request $request){
      $data = array('client_id' => $request->client_id,
      'caregiver_id' => $request->caregiver_id,
      'timeStart' => $request->timeStart,
      'timeEnd' => $request->timeEnd,
      'date' => $request->date
      );        
      DB::table('schedule')->insert($data); 
      return back();
      }

    public function newpassword(Request $request){
        $id= Auth::user()->id;
        $confirmpassword=$request->confirmpassword;
        $newpassword=$request->newpassword;
        if($newpassword == $confirmpassword ){
        $password = DB::table('users')
        ->where('id', $id)
        ->update(['password' => bcrypt($newpassword)
                 ]);
        return back()->with('message','Password Updated.');;
        }
        else{
        return back()->with('message','Confirm Password not matched');;
        }
   
       }

    public function addstaff(){
        $staff = DB::table('users')->select('*')->get();
        $totalstaff = DB::table('users')->select('*')->count();
        return view('admin.addstaff',compact('staff','totalstaff'));
        } 

    public function create(Request $request)
        {   
        $this->validate($request, [
        'name' => 'required|min:3|max:50',
        'email' => 'required', 'string', 'email', 'max:255', 'unique:users',
        'password' => 'confirmed|min:6',
        ]);  
        User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        ]);
        return back()->with('message','New Admin created sucessfully.');
        }
      
    public function sendmessage(Request $request){

         $data = array('message' =>  $request->message,
          'from' => 'admin',
          'to' => 'intakeCordinator',
          'type' => 'send',          
          'admin' =>Auth::user()->id,
          'intakeco_id' =>$request->intakeco_id,
          'date' =>date('Y-m-d '),
          'time' => date('H:i:s')
           );
          DB::table('messages')->insert($data);
           return back()->with('message','Message sent to intake cordinator.');
          }

 

   public function conversationMessage(Request $request)
      {
      $message =$request->message;
      $data = array('message' =>  $request->message,
      'from' => 'admin',
      'to' => 'intakeCordinator',
      'type' => 'send',          
      'admin' =>Auth::user()->id,
      'intakeco_id' =>$request->intakeco_id,
      'date' =>date('Y-m-d '),
      'time' => date('H:i:s')
      );
      DB::table('messages')->insert($data);
      return $message;
      }

  public function increasepayroll(Request $request){
        $update = DB::table('intakecordinator')->where('id', $request->payrollid)->update([
        'intakeco_payroll' => $request->intakeco_payroll]);
        $update = DB::table('notifications')->where('id', $request->notificationid)->update(['deactivate' => 1]);
        return redirect('/notifications');  
        }

  public function rejectpayrollreq($id){
    $update = DB::table('notifications')
    ->where('id', $id)
    ->update(['deactivate' => 1]);
    return redirect('/notifications');
  }

  public function notifications(){
        $payrollrequest = DB::table('notifications')->join('intakecordinator','intakecordinator.id','=','notifications.intakeco_id')->select('intakecordinator.*', 'notifications.*')->where('notifications.type', 'payrollrequest')->where('notifications.deactivate','!=',1)->orderBy('notifications.id', 'DESC')->get();
        $notifications = DB::table('notifications')->join('intakecordinator','intakecordinator.id','=','notifications.intakeco_id')->select('intakecordinator.*', 'notifications.*')->where('type','!=','clientapproval')->orderBy('notifications.id', 'DESC')->get();
        return view('admin.notifications',compact('notifications','payrollrequest'));
        }

  
    public function conversation($id){
        $conversation = DB::table('messages')->select('*')->where('intakeco_id', $id)->get();
        $uid = Auth()->User()->id;
        $sendsms = Message::all();
        $intakeco = DB::table('intakecordinator')->select('*')->where('id', $id)->first();
        return view('admin.conversation',compact('conversation','sendsms','id','intakeco'));
    }  

      public function caregiver_conversation($id){
        $conversation = DB::table('caregiversms')->select('*')->where('caregiver_id', $id)->get();
        $uid = Auth()->User()->id;
        $sendsms = Message::all();
        $caregiver = DB::table('caregivers')->select('*')->where('id', $id)->first();
       /* print_r($caregiver);
        exit();*/
        return view('admin.caregiver_conversation',compact('conversation','sendsms','id','caregiver'));
    }

     public function fetchmessage(){
        $conversation = 'abc';    
        return  $conversation;
    }
     public function fetch_caregiver_message(){
        $conversation_sms = 'abc';    
        return  $conversation_sms;
    }


    public function approvepayroll($id){
        $notificationsId = DB::table('notifications')->where('id', $id)->select('*')->first();
        $intake_id=$notificationsId->intakeco_id;
        $notifications = DB::table('intakecordinator')->where('id', $intake_id)->select('*')->first();    
        return view('admin.payrollapprove',compact('notifications','id'));
    }

     

   public function markallunread(){
          $update = DB::table('notifications')->where('status', 1)->update(['deactivate' => 1]);
          return response()->json(['success'=>'Ajax request submitted successfully']);
          }
  
   public function getnotificationcount(){
      $count = DB::table('notifications') ->select('*')->where('from','!=','admin')->where('deactivate','!=',1)->count();
      return $count;
       }

    

       public function addtaskquestions(Request $request)
       {

        $question= $request->question;
        $answer= $request->answer;

        $save= Faq::create([
          'question'=>$question,
          'answer'=>$answer,

        ]);
return back();

       }     
         public function addtaskVideo(Request $request)
       {

        if ($request->hasFile('video') && $request->video->isValid()) 
        {
        $extension=$request->video->extension();
        $filename=time()."_.".$extension;
        $request->video->move(public_path('video'),$filename);
        }
        else
        {
        $filename='no vido.mp4';
        }
        $question= $request->question;
        $video= $request->video;
          $save= Faq::create([
          'question'=>$question,
          'video'=>$filename,

        ]);

return back();


       }

   public function caregiversms(Request $request){
         $data = array('message' =>  $request->message,
          'from_name' => 'admin',
          'to_name' => 'Caregiver',
          'type' => 'send',          
          'admin_id' =>Auth::user()->id,
          'caregiver_id' =>$request->caregiver_id,
          'date' =>date('Y-m-d '),
          'time' => date('H:i:s')
           );
          DB::table('caregiversms')->insert($data);
       
           return back()->with('message','Message sent to caregiver.');
     }

      public function intakecosms(Request $request){
         $data = array('message' =>  $request->message,
          'from' => 'admin',
          'to' => 'intakeCordinator',
          'type' => 'send',          
          'admin' =>Auth::user()->id,
          'intakeco_id' =>$request->intakeco_id,
          'date' =>date('Y-m-d '),
          'time' => date('H:i:s')
           );
          DB::table('messages')->insert($data);
       
           return back()->with('message','Message sent to caregiver.');
     }


    public function faq()
    {
        $question=Faq::where('status', 1)->paginate(5);
        
         $exvideo=Faq::where('status', 2)->paginate(5);
        return view('admin.faq',compact('question','exvideo'));
    }


            
      

   
    public function inbox()
              {

              $intakeCordinator = DB::table('messages')->distinct()->get(['intakeco_id']);  
              $caregiver_db = DB::table('caregiversms')->distinct()->get(['caregiver_id']);  

        if ($intakeCordinator != NULL) {
          foreach ($intakeCordinator as $row ) {
              $id= $row->intakeco_id;       
              $allmessages[] = DB::table('messages')
              ->join('intakecordinator','intakecordinator.id','=','messages.intakeco_id')
              ->select('intakecordinator.image','intakecordinator.intake_name', 'messages.*')
              ->where('messages.intakeco_id', $id)
              ->orderBy('messages.date', 'DESC')
              ->first();
              }   
              }
        if ($caregiver_db != NULL) {
          foreach ($caregiver_db as $row ) {
              $id= $row->caregiver_id;       
              $caregiver_messages[] = DB::table('caregiversms')->join('caregivers','caregivers.id','=','caregiversms.caregiver_id')->select('caregivers.image','caregivers.name', 'caregiversms.*')->where('caregiversms.caregiver_id', $id)->orderBy('caregiversms.date', 'DESC')->first();
              } 
              }
              /*print_r($caregiver_messages);
              exit();*/
              $caregiver=Caregiver::all();
              $intakeco=IntakeCordinator::all();

              return view('admin.inbox',compact('allmessages','caregiver','intakeco','caregiver_messages'));

              }





   public function conversation_caregiversms(Request $request)
      {
      $message =$request->message;
      $data = array('message' =>  $request->message,
      'from' => 'admin',
      'to' => 'caregiver',
      'type' => 'send',          
      'admin' =>Auth::user()->id,
      'caregiver_id' =>$request->caregiver_id,
      'date' =>date('Y-m-d '),
      'time' => date('H:i:s')
      );
      DB::table('caregiversms')->insert($data);
      return $message;
      }

      
    public function caregiver_sendmessage(Request $request){

         $data = array('message' =>  $request->message,
          'from' => 'admin',
          'to' => 'intakeCordinator',
          'type' => 'send',          
          'admin' =>Auth::user()->id,
          'caregiver_id' =>$request->caregiver_id,
          'date' =>date('Y-m-d '),
          'time' => date('H:i:s')
           );
          DB::table('caregiversms')->insert($data);
           return back()->with('message','Message sent to Caregiver.');
          }





}
