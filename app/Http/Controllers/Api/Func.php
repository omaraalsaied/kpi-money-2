<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Users;
use App\Models\TaskApi;
use App\Models\Video;
use App\Models\Apps;
use App\Models\Weblink;
use App\User;
use Carbon\Carbon;

class Func extends Controller
{

    public function initCr(Request $req){
        $req =json_decode(urldecode(base64_decode(request()->data)),true);
        $uid=$req['ex_id'];
        $taskId=$req['id'];
        $type=$req['type'];
        $date=Carbon::now();
        $count=0;
        
        $key=$req['i3'];
        $salt=$req['i4'];
        if($salt=="" || $key==""){
            return $this->respError("Something went wrong!");      
        }
        if($key!= md5(env('API_KEY').$salt)){
            return $this->respError("Something went wrong!!" );      
        }

        switch ($type) {
            case 2:
                return $this->redeem($req);
                break;
            
            case 3:
                return $this->daily($uid);
                break;
                
            case 4:
                return $this->checkSpin($uid,$taskId);
                break;    
                
            case 5:
                return $this->aps($uid);
                break;
                
            case 6:
                return $this->vdo($uid);
                break;   
                
            case 7:
                return $this->web($uid);
                break;   
                
            case 8:
                return $this->spn($uid,$taskId,$req['ex']);
                break; 
            
            case 9:
                return $this->crWeb($uid,$taskId);
                break; 
                
            case 10:
                return $this->crVid($uid,$taskId);
                break; 
                
            case 11:
                return $this->crApp($uid,$taskId);
                break;     
                
            case 12:
                return $this->history($uid);
                break;   
                
            case 13:
                return $this->Rewardhistory($uid);
                break;       
            
            case 14:
                return $this->crGame($uid,$taskId);
                break;
            case 16:
                return $this->removeUser($uid);
                break;        
        }
    }
    
    
    public function daily($uid){
        $user=Users::find($uid);
        $user=$this->checkTaskStatus($user);
        if($this->itsToday($user->dcheck)){
            $user->dcheck=date('Y-m-d');
            $total=$user->balance+env('daily');
            $user->balance=$total;
            $this->insActivity('daily',env('daily'),$total,$uid);
            $user->save();
            return $this->respOk(env('daily')." Bonus Received",$total);
        }else{
            return $this->respError("Today Daily Bonus Already Claimed");
        }

    }
    
    function checkSpin($uid,$taskId){
        $user=Users::find($uid);
        $user=$this->checkTaskStatus($user);
        if($taskId==1){
           if($user->spn<env('spn')){
                return $this->respSpinOk(($user->spn-env('spn')),env('spn'));
            }else{
                return $this->respError('Today Spin Limit Completed');
            }  
        }else{
            if($user->math<env('mathLimit')){
                return $this->respSpinOk(($user->math-env('mathLimit')),env('mathLimit'));
            }else{
                return $this->respError('Today Quiz Limit Completed');
            }   
        }
         
        
    }
    
    function spn($uid,$taskId,$type){
        $user=Users::find($uid);
        if($type==1){
            if($user->spn < env('spn')){
                $user->spn+=+1;
                $total=$user->balance+$taskId;
                $user->balance=$total;
                $this->insActivity('spn',$taskId,$total,$uid);
                $user->save();
                
                return $this->respOk($taskId." Bonus Received",$total);
            }else{
               return $this->respError("Today No Spin Left"); 
            }
        }else{
            if($user->math < env('mathLimit')){
                $user->math+=+1;
                $total=$user->balance+env('mathCoin');
                $user->balance=$total;
                $this->insActivity('math',$taskId,$total,$uid);
                $user->save();
                
                return $this->respOk(env('mathCoin')." Bonus Received",$total);
            }else{
               return $this->respError("Today No Quiz Left"); 
            }
        }
        
        
    }
    
    function insActivity($type,$coin,$total,$id){
        $msg="";
        switch ($type) {
            case 'daily':
                 $msg="Daily Checkin";
                 $remark="Daily checkin Bonus claimed";
                break;
                
            case 'spn':
                 $msg="Lucky Spin";
                 $remark="Lucky Spin Bonus";
                break;
                
            case 'video':
                 $msg="Video Tutorial";
                 $remark="Video Tutorial Watched";
                 break;
            
            case 'web':
                 $msg="Article";
                 $remark="Article Read Bonus";
                break; 
                
            case 'app':
                 $msg="Offers";
                 $remark="Offers Completed Bonus";
                break; 
             
            case 'redeem':
                 $msg="Redeem";
                 $remark="Coin Withdrawal!!";
                break;
                
            case 'game':
                 $msg="Game";
                 $remark="Game Bonus!";
                break;
            case 'math':
                 $msg="Quiz";
                 $remark="Math Quiz Bonus!";
                break;    
          
        }
        
        
        DB::table('transaction')->insert([
          'tran_type'=>'credit',
          'user_id'=>$id,
          'amount'=>$coin,
          'type'=>$msg,
          'remained_balance'=>$total,
          'remarks'=>$remark 
        ]);
        
    }

    function respOk($msg,$coin){
        return response(['msg'=>$msg, 'balance'=>$coin, 'code'=>201]);
    }
    
    function respError($msg){
        return response(['msg'=>$msg, 'code'=>400 ]);
    }
    
    function itsToday($to){
        if($to==date('Y-m-d')){
            return false;
        }else{
           return true;
        }
    }
    
    function checkTaskStatus($user){
        if($user->td==date('Y-m-d')){
           return $user;
        }else{
            $user->spn=0;  
            $user->web=0;  
            $user->app=0;  
            $user->video=0;  
            $user->td=date('Y-m-d');
            $user->save();
          
            return $user;
        }
        
    }
    
    function web($uid){
        $user=Users::find($uid);
        $user=$this->checkTaskStatus($user);
        $avil=env('web')-$user->web; 
        if($avil<0){ return []; }
            $data= DB::select('Select weblink.id,
                            title,
                            url,
                            status,
                            point,
                            timer 
                            from weblink 
                        left outer join task on 
                        task.user_id =:ids 
                        and 
                        weblink.id = task.task_id
                        and 
                        task.type=3
                        where
                        task.task_id is NULL
                        and 
                        weblink.status=0 
                        ORDER BY weblink.id DESC limit :lim', ['ids' => $uid,'lim'=>$avil]);
        return $data;
    }
    
    function vdo($uid){
        $user=Users::find($uid);
        $user=$this->checkTaskStatus($user);
        $avil=env('video')-$user->video; 
        
        if($avil<0){ return []; }
        $data= DB::select('Select youtube_video.id,youtube_video.video_id,title,timer,status,point from youtube_video left outer join task on task.user_id =:ids and youtube_video.id = task.task_id and task.type=1 where task.task_id is NULL and status=0 ORDER BY youtube_video.id DESC limit :lim', ['ids' => $uid,'lim'=>$avil]);
         
         return $data;  
          
    }
    
    function aps($uid){
        $user=Users::find($uid);
        $user=$this->checkTaskStatus($user);
        $avil=env('app')-$user->app;
        
        if($avil<0){ return []; }
        $data= DB::select('Select appsname.id,
                            app_name,
                            image,
                            points,
                            url,
                            status,
                            appurl,
                            details
                            from appsname 
                        left outer join task on
                        task.user_id =:ids
                        and
                        appsname.id = task.task_id 
                        and 
                        task.type=5 
                        where 
                        task.task_id is NULL 
                        and 
                        status=0 ORDER BY appsname.id DESC limit :lim', ['ids' => $uid,'lim'=>$avil]);
        return $data;                
     }
     
    function checkTask(Users $user,$type){
      if($this->itsToday($user->td)){
          switch($type){
              case 'web':
                if($user->web>=env('web')){
                    return $this->respError('Today Task Limit Completed');
                }
                break;
             case 'video':
                if($user->video>=env('video')){
                    return $this->respError('Today Task Limit Completed');
                }
                break;  
                
            case 'app':
                if($user->app>=env('app')){
                    return $this->respError('Today Task Limit Completed');
                }
                break;       
          }
            
        } 
    } 

    function crGame($uid,$taskId){
        if($this->countTask(6,$taskId,$uid,date('Y-m-d'))==0){
            $user=Users::find($uid);
            $total=$user->balance+env('game');
            $user->balance=$total;
            $this->insActivity('game',$taskId,$total,$uid);
            $user->save();
            $this->taskLog(6,$taskId,$uid);
            return $this->respOk(env('game')." Bonus Received",$total);
        }else{
           return $this->respError("This Game Point Already Claimed Today"); 
        }
    }
    
    function crWeb($uid,$taskId){
        $user=Users::find($uid);
        $this->checkTask($user,'web');
        if($user->web < env('web')){
            $web=Weblink::find($taskId);
            $coin=$web->point;
            $user->web+=+1;
            $total=$user->balance+$coin;
            $user->balance=$total;
            $this->insActivity('web',$taskId,$total,$uid);
            $user->save();
            $web->views+=+1;
            $web->save();
            $this->taskLog(4,$taskId,$uid);
            return $this->respOk($coin." Bonus Received",$total);
        }else{
           return $this->respError("Today No Task Left"); 
        }
    }
    
    function crVid($uid,$taskId){
        $user=Users::find($uid);
        $this->checkTask($user,'video');
        if($user->video < env('video')){
            $vid=Video::find($taskId);
            $coin=$vid->point;
            $user->video+=+1;
            $total=$user->balance+$coin;
            $user->balance=$total;
            $this->insActivity('video',$taskId,$total,$uid);
            $user->save();
            $vid->views+=+1;
            $vid->save();
            $this->taskLog(1,$taskId,$uid);
            return $this->respOk($coin." Bonus Received",$total);
        }else{
           return $this->respError("Today No Task Left"); 
        }
    }
    
    function crApp($uid,$taskId){
        $user=Users::find($uid);
        $this->checkTask($user,'app');
        if($user->app < env('app')){
            if($this->countTask(5,$taskId,$uid,null)>0){
                return $this->respError("Bonus Already claimed");
            }
            $ofr=Apps::find($taskId);
            $coin=$ofr->points;
            $user->app+=+1;
            $total=$user->balance+$coin;
            $user->balance=$total;
            $this->insActivity('app',$taskId,$total,$uid);
            $user->save();
            $ofr->views+=+1;
            $ofr->save();
            $this->taskLog(5,$taskId,$uid);
            return $this->respOk($coin." Bonus Received",$total);
        }else{
           return $this->respError("Today No Task Left"); 
        }
    }
    
    function redeem($req){
        $uid=$req['ex_id'];
        $user=Users::find($uid);
        $currentcoin= $user->balance; 
        
        if($currentcoin >= $req['require']){
            $total= $currentcoin-$req['require'];
            $user->balance=$total;
            $user->save();
            
           DB::table('recharge_request')
            ->insert(['mobile_no'   =>      $req['data'],
                'amount'            =>      $req['require'],
                'type'              =>      $req['Reqtype'],
                'status'            =>      'Pending',
                'user_id'           =>      $uid,
                'orginal_amount'    =>      $req['amount'],
                'detail'            =>      $req['detail'] ]);  
                    
            $this->insActivity("redeem",$req['require'],$total,$uid);
            return $this->respOk("Redeem Successfully !",$total);
        }else{
            return $this->respError("Not Enough Coin!!");
        }
    }
    
    function history($uid){
        $user=Users::find($uid);
        return $data= DB::select('SELECt amount,remarks,type,inserted_at,tran_type from transaction where user_id=:uid or user_id=:ogid order by id desc limit 30', ['uid' => $uid,'ogid'=>$user->cust_id]);
    }
    
    function Rewardhistory($uid){
        $user=Users::find($uid);
        return $data= DB::select('select * from recharge_request where user_id =:uid or user_id=:ogid order by request_id desc limit 10', ['uid' => $uid,'ogid'=>$user->cust_id]);
    }

    function respSpinOk($count,$limit){
        return response(['count'=>$count,'limit'=>$limit , 'code'=>201]);
    }
    
    function taskLog($type,$taskid,$uid){
        TaskApi::insert(['type'=>$type,'task_id'=>$taskid,'user_id'=>$uid]);
    }
    
    function countTask($type,$taskid,$uid,$date){
        if($date==null){
            return TaskApi::where(['type'=>$type,'task_id'=>$taskid,'user_id'=>$uid])->count();
        }else{
            return TaskApi::where(['type'=>$type,'task_id'=>$taskid,'user_id'=>$uid])->whereDate('created_at',Carbon::now())->count();
        }
    }
    
     public function abouts(){
       $data= DB::table('setting')->where('id',1)->get();
       $spin= DB::table('wheel_points')->get();
            if($data){
                return response(['data'=>$data,'spin'=>$spin,'success'=>1]);
            }else{
                return response(['data'=>'data not found!!','success'=>0]);
            }
   } 
   
     public function offers(){
    $data= DB::select('Select status  from offers');
        if($data){
            return response(['data'=>$data,'code'=>201]);
        }else{
            return response(['data'=>'data not found!!','code'=>404]);
        }
    } 
   
    public function games(){
    $data= DB::select('Select * from games');
        if($data){
            return response(['data'=>$data,'success'=>1]);  
        }else{
            return response(['message'=>'Data Not Found', 'success'=>0]);
        }
     }
     
     public function sldiebanner(){
        $data= DB::select('Select * from home_banner');
            if($data){
                return response(['data'=>$data,'success'=>1]);  
            }else{
                return response(['data'=>'Data Not Found', 'success'=>0]);
            }
     }


     public function fetch_rewards(){
        $data= DB::select('Select * from redeem where status=0');
            if($data){
                return response(['data'=>$data,'success'=>1]);  
            }else{
                return response(['message'=>'Data Not Found', 'success'=>0]);
            }
     }
     
     public function removeUser($uid){
         Users::where('uid',$uid)->delete();
         DB::table('transaction')->where('user_id',$uid)->delete();
         DB::table('task')->where('user_id',$uid)->delete();
         
         return $this->respOk("Data Delete Successfully");
     }
 
    
}