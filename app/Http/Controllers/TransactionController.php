<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     
    public $uid;

    public function index()
    {
      return view('transaction');
    }

    public function getTransacitonList(){
      $data = DB::table('transaction')
      ->join('customer', 'customer.cust_id', '=', 'transaction.user_id')
      ->select('customer.uid', '=', 'transaction.user_id')
      ->select('transaction.*', 'customer.name')
      ->orderBy('transaction.id','DESC');
      
      return Datatables::queryBuilder($data)
      ->addIndexColumn()
      ->addColumn('inserted_at', function($data){
             return date('d-m-Y', strtotime($data->inserted_at));
 
        })
        ->addColumn('tran_type', function($data){
            if($data->tran_type == 'credit'){
                return '<span class="badge badge-success m-1">Credit</span>';
            }else{
                return '<span class="badge badge-danger m-1">Debit</span>';  
            }
        })
        ->rawColumns(['DT_RowIndex','inserted_at','tran_type'])
        ->toJson();
}

    public function usertrack($id){
        return view('pages.track',['id'=>$id]);
    }

    public function getUserTransaciton($id){
    
    $data = DB::table('transaction')
      ->join('customer','customer.uid', '=', 'transaction.user_id')
      ->select('transaction.*', 'customer.name')
      ->where('transaction.user_id','=',$id)
      ->orWhere('transaction.user_id','=',Users::find($id)->get()->first()->cust_id)
      ->orderBy('transaction.id','DESC');
      
      return Datatables::queryBuilder($data)
        ->addIndexColumn()
        ->addColumn('inserted_at', function($data){
             return date('d-m-Y', strtotime($data->inserted_at));
 
        })
        ->addColumn('tran_type', function($data){
            if($data->tran_type == 'credit'){
                return '<span class="badge badge-success">Credit</span>';
            }else{
                return '<span class="badge badge-danger">Debit</span>';  
            }
        })
        ->rawColumns(['DT_RowIndex','inserted_at','tran_type'])
      ->toJson();
}

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
