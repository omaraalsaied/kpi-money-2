<?php

namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;
use DataTables,Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('users');
    }
    
    public function bannedindex()
    {
       return view('pages.ban-users');
    }


    public function getUserList($status){
          $data  = Users::query()->where('status','=',$status)->orderBy('cust_id', 'DESC');

           return Datatables::eloquent($data)
           ->addIndexColumn()
           ->addColumn('inserted_at', function($data){
            return date('d-m-Y', strtotime($data->inserted_at));
            })
            ->addColumn('status', function($data){
                    $status = $data->status;
                    if($status ==0){
                        return '<span class="badge badge-success m-1 status" id="1" data-id="'.$data->uid.'">Enable</span>
                        ';
                    }else{
                        return '<span class="badge badge-danger m-1 status" id="0" data-id="'.$data->uid.'">Disable</span>';  
                    }
                })
                ->addColumn('action', function($data){
                        return '<div class="table-actions">
                        <a href="/user/track/'.$data->uid.'"><button type="button" data-id="'.$data->uid.'" class="btn btn-dark tr"><i class="ik ik-activity"></i>Track</button></a>
                        <button type="button" class="btn btn-danger remove-user" data-id="'.$data->uid.'" ><i class="ik ik-trash"></i>Delete</button>
                            </div>';
            
                })
                ->rawColumns(['DT_RowIndex','inserted_at','status','action'])
                 ->toJson();

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
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request)
    {
       $user = Users::find($request->id);
       $user->status=$request->status; 
       $user->reason=$request->reason; 
       $res= $user->save();
            if($res){
                return redirect('/users')->with('success','Account Status Updated !');
            }else{
                return redirect('/users')->with('error','technical Error !');
            }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Users::find($id)->delete();
        return 1;
    }
}
