<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\Users;
use App\Classes\Base64;
use Auth;
use DB;
use Mail,GeoIP;
use Illuminate\Support\Str;

class UserController extends Controller
{

    function index(Request $request)
    {
        $req =json_decode(urldecode(base64_decode(request()->data)),true);
        
        $key=$req['i3'];
        $salt=$req['i4'];
        if($salt=="" || $key==""){
            return $this->respError("Something went wrong!");      
        }
        if($key!= md5(env('API_KEY').$salt)){
            return $this->respError("Something went wrong!!" );      
        }
        
        

        if(env('cn')!=""){
            geoip()->getLocation(null);
            $ip=\Request::ip();
            $arr_ip= geoip()->getLocation($ip);
    
            $ar=explode(",",env('cn'));
            if(in_array($arr_ip->iso_code,$ar)){
               return $this->respError(str_replace('_', ' ',env('msg')));
            }
        }
        
        
        if($req['type']==0){
            return $this->store($req);
        }else if($req['type']==15){
            return $this->fetch_coin($req);
        }else if($req['type']==18){
            return $this->send_Verfiyotp($req['ex_id']);
        }else if($req['type']==19){
            return $this->checkVerfied($req['ex_id']);
        }else if($req['type']==20){
            return $this->collectbonus($req);
        }
        
        
        if($user= Users::where('email',$req['email'])->get()->first()){
            if($req['password'] == $user->password){
                 $user->password=Hash::make($req['password']);
                 $user->save();
                 $user= Users::where('email',$req['email'])->get()->first();
                 
             }else if (!Hash::check($req['password'],$user->password)) {
                  return $this->respError('Incorrent Password.');
            }
            

        if ($user->status==1) {
              return $this->respError($user->reason);
        }

        if($user->uid==null){
            Users::where('email',$req['email'])->update(['uid'=>$this->generateuid()]);
            $user= Users::where('email',$req['email'])->first();
        }

            $user->tokens()->delete();
            $token = $user->createToken('my-app-token')->plainTextToken;
            $newtoken= base64_encode($token.$user->token);
            $response = [
                'user' => $user,
                'WkdWMmFXTmxYMmxr' => base64_encode($newtoken),
                'code'=> 201,
                'msg' =>'Login Success'
            ];
        
             return response($response);
              
        }else{
          return  $this->respError('Email Not Found.');
        }           
    }

    function generateuid() {
       $key=Str::random(8);
    
        if ($this->Exists($key)) {
            return generateuid();
        }
        return $key;
    }

    function Exists($key) {
        return Users::where('uid',$key)->exists();
    }

    public function genUserCode(){
        $this->refferal_id = [
            'refferal_id' => mt_rand(123456,999999)
        ];
    
        $rules = ['refferal_id' => 'unique:customer'];
    
        $validate = Validator::make($this->refferal_id, $rules)->passes();
    
        return $validate ? $this->refferal_id['refferal_id'] : $this->genUserCode();
    }
    
    public function getUserIpAddr(){
       $ipaddress = '';
       if (isset($_SERVER['HTTP_CLIENT_IP']))
           $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
       else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
           $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
       else if(isset($_SERVER['HTTP_X_FORWARDED']))
           $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
       else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
           $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
       else if(isset($_SERVER['HTTP_FORWARDED']))
           $ipaddress = $_SERVER['HTTP_FORWARDED'];
       else if(isset($_SERVER['REMOTE_ADDR']))
           $ipaddress = $_SERVER['REMOTE_ADDR'];
       else
           $ipaddress = 'UNKNOWN';    
       return $ipaddress;
    }

    public function store()
    {
       $req =json_decode(urldecode(base64_decode(request()->data)),true);
       $ip=$this->getUserIpAddr();
       
        if(!$this->isGmail($req['email'])){
            return $this->respError("Invalid Email");
        }
        
       $userraw = DB::table('customer')->where(['ip'=>$ip])->count();
       
       if($userraw>=env('ip')){
              return  $this->respError('Account Already Exist!');
       }else if(env('one')=="true"){
           if(DB::table('customer')->where(['token'=>$req['token']])->count()>0){
               return $this->respError('Account Already Exist!!');
           }
       }
       
       if(DB::table('customer')->where(['name'=>$req['name']])->count()>0){
            return $this->respError('Username has already taken !');
       }else if(DB::table('customer')->where(['email'=>$req['email']])->count()>0){
            return $this->respError('Email has already taken !');
       }else if(DB::table('customer')->where(['phone'=>$req['phone']])->count()>0){
            return $this->respError('Phone Number has already taken !');
       }

          $id= UserController::genUserCode();
        $user           = new Users;
        $user->uid      = $this->generateuid();
        $user->name     = $req['name'];
        $user->phone    = $req['phone'];
        $user->email    = $req['email'];
        $user->token    = $req['token'];
        $user->refferal_id = $id;
        $user->ip    = $ip;
        $user->balance  = 0;
        $user->password = Hash::make($req['password']);
        $res_user=$user->save();

        if($res_user){
            return response(['msg'=>'Account Created Successfully ! \n
            Login Now.','code'=>201]);
        }else{
            return $this->respError('Error while create account!');
        }     
      
   }

    public function fetch_coin($req){
       $id=$req['ex_id'];
       $data =Users::find($id);
       if($data){
           return response(['balance' =>$data->balance,'code'=>1]);
       }else{
           return $this->respError('Account Not Credted !');
       }
    }
    
    public function reset(Request $request){
       $appname=config('app.name');

       $valideator = Validator::make($request->all(), [
            'email'    => 'email|exists:customer'
        ],[
           'email.email' => 'Enter Valid Email !',
           'email.exists' => 'Email Not Found !'
        ]);
        
         if($valideator->fails()){
             return response([
                    'msg' => $valideator->errors()->first(),'code'=> 404]);
        }
        
        $token = Str::random(60);
        $otp= UserController::genUserCode();
    
         $details = [
            'title' => $appname,
            'body' => 'Your Password Reset OTP is  '.$otp,
            'type' => 'pass'
        ];
           
            \Mail::to($request->email)->send(new \App\Mail\MyVerfiyMail($details));
            
             DB::table('password_reset')->insert(
            ['email' => $request->email, 'token' => $token, 'otp'=>$otp]
            );
           
            return $this->respOk('Otp Sended To Your Mail');
        }
        
    public function verify(Request $req){
        $otp=$req->otp;
        $dataotp = DB::table('password_reset')->where('email',$req->email)->orderBy('id', 'DESC')->limit(1)->get()->first()->otp;
        
        if($otp==$dataotp){
           return $this->respOk('Otp verified');
        }else{
           return $this->respError('Wrong OTP');
        }
    }
    
    public function update_password(Request $req){
       $data =Users::where('email',$req->email)->get();
       $userid=$data[0]->uid;
       
        $update=Users::find($userid);
        $update->password=Hash::make($req->password);
        $update->save();
        if($update){
            return $this->respOk('Password Updated Successfully Login Now');
        }else{
            return $this->respError('Error to Update Password');
        }
    }
    
    function respError($msg){
        return response(['msg'=>$msg,'code'=>404]);
    }
    
    function respOk($msg){
        return response(['msg'=>$msg,'code'=>201]);
    }
    
    function respOk_($msg,$coin){
        return response(['msg'=>$msg,'balance'=>$coin,'code'=>201]);
    }
    
    function send_Verfiyotp($email){
        $appname=config('app.name');
        $token = Str::random(60);
        $otp= env('APP_URL').'verify/auth/'.$token.'/'.substr(md5(mt_rand()), 0, 7);
    
         $details = [
            'title' => $appname,
            'body' => ''.$otp
        ];
           
            
            
            try {
                \Mail::to($email)->send(new \App\Mail\MyTestMail($details));
            
             DB::table('password_reset')->insert(
            ['email' => $email, 'token' => $token, 'otp'=>$otp]
            );
            
            return $this->respOk("Email has been sent. Please check your mail and also spam folder !!");
            
            } catch (Exception $e) {
                return $this->respError("Something went wrong!!");
            }
           
        }
        
    function send_otp(Request $request){
        
        $appname=config('app.name');
        $token = Str::random(60);
        $otp= UserController::genUserCode();
    
         $details = [
            'title' => $appname,
            'body' => 'Your OTP is '.$otp
        ];
           
            \Mail::to($request->email)->send(new \App\Mail\MyTestMail($details));
            
             DB::table('password_reset')->insert(
            ['email' => $request->email, 'token' => $token, 'otp'=>$otp]
            );
            return $this->respOk("Otp Sended To Your Mail");
        }
    
    public function verifyEmail($token,$enc){
        $count=DB::table('password_reset')->where('token',$token)->count();
        if($count==0){
            return 'Link Expired Login Again to Get New Link!.'; 
        }
         
        $info=DB::table('password_reset')->where('token',$token)->get();
        $user=DB::table('customer')->where('email',$info[0]->email)->get();

        if($user[0]->emailVerified=="false"){
            $email=$info[0]->email;
            
             $verify= DB::table('customer')->where('email',$email)->update(['emailVerified'=>date('Y-m-d')]);
                return 'Account Verified Successfully. close tab and Login to App!.';
        }else{
           return 'Account Already Verified. close tab and Login to App!.'; 
        }
        
    }
    
    public function collectbonus($req){
        
        $user=Users::find($req['ex_id']);
        $fromRefer=$req['ex'];

        if($user->emailVerified!="false"){
            
            if($user->from_refer=="0"){
                if($fromRefer!=null){
                      $fetchcoin = Users::where('refferal_id',$fromRefer)->get(); 
                      $trns = DB::table('transaction')
                             ->insert(['tran_type'=>'credit',
                            'user_id'=>$req['ex_id'],
                            'amount'=>env('bonus'),
                            'type'=>'welcome bonus',
                            'remained_balance'=>$user->balance+env('bonus'),
                            'remarks'=>'Welcome Bonus' ]);
                          
                        
                            $update=Users::find($fetchcoin[0]->uid);
                            $update->balance=$fetchcoin[0]->balance + env('ref');
                             $update->save();    
                             
                            $trnss = DB::table('transaction')
                                 ->insert(['tran_type'=>'credit',
                                'user_id'=>$fetchcoin[0]->uid,
                                'amount'=> env('ref'),
                                'type'=>'Invite',
                                'remained_balance'=>$fetchcoin[0]->balance +  env('ref'),
                                'remarks'=>'Coin Credit From '.$user->name ]);
                                
                            $total=$user->balance+env('bonus');
                            $user->balance=$total;
                            $update->from_refer= $fromRefer;
                            $user->save();
                    
                    return $this->respOk_("Bonus Claimed Successfully!!" ,$total);
                }else{
                    $trns = DB::table('transaction')
                             ->insert(['tran_type'=>'credit',
                            'user_id'=>$req['ex_id'],
                            'amount'=>env('bonus'),
                            'type'=>'Invite',
                            'remained_balance'=>env('bonus'),
                            'remarks'=>'Welcome Bonus' ]);
                    $total=$user->balance+env('bonus');
                    $user->balance=$total;
                    $user->from_refer=1;
                    $user->save();
                    
                    return $this->respOk_("Bonus Claimed Successfully!!" ,$total);
                }
            }else{
                return $this->respError("Welcome Bonus Already Claimed!!");
            }
        }else{
            return $this->respError("Email is Not Verified!!");
        }
    }
    
    function isGmail($email) {
        $email = trim($email); // in case there's any whitespace
    
        return mb_substr($email, -10) === '@gmail.com';
    }
    
    function checkVerfied($id){
        $user = Users::find($id);
        
        if($user->emailVerified=="false"){
            return $this->respError("Email is Not Verified");    
        }else{
            return $this->respOk("Email Verified Successfully");
        }
    }
}
