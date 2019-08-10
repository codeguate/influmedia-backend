<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\NissanUsers;
use GuzzleHttp\Client;
use Sheets;
use DB;
use Response;
use Validator;
class NissanUsersController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return Response::json(NissanUsers::all(), 200);
    }
    
    public function getThisByFilter(Request $request, $id,$state)
    {
        if($request->get('filter')){
            switch ($request->get('filter')) {
                case 'state':{
                    $objectSee = NissanUsers::whereRaw('user=? and state=?',[$id,$state])->with('user')->get();
                    break;
                }
                case 'type':{
                    $objectSee = NissanUsers::whereRaw('user=? and tipo=?',[$id,$state])->with('user')->get();
                    break;
                }
                default:{
                    $objectSee = NissanUsers::whereRaw('user=? and state=?',[$id,$state])->with('user')->get();
                    break;
                }
    
            }
        }else{
            $objectSee = NissanUsers::whereRaw('user=? and state=?',[$id,$state])->with('user')->get();
        }
    
        if ($objectSee) {
            return Response::json($objectSee, 200);
    
        }
        else {
            $returnData = array (
                'status' => 404,
                'message' => 'No record found'
            );
            return Response::json($returnData, 404);
        }
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
         {
            try {
                // $rows = Sheets::spreadsheet('1CQ7noAv4qW95M918l_3cSFNHjHgqcBcDqsd_T0Qx7uw')->sheet('Nissan_Smartdsmedia')->insert(array(
                //     "name" => "John",
                //     "age" => 23,
                //     "email" => "john@example.com"
                // ));
                // return $rows;
                
                DB::beginTransaction();
                
                $newObject = new NissanUsers();
                $newObject->nombres            = $request->get('nombre');
                $newObject->telefono            = $request->get('telefono');
                $newObject->email            = $request->get('email');
                $newObject->save();
                $URL ="https://www.somosinflumedia.com/sheets/setDataSheet.php?nombre=".$request->get('nombre')."&email=".$request->get('email')."&telefono=".$request->get('telefono')."";
                // $result = Laracurl::get($URL, 'GET'); 
                $result = \Illuminate\Http\Request::create($URL, 'POST', ['nombre' => $request->get('nombre'), 'email' => $request->get('email'), 'telefono' => $request->get('telefono')]); 
                DB::commit();
                  
                return Response::json($result, 200);
                
    
            } catch (Exception $e) {
                $returnData = array (
                    'status' => 500,
                    'message' => $e->getMessage()
                );
                DB::rollback();
                return Response::json($returnData, 500);
            }
        }
    }
    
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $objectSee = NissanUsers::find($id);
        if ($objectSee) {
            return Response::json($objectSee, 200);
    
        }
        else {
            $returnData = array (
                'status' => 404,
                'message' => 'No record found'
            );
            return Response::json($returnData, 404);
        }
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        //
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        $objectUpdate = NissanUsers::find($id);
        if ($objectUpdate) {
            try {
                $objectUpdate->nombres = $request->get('nombre', $objectUpdate->nombre);
                $objectUpdate->telefono = $request->get('telefono', $objectUpdate->telefono);
                $objectUpdate->email = $request->get('email', $objectUpdate->email);
    
                $objectUpdate->save();
                return Response::json($objectUpdate, 200);
            } catch (Exception $e) {
                $returnData = array (
                    'status' => 500,
                    'message' => $e->getMessage()
                );
                return Response::json($returnData, 500);
            }
        }
        else {
            $returnData = array (
                'status' => 404,
                'message' => 'No record found'
            );
            return Response::json($returnData, 404);
        }
    }
    
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $objectDelete = NissanUsers::find($id);
        if ($objectDelete) {
            try {
                NissanUsers::destroy($id);
                return Response::json($objectDelete, 200);
            } catch (Exception $e) {
                $returnData = array (
                    'status' => 500,
                    'message' => $e->getMessage()
                );
                return Response::json($returnData, 500);
            }
        }
        else {
            $returnData = array (
                'status' => 404,
                'message' => 'No record found'
            );
            return Response::json($returnData, 404);
        }
    }
}
