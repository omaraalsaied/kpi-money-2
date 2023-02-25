<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DataTables;
use Image;
use File;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.game');
    }
    
     public function List(){
        $data=Game::get();

        return DataTables::of($data)
         ->addIndexColumn()
         ->addColumn('image',function($data){
             return '<img src="'.url('images/'.$data->image).'" alt="An image" height="100px">';
         })
         ->addColumn('action', function($data){
            return '<div class="table-actions">
                 <button type="button" class="btn btn-success edit-game" data-id="'.$data->id.'"  ><i class="ik ik-edit"></i>Edit</button>
                <button type="button" class="btn btn-danger remove-game" data-id="'.$data->id.'"  ><i class="ik ik-trash"></i>Delete</button>
              </div>';    
         })
         ->rawColumns(['DT_RowIndex','image','action'])      
         ->make(true);    
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
        $image = $request->icon;
        $filenameWithExt = $image->getClientOriginalName();
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
        $filename = preg_replace("/\s+/", '-', $filename);
        $extension = $image->getClientOriginalExtension();
        $fileNameToStore = $filename.'_'.time().'.'.$extension;
        $image_resize = Image::make($image->getRealPath());
        $image_resize->resize(200,200);
        $save= $image_resize->save('images/'.$fileNameToStore);

        if($save){
            $game= new Game;
            $game->link=$request->link;
            $game->title=$request->title;
            $game->image=$fileNameToStore;
            $res=$game->save();
                if($res){
                    return redirect('/games')->with('success', ' Added Successfully!');
                }else{
                    return redirect('/games')->with('error', 'Technical Error!');
                }
        }else{
            return redirect('/games')->with('error', 'Technical Error Image!');
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function show(Slider $slider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $id)
    {
       return $id;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        if(isset($request->icon))
        {
            $image = $request->icon;
            $filenameWithExt = $image->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $filename = preg_replace("/[^A-Za-z0-9 ]/", '', $filename);
            $filename = preg_replace("/\s+/", '-', $filename);
            $extension = $image->getClientOriginalExtension();
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            $image_resize = Image::make($image->getRealPath());
            $image_resize->resize(200,200);
            $save= $image_resize->save('images/'.$fileNameToStore);
             $icon=$fileNameToStore;
             $imagePath = public_path('images/'.$request->oldimage);
            if(File::exists($imagePath)){
                unlink($imagePath);
            }
        }
        else
        {
         $icon=$request->oldimage; 
        }
        
        $game= Game::find($request->id);
        $game->title=$request->title;
        $game->link=$request->link;
        $game->image=$icon;
        $res=$game->save();
            if($res){
                return redirect('/games')->with('success', 'Update Successfully!');
            }else{
                return redirect('/games')->with('error', 'Technical Error!');
            }  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Slider  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       Game::find($id)->delete();
                return 1;
    }
    
    public function action(Request $req)
    {
        $ids = $req->id;
        if($req->status=='enable'){
           $update =Slider::whereIn('id',explode(",",$ids))->update(array('status' =>0)); 
            if($update){
                return 1;
            }else{
                return "not updated";
            }
        }
        else if($req->status=='disable'){
            $update =Slider::whereIn('id',explode(",",$ids))->update(array('status' =>2)); 
            if($update){
                return 1;
            }else{
                return "not updated".$ids;
            }
        }else if($req->status=='delete'){
            $update =Slider::whereIn('id',explode(",",$ids))->delete();
            if($update){
                return 1;
            }else{
                return "not updated".$ids;
            }
        }
    }
}
