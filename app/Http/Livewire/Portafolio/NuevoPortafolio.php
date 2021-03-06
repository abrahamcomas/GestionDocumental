<?php

namespace App\Http\Livewire\Portafolio;

use Livewire\Component;
use Livewire\WithFileUploads; 
use Illuminate\Support\Facades\Storage;
use App\Models\Portafolio;
use App\Models\DocumentoFirma;
use App\Models\DestinoDocumento;
use Illuminate\Support\Facades\DB;  
use Illuminate\Support\Facades\Auth;
use setasign\Fpdi\Fpdi;
use Spatie\PdfToImage\Pdf;
use Org_Heigl\Ghostscript\Ghostscript;
use Zxing\QrReader;
use Imagick;

class NuevoPortafolio extends Component
{ 

    public $Ayuda=0; 
    
    public function Ayuda(){
        $this->Ayuda = 1;
    }
    public function VolverAyuda(){
        $this->Ayuda = 0;
    }
    
    use WithFileUploads;
 
    //Crear imagen firma  
    public $Nombres;
    public $Apellidos; 
    public $Rut;
    public $Oficina;
    public $Cargo;

    public $ID_Plantilla=0;





    public $Creado=1;

    public function Creada(){

        $this->Creado = 0;  

    }
    
    public $Titulo_T,$Tipo_T,$Folio,$Fecha_T,$Materia_T,$Privado,$NumeroIngresado;
    
    public $Acta=0;
    public $PDF= [];
    public $Pagina=0; 

    protected $rules = ['Titulo_T' => 'required',  
                        'Tipo_T' => 'required',
                        'Fecha_T' => 'required', 
                        'PDF' => 'required']; 
 
	protected $messages = [ 'Titulo_T.required' =>'El campo "Título" es obligatorio.',
                            'Tipo_T.required' =>'El campo "Tipo" es obligatorio.',
                            'Fecha_T.required' =>'El campo "Dias para finalizar" es obligatorio.',
                            'PDF.required' =>'El campo "Archivo" es obligatorio.'];

    public function Ingresar()
    {
            $this->validate(); 

            $Funcionario  =  Auth::user()->ID_Funcionario_T;

            $AnioActual = date("y");  

            $Numero = DB::table('Portafolio')
                    ->select('NumeroInterno','Anio')
                    ->orderBy('ID_Documento_T', 'DESC')->first();

            if ($Numero==null) {
                $NumeroInterno  = '0';
                $Anio        = $AnioActual; 
    
            }
            else{
                $NumeroInterno  = $Numero->NumeroInterno;
                $Anio           = $Numero->Anio; 
            }
             

            if ($Anio==$AnioActual){ 
                    
                $NumeroInterno=$NumeroInterno+1;
            }
            else{ 
                
                $NumeroInterno=1;
            } 

            $ID_OficinaPartes =  DB::table('LugarDeTrabajo') 
                ->leftjoin('OficinaPartes', 'LugarDeTrabajo.ID_DepDirecciones_LDT', '=', 'OficinaPartes.ID_OP_LDT')
                ->select('Id_OP')
                ->where('ID_Funcionario_LDT', '=',$Funcionario)
                ->where('Estado_LDT', '=',1)
                ->first();
 
            $DiasTotal= date("Y/m/d",strtotime("+ $this->Fecha_T days")); 
            
            $LugarDeTrabajo =  DB::table('LugarDeTrabajo')
                ->select('ID_DepDirecciones_LDT')
                ->where('ID_Funcionario_LDT', '=',$Funcionario) 
                ->first();

            $ODP_Origen =  DB::table('OficinaPartes')
                ->select('Id_OP')
                ->where('ID_OP_LDT', '=',$LugarDeTrabajo->ID_DepDirecciones_LDT) 
                ->first();
            
            $Portafolio                      = new Portafolio; 
            $Portafolio->ID_Funcionario_Sol  = $Funcionario;
            $Portafolio->Encargado           = 0;
            $Portafolio->ODP                 = 0;
            $Portafolio->ID_OficinaP         = $ODP_Origen->Id_OP;
            $Portafolio->ID_OP_LDT_P         = $LugarDeTrabajo->ID_DepDirecciones_LDT;
            $Portafolio->NumeroInterno       = $NumeroInterno;
            $Portafolio->Privado             = $this->Privado;
            $Portafolio->Folio               = $this->Folio;
            $Portafolio->Estado_T            = 0;
            $Portafolio->Titulo_T            = $this->Titulo_T;
            $Portafolio->Tipo_T              = $this->Tipo_T;
            $Portafolio->Fecha_T             = date("Y/m/d");
            $Portafolio->Anio                = date("y");
            $Portafolio->FechaUrgencia_T     = $DiasTotal;
            $Portafolio->Observacion_T       = $this->Materia_T;
            $Portafolio->save();  
        
            $ID_Documento_T  = $Portafolio->ID_Documento_T;    
             
            $this->NumeroIngresado=$NumeroInterno.'-'.date("y");     
 
            foreach ($this->PDF as $Archivos) {  

                $codificado = Storage::disk('PDF')->put('', $Archivos);

                $NuevoNombre = substr($codificado, 0, -4);
            
                $pdf = new Pdf('PDF/'.$codificado);
                $NumeroPaginas = $pdf->getNumberOfPages('PDF/'.$codificado);
                $pdf->setPage($NumeroPaginas)
                ->saveImage('ImagenQRPDF/'.$NuevoNombre.'.png');
    
                $qrcode= new QrReader('ImagenQRPDF/'.$NuevoNombre.'.png');
                $textQR= $qrcode->text();
    
                if($textQR==false){

                        //Nueva Ruta
                        $NuevaRuta = date("y");
                        $NuevaRutaF = Storage::disk('PDF')->put($NuevaRuta, $Archivos);
                        Storage::disk('ImagenPDF')->put($NuevaRuta, $Archivos);
                        //FIN
     
                    //CREAR IMAGEN DE PDF
                        $hoy = date("Y-m-d H:i:s"); 
                        $token = md5($hoy);
                       

                        $DestinoDocumento                   = new DestinoDocumento;
                        $DestinoDocumento->ID_FSube         = $Funcionario;
                        $DestinoDocumento->DOC_ID_Documento = $ID_Documento_T;
                        $DestinoDocumento->Token            = $token; 
                        $DestinoDocumento->NombreDocumento  = $Archivos->getClientOriginalName(); 
                        $DestinoDocumento->Ruta_T           = $NuevaRutaF;   
                        $DestinoDocumento->save(); 
        
                        $DocumentoFirma                  = new DocumentoFirma;
                        $DocumentoFirma->ID_Funcionario  = $Funcionario;
                        $DocumentoFirma->ID_Documento    = $DestinoDocumento->ID_DestinoDocumento;  
                        $DocumentoFirma->Firmado         = 0;   
                        $DocumentoFirma->save();

                        $contenido='sgd.municipalidadcurico.cl/MostrarDocumentoQR/'.$token.'';

                        $NuevaRuta = substr($codificado, 0, -4);
                        $NuevaRuta2 = $NuevaRuta.'.png';
        
                        $qrimage= public_path('../public/QR/'.$NuevaRuta.'.png');
                        \QRCode::url($contenido)->setOutfile($qrimage)->png();
               
                        $pdf = new FPDI(); 
                        $pagecount =  $pdf->setSourceFile('PDF'.'/'.$codificado);
                        $UltimaPagina=$pagecount;
                
                        for($i =1; $i<=$pagecount; $i++){
                            
                            if($i!=$UltimaPagina){
                                $pdf->AddPage();
                                $pdf->setSourceFile('PDF'.'/'.$codificado);
                                $template = $pdf->importPage($i);
                                $pdf->useTemplate($template,0, 0, 215, 280, true);
                            }
                            else{ 
                                $pdf->AddPage();
                                $pdf->setSourceFile('PDF'.'/'.$codificado);
                                $template = $pdf->importPage($i);
                                $pdf->useTemplate($template,0, 0, 215, 280, true);
                                $pdf->Image('QR/'.$NuevaRuta2, 173, 240, 40, 40);
                                $pdf->SetY(239);
                                $pdf->SetFont('Arial','B',7);
                                $pdf->Cell(172);
                                $pdf->Cell(0,6,utf8_decode("VALIDAR FIRMAS Y V°B°"),0,0,'C');
                                $pdf->Ln(4);
                            }
                        }
    
                        $pdf->Output('F', 'PDF/'.date("y").'/'.$codificado);

                        $pdf->Output('F', 'ImagenPDF/'.date("y").'/'.$codificado);
        
                        Storage::disk('QR')->delete($NuevaRuta2);
                        Storage::disk('ImagenQRPDF')->delete($NuevoNombre.'.png');
        
                        Storage::disk('PDF')->delete($codificado);
                    //FIN CREAR IMAGEN DE PDF
                }else{  
 
                    $token = substr($textQR, 53);

                    $DestinoDocumento                   = new DestinoDocumento;
                    $DestinoDocumento->ID_FSube         = $Funcionario;
                    $DestinoDocumento->DOC_ID_Documento = $ID_Documento_T;
                    $DestinoDocumento->Token            = $token; 
                    $DestinoDocumento->NombreDocumento  = $Archivos->getClientOriginalName(); 
                    $DestinoDocumento->Ruta_T           = $token;   
                    $DestinoDocumento->save(); 
    
                    $DocumentoFirma                  = new DocumentoFirma;
                    $DocumentoFirma->ID_Funcionario  = $Funcionario;
                    $DocumentoFirma->ID_Documento    = $DestinoDocumento->ID_DestinoDocumento;  
                    $DocumentoFirma->Firmado         = 0;   
                    $DocumentoFirma->save();

                }
 
               
            
            }

            $this->Pagina=1; 
        
    } 

    public function Volver()
    {  
        $this->Pagina=0;
    }
 
    public $TipoDocumento; 
    public $Existe;
    public function render()
    { 
        $this->TipoDocumento =  DB::table('TipoDocumento')
        ->where('EstadoTipoDocumento','=', 1)
        ->get();

        $Funcionario  =  Auth::user()->ID_Funcionario_T;
 
        $DatosFirma =  DB::table('Funcionarios')
        ->leftjoin('LugarDeTrabajo', 'Funcionarios.ID_Funcionario_T', '=', 'LugarDeTrabajo.ID_Funcionario_LDT') 
        ->leftjoin('DepDirecciones', 'LugarDeTrabajo.ID_DepDirecciones_LDT', '=', 'DepDirecciones.ID_DepDir') 
        ->select('Rut','Nombres','Apellidos','Nombre_DepDir','Cargo','ID_DepDir')->where('ID_Funcionario_T', '=',$Funcionario)->first();

  
        $this->Rut = $DatosFirma->Rut;
        $this->Nombres = $DatosFirma->Nombres;
        $this->Apellidos = $DatosFirma->Apellidos;  
        $this->Oficina = $DatosFirma->Nombre_DepDir;
        $this->Cargo = $DatosFirma->Cargo;

        $ID_DepDir = $DatosFirma->ID_DepDir; 

        $Lista =  DB::table('ImagenFirma') 
        ->where('id_Funcionario_T', '=',$Funcionario)
        ->get(); 

        $this->Existe=count($Lista);

        return view('livewire.portafolio.nuevo-portafolio',[
            'DatosOficinaPartes' =>  DB::table('OficinaPartes') 
            ->leftjoin('Funcionarios', 'OficinaPartes.id_Funcionario_OP', '=', 'Funcionarios.ID_Funcionario_T') 
            ->select("Nombres","Apellidos")->where('ID_OP_LDT', '=',$ID_DepDir)
            ->first(),
            'plantillas' =>  DB::table('plantillas')->get(), 
      ]);
    }
}
 