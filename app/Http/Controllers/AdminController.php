<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\Users;
use App\Models\Video;
use App\Models\Weblink;
use App\Models\Redeem;
use App\Models\Apps;
use DataTables,Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AdminController extends Controller
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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(env('API_KEY')==null){
            $this->updateData('API_KEY',Str::random(35));
        }
        
        $now = Carbon::now();
        $user=  Users::count();   
        $apps=  Apps::count();   
        $redeem=  Redeem::count();   
        $video=  Video::count();   
        $weblink=  Weblink::count();   
        $pending=DB::table('recharge_request')->where('status','Pending')->count();
        $transaction=DB::table('transaction')->count();
        $complete=DB::table('recharge_request')->where('status','Success')->count();
        $today=DB::table('personal_access_tokens')->wheredate('created_at',$now)->count();
        $week=DB::table('personal_access_tokens')->where('created_at', '>', Carbon::now()->startOfWeek())->where('created_at', '<', Carbon::now()->endOfWeek())->count();
        $month=DB::table('personal_access_tokens')
                   ->whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'))
                    ->get(['name','created_at'])
                    ->count();
        return view('pages.dashboard',
            ['user'=>$user,
            'apps'=>$apps,
            'redeem'=>$redeem,
            'video'=>$video,
            'weblink'=>$weblink,
            'pending'=>$pending,
            'transaction'=>$transaction,
            'today'=>$today,
            'week'=>$week,
            'month'=>$month,
            'complete'=>$complete]);
    }

   
    public function admin(){
        $data= User::find(2);
        return view('pages.admin',['data'=>$data]);
    }
    
    public static function updateData($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {

            file_put_contents($path, str_replace(
                $key . '=' . env($key), $key . '=' . $value, file_get_contents($path)
            ));
        }
        
        // \Artisan::call('config:cache');
        \Artisan::call('config:clear');
    }
    
    public function update(Request $req){
        if($req->type=="server"){
            $this->updateData('API_URL',$req->API_URL);
            return redirect ('/admin-profile')->with('success','Update Successfully !!');    
    
        }else{
            $user= User::find(2);
              
             if (!$user || !Hash::check($req->oldpass,$user->password)) {
                return redirect ('/admin-profile')->with('error','Old Password Not Matched !!');    
            }else{
                if($req->newpas == $req->cnpas){
                    $admin =User::find(2);
                    $admin->email=$req->email;
                    $admin->password=Hash::make($req->newpas);
                    $res=$admin->save();
                        if($res){
                            return redirect ('/admin-profile')->with('success','Update Successfully !!');    
                        }else{
                            return redirect ('/admin-profile')->with('error','Error While Update Data !!');      
                        }    
                }else{
                    return redirect ('/admin-profile')->with('error','New Password and Confirm Password Not Matched !!');       
                }    
            }
        }
        
    }

    public function verify(Request $req)
    {
        $licence = base64_encode($req->licence);
        $package= base64_encode($req->package);
        
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL,base64_decode('aHR0cHM6Ly9saWNlbmNlLnRlY2huaWNhbHN1bWVyLmNvbS9nZXRkYXRhbmFtZS5waHA/Y3BuPQ==').$req->licence);
        curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        $query = curl_exec($curl_handle);
        $data = json_decode($query, true);
        curl_close($curl_handle);
        
        $pac= $data["lists"][0]["package"];
        $lice= $data["lists"][0]["purchase_code"];
            
            if($pac==$req->package && $lice==$req->licence){
                $admin = User::find(2);
                $admin->licence=$licence;
                $admin->package=$package;
                $res=$admin->save();
                    if($res){
                        return redirect ('/admin-profile')->with('success','Licence Verified Successfully !!');    
                    }else{
                        return redirect ('/admin-profile')->with('error','Error While Update Data !!');      
                    }   
            }else{
                return redirect ('/admin-profile')->with('error','Licence Details Not Found!!');         
            }
        }


    }