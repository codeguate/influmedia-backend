<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\NissanUsers;
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;
use Google\Spreadsheet\SpreadsheetService;
use DB;
use Google_Client;
use Google_Service_Drive;
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
                
                DB::beginTransaction();
                
                $newObject = new NissanUsers();
                $newObject->nombres            = $request->get('nombre');
                $newObject->telefono            = $request->get('telefono');
                $newObject->email            = $request->get('email');
                $newObject->save();
                $nombreSpreahSheet = "Nissan_Smartdsmedia";
                // Nombre de hoja de cálculo
                $hojaCalculo = "Registros";
                // Aqui deben agregar el Path del archivo json.
                $pathJson = "nissanform-678e0c4a47fc.json";
                
                // Inicializamos Google Client
                $client = new Google_Client();
                $client->setAuthConfig($pathJson);
                $client->addScope(Google_Service_Drive::DRIVE);
                
                // si expiro el access token generamos otro
                if($client->isAccessTokenExpired()) {
                $client->fetchAccessTokenWithAssertion();
                }
                
                // Obtenemos el access token
                $obj_token = $client->getAccessToken();
                $accessToken = $obj_token['access_token'];
                
                // Inicializamos google-spreadsheet-client
                $serviceRequest = new DefaultServiceRequest($accessToken);
                ServiceRequestFactory::setInstance($serviceRequest);
                
                //Obtenemos los Spreadsheets disponibles para las credenciales actuales
                $spreadsheetService = new SpreadsheetService();
                $spreadsheetFeed = $spreadsheetService->getSpreadsheetFeed();
                
                // Obtenemos la spreadsheet por su nombre
                $spreadsheet = $spreadsheetFeed->getByTitle($nombreSpreahSheet);
                
                // Obtenemos las hojas de cálculo de la spreadsheet obetenida
                $worksheetFeed = $spreadsheet->getWorksheets();
                
                // Obtenemos la hoja de cálculo por su nombre
                $worksheet = $worksheetFeed->getByTitle($hojaCalculo);
                $listFeed = $worksheet->getListFeed();
                
                /*
                * Array de datos a agregar.
                * Observar que el valor de la claves del array que representan los encabezados
                * de las columnas van en minúsculas, en vez de Email sería email.
                * Esto es porque los encabezados de columna deben coincidir exactamente
                * con lo que fue devuelto por la API de Google y no por lo que se ve en Google Drive.
                */
                
                $dataAgregar = array('nombre' => $newObject->nombres,
                'email' => $newObject->email,
                'telefono' => $newObject->telefono
                );
                
                // Agregar datos
                try{
                    DB::commit();
                    $listFeed->insert($dataAgregar);
                    return Response::json($newObject, 200);

                } catch (Exception $e) {
                    $returnData = array (
                        'status' => 500,
                        'message' => $e->getMessage()
                    );
                    
                    DB::rollback();
                    return Response::json($returnData, 500);
                }
    
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
