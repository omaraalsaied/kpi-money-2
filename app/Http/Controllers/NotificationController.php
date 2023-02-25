<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;

class NotificationController extends Controller
{

   public function new(Request $request){
    #API access key from Google API's Console
    define( 'API_ACCESS_KEY',env('FIRBASE_KEY'));

#prep the bundle
     $msg = array
          (
		'body' 	=> $request->message,
		'title'	=> $request->title
            
          );

	$fields = array
			(
				'to'		=> '/topics/all',
				'notification'	=> $msg
			);
	
	
	$headers = array
			(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
			);

#Send Reponse To FireBase Server	
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );

         if($result){
             return redirect('/notification')->with('success','Notification Send Successfully');
         }else{
            return redirect('/notification')->with('error','Technical Error');     
         }   

   }
}
