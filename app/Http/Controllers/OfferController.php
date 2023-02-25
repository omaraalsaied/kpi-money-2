<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;
use Image;
use File;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.homeoffer');
    }
    
     public function List(){
        $data=Offer::query();

        return DataTables::eloquent($data)
          ->addColumn('DT_RowIndex', function($data){
             return '<input type="checkbox" class="sub_chk" value="'.$data->id.'" data-id="'.$data->id.'">';
             })
        ->addColumn('status', function($data){
             $status = $data->status;
                    if($status ==0){
                        return '<span class="badge badge-success m-1">Enabled</span>';
                    }else{
                        return '<span class="badge badge-danger m-1 status" id="0">Disabled</span>';  
                    }
             })     
         ->addColumn('action', function($data){
            return '';    
         })
         ->rawColumns(['DT_RowIndex','status','action'])      
         ->toJson();  
    }


    public function edit(Offer $id)
    {
       return $id;
    }

 
    public function update(Request $request, Offer $offer)
    {
       
        $offer= Offer::find($request->id);
        $offer->offer_title=$request->offer_title;
        $res=$offer->save();
            if($res){
                return redirect('/offer')->with('success', 'Update Successfully!');
            }else{
                return redirect('/offer')->with('error', 'Technical Error!');
            }  
    }

    public function action(Request $req)
    {
        $ids = $req->id;
        if($req->status=='enable'){
           $update =Offer::whereIn('id',explode(",",$ids))->update(array('status' =>0)); 
            if($update){
                return 1;
            }else{
                return "not updated";
            }
        }
        else if($req->status=='disable'){
            $update =Offer::whereIn('id',explode(",",$ids))->update(array('status' =>1)); 
            if($update){
                return 1;
            }else{
                return "not updated".$ids;
            }
        }
    }
}
