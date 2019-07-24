<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

use App\Http\Requests;
use App\Users;
use Faker\Factory as Faker;
use Response;
use Validator;
use Hash;
use PDF as PDF2;
use QrCode;
use DB;
use APIWHA\SDK\Factory;
use APIWHA\SDK\Message\Message as whMessage;
use APIWHA\SDK\Message\Image;
use APIWHA\SDK\Message\Audio;
use APIWHA\SDK\Message\PDF;
use Mpdf\Mpdf;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response::json(Users::with('roles')->get(), 200);
    }


    
    public function getThisByFilter(Request $request, $id,$state)
    {
        if($request->get('filter')){
            switch ($request->get('filter')) {
                case 'state':{
                    $objectSee = Users::whereRaw('state=?',[$state])->get();
                    break;
                }
                case 'email':{
                    $objectSee = Users::whereRaw('email=?',[$state])->get();
                    break;
                }
                case 'telefono':{
                    $objectSee = Users::whereRaw('telefono=?',[$state])->get();
                    break;
                }
                case 'dpi':{
                    $objectSee = Users::whereRaw('dpi=?',[$state])->get();
                    break;
                }
                case 'codigo':{
                    $objectSee = Users::whereRaw('codigo=?',[$state])->get();
                    break;
                }
                default:{
                    $objectSee = Users::all();
                    break;
                }
    
            }
        }else{
            $objectSee = Users::all();
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

    public function getUsersByRol($id)
    {
        return Response::json(Users::whereRaw('rol=?',$id)->with('roles')->get(), 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *'password'      => 'required|min:3|regex:/^.*(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!-,:-@]).*$/',
     */
     public function store(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'email'         => 'required|email',
             'dpi'         => 'required',
             'codigo'         => 'required'
         ]);
         
 
         if ($validator->fails()) {
             $returnData = array(
                 'status' => 400,
                 'message' => 'Invalid Parameters',
                 'validator' => $validator->messages()->toJson()
             );
             return Response::json($returnData, 400);
         }
         else {
             $email = $request->get('email');
             $email_exists  = Users::whereRaw("email = ?", $email)->count();
             $user = $request->get('username');
             $user_exists  = Users::whereRaw("username = ?", $user)->count();
             $telefono = $request->get('telefono');
             $telefono_exists  = Users::whereRaw("telefono = ?", $telefono)->count();
             $dpi = $request->get('dpi');
             $dpi_exists  = Users::whereRaw("dpi = ?", $dpi)->count();
             if($email_exists == 0 && $user_exists == 0 && $telefono_exists == 0 && $dpi_exists == 0){    
                     $newObject = new Users();
                     $newObject->username = $request->get('username');
                     $newObject->email = $email;
                     $newObject->password = Hash::make($request->get('password'));
                     $newObject->nombres = $request->get('nombres');
                     $newObject->apellidos = $request->get('apellidos');
                     $newObject->rol = $request->get('rol');
                     $newObject->nacimiento = $request->get('nacimiento');
                     $newObject->codigo = $request->get('codigo');
                     $newObject->descripcion = $request->get('descripcion', '');
                     $newObject->telefono = $telefono;
                     $newObject->dpi = $request->get('dpi', '');
                     $newObject->state = $request->get('state',1);
                     $newObject->save();
                     
                     $objectSee = Users::whereRaw('id=?',$newObject->id)->with('roles')->first();
                     if ($objectSee) {
                        $baseimagen = ImageCreateTrueColor(512,1106);
                        //Cargamos la primera imagen(cabecera)
                        if(file_exists("https://5bconectate.com/Asset/img/Invitacion-min.png")){
                            $logo = ImageCreateFromPng("https://5bconectate.com/Asset/img/Invitacion-min.png");

                        }else{
                            $logo = ImageCreateFromPng("https://5bconectate.com/Asset/img/Invitacion-min.png");

                        }
                        //Unimos la primera imagen con la imagen base
                        imagecopymerge($baseimagen, $logo, 0, 0, 0, 0, 512, 1106, 100);
                        //Cargamos la segunda imagen(cuerpo)
                        $ts_viewer = ImageCreateFromPng("https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=https://5bconectate.com/dashboard/verificacion.php?codigo=".$objectSee->codigo);
                        //Juntamos la segunda imagen con la imagen base
                        imagecopymerge($baseimagen, $ts_viewer, 110, 50, 0, 0, 300, 300, 100);
                        $img = new TextToImage;
                        $img->createImage(strtoupper($objectSee->nombres.' '.$objectSee->apellidos), 16, 300,60);
                        $img->saveAsPng($objectSee->nombres.'-'.$objectSee->apellidos.'-name','');
                        $textImg = ImageCreateFromPng("https://5bconectate.com/backend/public/".$objectSee->nombres."-".$objectSee->apellidos."-name.png");
                        imagecopymerge($baseimagen, $textImg, 110, 530, 0, 0, 300, 60, 100);
                        //Mostramos la imagen en el navegador
                        ImagePng($baseimagen,"".$objectSee->codigo."_salida.png",5);
                        //Limpiamos la memoria utilizada con las imagenes
                        ImageDestroy($logo);
                        ImageDestroy($ts_viewer);
                        ImageDestroy($baseimagen);
                        $url = "https://5bconectate.com/backend/public/"."".$objectSee->codigo."_salida.png";
                        Mail::send('emails.confirm', ['empresa' => 'Registro 5B', 'url' => 'https://www.JoseDanielRodriguez.com', 'app' => 'http://me.JoseDanielRodriguez.gt', 'password' => $request->get('password'), 'username' => $objectSee->username, 'codigo' => $objectSee->codigo,'email' => $objectSee->email,'imagen' => $url, 'name' => $objectSee->nombres.' '.$objectSee->apellidos,], function (Message $message) use ($objectSee){
                            $message->from('registro@5b.com.gt', 'Info Registro 5B')
                                    ->sender('registro@5b.com.gt', 'Info Registro 5B')
                                    ->to($objectSee->email, $objectSee->nombres.' '.$objectSee->apellidos)
                                    ->replyTo('registro@5b.com.gt', 'Info Registro 5B')
                                    ->subject('Comprobante');
                        
                        });
                            // $apiKey = 'SX1SLWK6MOYRZHBIGD1Y';
                            // $client = (new Factory)->create($apiKey);
                            // $number = $objectSee->telefono;
                            // $message = new Image($number, $url);
                            // $response = $client->send($message);
                        

                            return  Response::json($objectSee, 200);
                        }
                        else {
                            $returnData = array (
                                'status' => 404,
                                'message' => 'No record found'
                            );
                            return Response::json($returnData, 404);
                        }
             }else{
                $returnData = array(
                    'status' => 400,
                    'message' => 'User already exists',
                    'validator' => $validator->messages()->toJson()
                );
                return Response::json($returnData, 400);
             }
         }
     }
    public function makePDF($data){
        $viewPDF = view('emails.confirm', $data);
        $pdf = PDF2::loadHTML($viewPDF);
        return $pdf->stream('download.pdf');
    }
    public function sendEmail(Request $request){


        $objectSee = Users::whereRaw('id=?',$request->get('id'))->with('roles')->first();
                     if ($objectSee) {
                        $baseimagen = ImageCreateTrueColor(512,1106);
                        //Cargamos la primera imagen(cabecera)
                        if(file_exists("https://5bconectate.com/Asset/img/Invitacion-min.png")){
                            $logo = ImageCreateFromPng("https://5bconectate.com/Asset/img/Invitacion-min.png");

                        }else{
                            $logo = ImageCreateFromPng("https://5bconectate.com/Asset/img/Invitacion-min.png");

                        }
                        //Unimos la primera imagen con la imagen base
                        imagecopymerge($baseimagen, $logo, 0, 0, 0, 0, 512, 1106, 100);
                        //Cargamos la segunda imagen(cuerpo)
                        $ts_viewer = ImageCreateFromPng("https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=https://5bconectate.com/dashboard/verificacion.php?codigo=".$objectSee->codigo);
                        //Juntamos la segunda imagen con la imagen base
                        imagecopymerge($baseimagen, $ts_viewer, 110, 50, 0, 0, 300, 300, 100);
                        $img = new TextToImage;
                        $img->createImage(strtoupper($objectSee->nombres.' '.$objectSee->apellidos), 16, 300,60);
                        $img->saveAsPng($objectSee->nombres.'-'.$objectSee->apellidos.'-name','');
                        $textImg = ImageCreateFromPng("https://5bconectate.com/backend/public/".$objectSee->nombres."-".$objectSee->apellidos."-name.png");
                        imagecopymerge($baseimagen, $textImg, 110, 530, 0, 0, 300, 60, 100);
                        //Mostramos la imagen en el navegador
                        ImagePng($baseimagen,"".$objectSee->codigo."_salida.png",5);
                        //Limpiamos la memoria utilizada con las imagenes
                        ImageDestroy($logo);
                        ImageDestroy($ts_viewer);
                        ImageDestroy($baseimagen);
                        $url = "https://5bconectate.com/backend/public/"."".$objectSee->codigo."_salida.png";
                        Mail::send('emails.confirm', ['empresa' => 'Registro 5B', 'url' => 'https://www.JoseDanielRodriguez.com', 'app' => 'http://me.JoseDanielRodriguez.gt', 'password' => $request->get('password'), 'username' => $objectSee->username, 'codigo' => $objectSee->codigo,'email' => $objectSee->email,'imagen' => $url, 'name' => $objectSee->nombres.' '.$objectSee->apellidos,], function (Message $message) use ($objectSee){
                            $message->from('registro@5b.com.gt', 'Info Registro 5B')
                                    ->sender('registro@5b.com.gt', 'Info Registro 5B')
                                    ->to($objectSee->email, $objectSee->nombres.' '.$objectSee->apellidos)
                                    ->replyTo('registro@5b.com.gt', 'Info Registro 5B')
                                    ->subject('Comprobante');
                        
                        });
                    //     $apiKey = 'BT2VFMDLYHTIREKDQCF7';
                    // $client = (new Factory)->create($apiKey);
                    //         $number = $objectSee->telefono;
                    //         $message = new Image($number, $url);
                            // $pdf =    $this->makePDF(['empresa' => 'Registro 5B', 'url' => 'https://www.JoseDanielRodriguez.com', 'app' => 'http://me.JoseDanielRodriguez.gt', 'password' => $request->get('password'), 'username' => $objectSee->username, 'codigo' => $objectSee->codigo, 'email' => $objectSee->email, 'name' => $objectSee->nombres.' '.$objectSee->apellidos]);
                            // $message = new whMessage($number, "data:image/png;base64,".base64_encode(QrCode::format('png')->size(250)->generate($objectSee->codigo))."");
                            // $response = $client->send($message);
                        
                            
                            return  Response::json($objectSee, 200);
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
        $objectSee = Users::whereRaw('id=?',$id)->with('roles','comprados','referidos')->first();
        if ($objectSee) {
            return Response::json($objectSee, 200);
        }
        else {
            $returnData = array(
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
        $objectUpdate = Users::whereRaw('id=?',$id)->first();
        if ($objectUpdate) {
            try {
                $objectUpdate->username = $request->get('username', $objectUpdate->username);
                $objectUpdate->email = $request->get('email', $objectUpdate->email);
                $objectUpdate->nombres = $request->get('nombres', $objectUpdate->nombres);
                $objectUpdate->apellidos = $request->get('apellidos', $objectUpdate->apellidos);
                $objectUpdate->descripcion = $request->get('descripcion', $objectUpdate->descripcion);
                $objectUpdate->nacimiento = $request->get('nacimiento', $objectUpdate->nacimiento);
                $objectUpdate->state = $request->get('state', $objectUpdate->state);
                $objectUpdate->rol = $request->get('rol', $objectUpdate->rol);
                $objectUpdate->codigo = $request->get('codigo', $objectUpdate->codigo);
                $objectUpdate->save();
                $objectUpdate->roles;

                return Response::json($objectUpdate, 200);
            }catch (\Illuminate\Database\QueryException $e) {
                if($e->errorInfo[0] == '01000'){
                    $errorMessage = "Error Constraint";
                }  else {
                    $errorMessage = $e->getMessage();
                }
                $returnData = array (
                    'status' => 505,
                    'SQLState' => $e->errorInfo[0],
                    'message' => $errorMessage
                );
                return Response::json($returnData, 500);
            }
            catch (Exception $e) {
                $returnData = array(
                    'status' => 500,
                    'message' => $e->getMessage()
                );
            }
        }
        else {
            $returnData = array(
                'status' => 404,
                'message' => 'No record found'
            );
            return Response::json($returnData, 404);
        }
    }

    public function recoveryPassword(Request $request){
        $objectUpdate = Users::whereRaw('email=? or username=?',[$request->get('username'),$request->get('username')])->first();
        if ($objectUpdate) {
            try {
                $faker = Faker::create();
                // $pass = $faker->password(8,15,true,true);
                $pass = $faker->regexify('[a-zA-Z0-9-_=+*%@!]{8,15}');
                $objectUpdate->password = Hash::make($pass);
                $objectUpdate->state = 21;
                
                Mail::send('emails.recovery', ['empresa' => 'Registro 5B', 'url' => 'https://www.JoseDanielRodriguez.com', 'password' => $pass, 'username' => $objectUpdate->username, 'email' => $objectUpdate->email, 'name' => $objectUpdate->nombres.' '.$objectUpdate->apellidos,], function (Message $message) use ($objectUpdate){
                    $message->from('registro@5b.com.gt', 'Info Registro 5B')
                            ->sender('registro@5b.com.gt', 'Info Registro 5B')
                            ->to($objectUpdate->email, $objectUpdate->nombres.' '.$objectUpdate->apellidos)
                            ->replyTo('registro@5b.com.gt', 'Info JoseDanielRodriguez')
                            ->subject('Contraseña Reestablecida');
                
                });
                
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

    public function changePassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'new_pass' => 'required|min:3',
            'old_pass'      => 'required'
        ]);

        if ($validator->fails()) {
            $returnData = array(
                'status' => 400,
                'message' => 'Invalid Parameters',
                'validator' => $validator->messages()->toJson()
            );
            return Response::json($returnData, 400);
        }
        else {
            $old_pass = $request->get('old_pass');
            $new_pass_rep = $request->get('new_pass_rep');
            $new_pass =$request->get('new_pass');
            $objectUpdate = Users::find($id);
            if ($objectUpdate) {
                try {
                    if(Hash::check($old_pass, $objectUpdate->password))
                    {                       
                        if($new_pass_rep != $new_pass)
                        {
                            $returnData = array(
                                'status' => 404,
                                'message' => 'Passwords do not match'
                            );
                            return Response::json($returnData, 404);
                        }

                        if($old_pass == $new_pass)
                        {
                            $returnData = array(
                                'status' => 404,
                                'message' => 'New passwords it is same the old password'
                            );
                            return Response::json($returnData, 404);
                        }
                        $objectUpdate->password = Hash::make($new_pass);
                        $objectUpdate->state = 1;
                        $objectUpdate->save();

                        return Response::json($objectUpdate, 200);
                    }else{
                        $returnData = array(
                            'status' => 404,
                            'message' => 'Invalid Password'
                        );
                        return Response::json($returnData, 404);
                    }
                }
                catch (Exception $e) {
                    $returnData = array(
                        'status' => 500,
                        'message' => $e->getMessage()
                    );
                }
            }
            else {
                $returnData = array(
                    'status' => 404,
                    'message' => 'No record found'
                );
                return Response::json($returnData, 404);
            }
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
        $objectDelete = Users::find($id);
        if ($objectDelete) {
            try {
                Users::destroy($id);
                return Response::json($objectDelete, 200);
            }
            catch (Exception $e) {
                $returnData = array(
                    'status' => 500,
                    'message' => $e->getMessage()
                );
                return Response::json($returnData, 500);
            }
        }
        else {
            $returnData = array(
                'status' => 404,
                'message' => 'No record found'
            );
            return Response::json($returnData, 404);
        }
    }
}
