<?php

namespace App\Http\Controllers\Documentos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;   
use Illuminate\Support\Facades\Http;
use App\Models\Documento;
use Illuminate\Support\Facades\Auth; 
use App\Models\DocFunc;
use App\Models\DocumentoFirma;
use App\Models\FirmadosDD;
use App\Models\FirmadosFunc;
use App\Models\AnioDD;
use setasign\Fpdi\Fpdi;

class FirmarDocumentosRecibidos extends Controller
{
    public function index(Request $request)   
    {

        dd('stop');
 
        $Contrasenia = $request->input('Contrasenia'); 
        $Ruta  = $request->input('Ruta');
        $mousePosX = $request->input('mousePosX'); 
        $mousePosY = $request->input('mousePosY'); 
        $Pagina = $request->input('Pagina'); 

        $Ancho = $request->input('Ancho'); 
        $Alto = $request->input('Alto'); 

        if(empty($Pagina)){
            $Pagina = 1;
        } 

        $rules = [
            'Contrasenia' => 'required',
            'Ancho' => 'required',
        ]; 
     
        $messages = [ 
            'Contrasenia.required' =>'El campo Contraseña es obligatorio.',
            'Ancho.required' =>'Por favor seleccione el lugar en donde ira la firma digital.'
        ]; 
    
        $this->validate($request, $rules, $messages);

        $RUNInspector=Auth::guard('web')->user()->Rut;
        $RutFirma = substr($RUNInspector, 0, -2); 

        if(Auth::attempt(['Rut' => $RUNInspector, 'password' => $Contrasenia], true))
        { 

            $hoy = date("Y-m-d H:i:s");   
            $NuevaFecha = strtotime ( '+4 minute' , strtotime ($hoy) ) ; 
            $NuevaFecha = date ( 'Y-m-d H:i:s' , $NuevaFecha); 
           
                $Purpose='Desatendido';    
                $header   = [      
                                "alg"=> "HS256",
                                "typ"=> "JWT"
                    ];
        
            $header2 = base64_Encode(json_encode($header, JSON_UNESCAPED_UNICODE));
                    
            $payload  = [               
                        "entity" => "Ilustre Municipalidad de Curicó",
                        "run" =>  $RutFirma,
                        "expiration" => $NuevaFecha, 
                        "purpose" =>  $Purpose
            ];

            $key = "2de50761dde340d383f61bf842646352";
            $header2 = base64_Encode(json_encode($header, JSON_UNESCAPED_UNICODE));
            
            $payload2 = base64_Encode(json_encode($payload, JSON_UNESCAPED_UNICODE));
            $payload2 =str_replace("=","",$payload2);
             
            $unsignedToken = $header2.'.'.$payload2;

            $signature = hash_hmac('sha256',$unsignedToken,$key,true);
            $signature = base64_Encode($signature); 
        
            $token = $unsignedToken.'.'.$signature;  

            $datos=DB::table('DestinoDocumento')->Select('Ruta_T')->where('Ruta_T', '=', $Ruta)->first();
                                        
            $Ruta = $datos->Ruta_T;  
                                                               
            $PDF = Storage::disk('PDF')->get($Ruta); 

            $codificado = base64_encode($PDF); 
        
            $Sha256 = hash('sha256', $PDF);

            $ID_Funcionario  =  Auth::user()->ID_Funcionario_T;   
            if($RutaImagenFirma==null){

                $rutaImagen=DB::table('ImagenFirma')->Select('Ruta')->where('id_Funcionario_T', '=', $ID_Funcionario)->first();
                $rutaImagen2="Firmas/".$rutaImagen->Ruta;


            }else{

                $rutaImagen=DB::table('ImagenFirma')->Select('Ruta')->where('Ruta', '=', $RutaImagenFirma)->first();
                $rutaImagen2="Firmas/".$rutaImagen->Ruta;
            }

            $contenidoBinario = file_get_contents($rutaImagen2);
            $imagenComoBase64 = base64_encode($contenidoBinario);

            $AnchoFirma=(($Ancho*35)/100);
            $AltoFirma=(($Alto*25)/100);
    
            $llx1 = (($mousePosX*100)/$Ancho);
            $lly1 = 100 - (($mousePosY*100)/$Alto);
            $llx = (($llx1*$Ancho)/100) +  $AnchoFirma;   
            $lly = ((($lly1)*$Alto)/100);
            
            $urx = $llx - $AnchoFirma; 
            $ury = $lly - $AltoFirma;

            $response = Http::post('https://api.firma.digital.gob.cl/firma/v2/files/tickets',[
                "api_token_key"=> "54740e7d-10ac-4b8e-8caa-6c87857b776e",
                    "token"=> $token,
                    "files"=> array([
                        "content-type"=> "application/pdf",
                        "content"=>  $codificado,
                        "description"=> "str",
                        "checksum"=> $Sha256,
                        "layout"=> "<AgileSignerConfig> 
                        <Application id=\"THIS-CONFIG\"> 
                            <pdfPassword/> 
                            <Signature> 
                                <Visible active=\"true\" layer2=\"false\" label=\"false\" pos=\"1\">
                                        <llx>$llx</llx>
                                        <lly>$lly</lly>
                                        <urx>$urx</urx>
                                        <ury>$ury</ury> 
                                        <page>$Pagina</page> 
                                        <image>BASE64</image> 
                                        <BASE64VALUE>$imagenComoBase64</BASE64VALUE>
                                </Visible> 
                            </Signature> 
                        </Application> 
                    </AgileSignerConfig>"    
                                        
                    ])        
            ]);
                                        
            $responseBody = json_decode($response->getBody());

            if(empty($responseBody->status))
                { 
                    
                    if($responseFiles = $responseBody->files!=null)

                    { 

                        $responseFiles = $responseBody->files;
                        foreach($responseFiles as $posicion)
                            { 
                                $status = $posicion->status;
                            }
                                
                        if($status=='OK')
                            {

                            $responseFiles = $responseBody->files;

                         
                                    $responseidSolicitud = $responseBody->idSolicitud;
                                    foreach($responseFiles as $posicion)
                                    { 
                                        $content = $posicion->content;
                                        $status = $posicion->status;
                                        $checksum_original = $posicion->checksum_original;
                                    }
            
                                    $hoy = date("Y-m-d");   
                                    $decoded = base64_decode($content);
                                    $image = $request->get('image_base64');  // your base64 encoded
                                    $image = str_replace('data:pdf;base64,', '', $decoded);
                                    $image = str_replace(' ', '+', $image);
                                    
                                    Storage::disk('PDF')->put($Ruta, $decoded);        
                                
                                    if (file_exists($Ruta)) {
                                        header("Content-Description: File Transfer");
                                        header("Content-Type: application/octet-stream");
                                        header('Content-Disposition: attachment; filename="'.basename($Ruta).'"');
                                        header("Expires: 0");
                                        header("Cache-Control: must-revalidate");
                                        header("Pragma: public");
                                        header("Content-Length: " . filesize($Ruta));
                                        $guardar= readfile($Ruta);
                                        exit;
                                    }

                                    $DestinoDocumento  =  DB::table('DestinoDocumento')->where('Ruta_T', '=',$Ruta)->first();
            
                                    $ID_DestinoDocumento   = $DestinoDocumento->ID_DestinoDocumento ;

                                    $ID_Funcionario  =  Auth::user()->ID_Funcionario_T;   
                                    $ID_DocumentoFirma  =  DB::table('DocumentoFirma') 
                                                ->where('ID_Funcionario', '=',$ID_Funcionario)
                                                ->where('ID_Documento', '=',$ID_DestinoDocumento)->first();
            
                                    $ID_DocumentoF   = $ID_DocumentoFirma->ID_DocumentoFirma;
             
                                    $DocumentoFirma             =DocumentoFirma::find($ID_DocumentoF);
                                    $DocumentoFirma->FechaFirma = date("Y/m/d");
                                    $DocumentoFirma->Firmado    = 1;
                                    $DocumentoFirma->save();

                                    return view('Documentos/DocumentoFirmado', compact('status'));
                                
                                }
                                else 
                                {
                                    return view('Documentos/DocumentoFirmado', compact('status'));
                                }

                                 }
                            else 
                                {
                                    $status='ERROR, firma digital';
                                    return view('Documentos/DocumentoFirmado', compact('status'));
                                }

                }
                else{
                    $status='ERROR, firma digital no registrada';
                    return view('Documentos/DocumentoFirmado', compact('status'));

                }                            
        }                                
        else
        {
            return back()
            ->withErrors(['Contraseña Incorrecta'])
            ->withInput(request(['RUN'])); 
        }
    }
}
