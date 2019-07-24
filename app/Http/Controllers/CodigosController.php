<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Codigos;
use Response;
use Validator;
class CodigosController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return Response::json(Codigos::all(), 200);
    }
    
    public function getThisByFilter(Request $request, $id,$state)
    {
        if($request->get('filter')){
            switch ($request->get('filter')) {
                case 'codigo':{
                    $objectSee = Codigos::whereRaw('codigo=?',[$state])->get();
                    break;
                }
                case 'activa':{
                    $objectSee = Codigos::whereRaw('activa=?',[$state])->get();
                    break;
                }
                default:{
                    $objectSee = Codigos::all();
                    break;
                }
    
            }
        }else{
            $objectSee = Codigos::allt();
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
    

    public function marcar(Request $request, $id)
    {
        $objectUpdate = Codigos::whereRaw('codigo=?',$id)->first();
        if ($objectUpdate) {
            try {
                $objectUpdate->codigo = $request->get('codigo', $objectUpdate->codigo);
                $objectUpdate->vencimiento = $request->get('vencimiento', $objectUpdate->vencimiento);
                $objectUpdate->activa = $request->get('activa', $objectUpdate->activa);
                $objectUpdate->state = $request->get('state', $objectUpdate->state);
                $objectUpdate->asignado = $request->get('asignado', $objectUpdate->asignado);
    
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
        $validator = Validator::make($request->all(), [
            'codigo'          => 'required',
        ]);
        if ( $validator->fails() ) {
            $returnData = array (
                'status' => 400,
                'message' => 'Invalid Parameters',
                'validator' => $validator
            );
            return Response::json($returnData, 400);
        }
        else {
            try {
                $newObject = new Codigos();
                $newObject->codigo            = $request->get('codigo');
                $newObject->vencimiento            = $request->get('vencimiento');
                $newObject->activa            = $request->get('activa');
                $newObject->state            = $request->get('state');
                $newObject->asignado            = $request->get('asignado');
                $newObject->save();
                return Response::json($newObject, 200);
    
            } catch (Exception $e) {
                $returnData = array (
                    'status' => 500,
                    'message' => $e->getMessage()
                );
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
        $objectSee = Codigos::find($id);
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
        $objectUpdate = Codigos::find($id);
        if ($objectUpdate) {
            try {
                $objectUpdate->codigo = $request->get('codigo', $objectUpdate->codigo);
                $objectUpdate->vencimiento = $request->get('vencimiento', $objectUpdate->vencimiento);
                $objectUpdate->activa = $request->get('activa', $objectUpdate->activa);
                $objectUpdate->state = $request->get('state', $objectUpdate->state);
                $objectUpdate->asignado = $request->get('asignado', $objectUpdate->asignado);
    
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
        $objectDelete = Codigos::find($id);
        if ($objectDelete) {
            try {
                Codigos::destroy($id);
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
