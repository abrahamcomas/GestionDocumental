<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route; 
use App\Http\Controllers\Login\Registro;
use App\Http\Controllers\Login\Login; 
use App\Http\Controllers\Login\Cerrar;
use App\Http\Controllers\Login\ResetearContrasenia;
use App\Http\Controllers\Login\RestaurarContrasenia;
use App\Http\Controllers\Login\NuevaContrasenia;
 
use App\Http\Controllers\Documentos\PDFGestionController;
use App\Http\Controllers\Documentos\FirmarDocumentoController;
use App\Http\Controllers\Documentos\FirmarDocumentoController4;


use App\Http\Controllers\Documentos\FirmaMasivaController; 

use App\Http\Controllers\Documentos\IngresarQRController; 


use App\Http\Controllers\Documentos\MostrarQR;
use App\Http\Controllers\Documentos\Plantillas;
use App\Http\Controllers\Documentos\IngresarPlantillas;


use App\Http\Controllers\Documentos\PosicionFirmaController;


use App\Http\Controllers\Externos\FirmarDocumento;
use App\Http\Controllers\Externos\SubirPDFController;
use App\Http\Controllers\Externos\MostrarPDFExterno;
use App\Http\Controllers\Externos\VisualizarPDFExternos;

use App\Http\Controllers\ODP\SolicitarFirma;
use App\Http\Controllers\ODP\FirmandoSolicitudController;



use App\Http\Controllers\Sessiones\SessionesController;
use App\Http\Controllers\Sessiones\EliminarVinculo;

use App\Http\Controllers\Root\ImagenCreada;
use App\Http\Controllers\Portafolio\ImagenCreada2;
 
use App\Http\Controllers\CrearDocumento\NuevoDocumentoController;
use App\Http\Controllers\CrearDocumento\MostrarPlantillas;

use App\Http\Controllers\GenerarPlantillas\IngresarPlantillaController;

use Illuminate\Http\Request;


use App\Http\Controllers\Sistema\CambiarContController;
use App\Http\Controllers\Sistema\CambiarCorreoControler;
 

use App\Http\Controllers\EncargadoODP\FirmarArchivoDirectoIndividual;
use App\Http\Controllers\EncargadoODP\FirmarArchivoDirectoMasiva;


use App\Http\Controllers\Recibidos\FirmandoRecibidoIndiv;
use App\Http\Controllers\Recibidos\FirmandoRecibidoMult;


use App\Http\Controllers\Detenidos\FirmandoDetenidas;
use App\Http\Controllers\Detenidos\FirmandoDetenidasMasivo;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/ 

    Route::get('/', function () {
        if(Auth::guard('web')->check()){
            return view('Posts/Principal/PrincipalPosts');
        }else{
            return view('Login/Login');
        }
    })->name('Index');

//PAGINA PRINCIPAL LOGIN 

    Route::post('/', [Login::class, 'index'])->name('Login'); 

// REGISTRO

    Route::patch('IngresoRegistro', [Registro::class, 'index'])->name('Registro');
    Route::get('Registro', function () {
        return view('Posts/Registro/Registrarse');
    })->name('Registrarse');

//CERRAR SESION 

    Route::get('CerrarSesion', [Cerrar::class, 'index'])->name('CerrarSesion');
 

//RESTAURAR CONTRASEÑA POR CORREO

    Route::patch('Restaurar', [NuevaContrasenia::class, 'index'])->name('Restaurar');
    Route::post('Login/RecuperarContrasenia', [ResetearContrasenia::class, 'index'])->name('ContraseniaEnviada');
    Route::get('ResetearContrasenia', [RestaurarContrasenia::class, 'index']);
    Route::get('RecuperarContrasenia', function (){ 
        return view('Login/RecuperarContrasenia');
    })->name('Recuperar');  

     
    

//SOLICITUD.

//NUEVA
Route::post('/PortafolioSinFimrar',function () { 
    return view('Posts/Portafolio/FirmarDocumentos');
})->middleware('auth')->name('EnvioOficinaPartes');
 
Route::get('/PortafolioSinFimrar',function () { 
    return view('Posts/Portafolio/FirmarDocumentos');
})->middleware('auth'); 
//Fin   
 
//DETENIDAS 
// App\Http\Controllers\Documentos\PosicionFirmaController;
Route::post('/FirmaDetenidoI', [PosicionFirmaController::class, 'FirmaDetenidoIndividual'])->middleware('auth')->name('FirmaDetenidoIndividual');   
Route::get('/FirmaDetenidoI',function () { 
    return view('Posts/Portafolio/FirmaDetenidoIndividual'); 
})->middleware('auth'); 
 
Route::post('/FirmaDetenidoM', [PosicionFirmaController::class, 'FirmaDetenidoMasiva'])->middleware('auth')->name('FirmaDetenidoMasiva');   
Route::get('/FirmaDetenidoM',function () { 
    return view('Posts/Portafolio/FirmaDetenidoIndividual'); 
})->middleware('auth');  
 
// App\Http\Controllers\Documentos\FirmaMasivaController; 
Route::post('/IngresarFirmamasiva', [FirmandoDetenidasMasivo::class, 'index'])->middleware('auth')->name('FirmandoDetenidasMasivo');   

Route::post('/FirmandoDetenidas', [FirmandoDetenidas::class, 'index'])->middleware('auth')->name('FirmandoDetenidas');   
  

 
//RECIBIDAS 
Route::post('/FirmaIndRec', [PosicionFirmaController::class, 'FirmaIndRec'])->middleware('auth')->name('FirmaIndRec');    
Route::get('/FirmaIndRec',function () { 
    return view('Posts/Portafolio/FirmaIndRec');
})->middleware('auth');   

Route::post('/FirmaMasivaRec', [PosicionFirmaController::class, 'FirmaMasivaRec'])->middleware('auth')->name('FirmaMasivaRec');    
Route::get('/FirmaMasivaRec',function () { 
    return view('Posts/Portafolio/FirmaMasivaRec');
})->middleware('auth');   

Route::post('/FirmandoRecibidoIndiv', [FirmandoRecibidoIndiv::class, 'index'])->middleware('auth')->name('FirmandoRecibidoIndiv'); 
Route::post('/FirmandoRecibidoMult', [FirmandoRecibidoMult::class, 'index'])->middleware('auth')->name('FirmandoRecibidoMult'); 


//PORTAFOLIO DIRECTO 
Route::post('/CoordenadasFirma3', [PosicionFirmaController::class, 'FirmarIndividualDirecto'])->middleware('auth')->name('FirmarIndividualDirecto');   
Route::get('/CoordenadasFirma3',function () { 
    return view('Posts/Portafolio/FirmandoDocumentoDirecto'); 
})->middleware('auth');   
 
Route::post('/FirmaMasivaPortDirecto', [PosicionFirmaController::class, 'FirmaMasivaDirecto'])->middleware('auth')->name('FirmaMasivaDirecto');   
Route::get('/FirmaMasivaPortDirecto',function () { 
    return view('Posts/EncargadoODP/FirmaMasiva');
})->middleware('auth');   
 
Route::post('/FirmarArchivoDirectoInd', [FirmarArchivoDirectoIndividual::class, 'index'])->middleware('auth')->name('FirmarArchivoDirectoIndividual');   
Route::post('/FirmarArchivoDirectoM', [FirmarArchivoDirectoMasiva::class, 'index'])->middleware('auth')->name('FirmarArchivoDirectoMasiva');   


//CREAR SOLICITUD POR URL
Route::get('/firmarsolicitud/{ID_LinkFirma}/{token}',function ($ID_LinkFirma,$token) { 
   

    $LinkFirma =  DB::table('LinkFirma') 
    ->select('ID_Documento_L') 
    ->where('ID_LinkFirma', '=', $ID_LinkFirma) 
    ->where('token', '=', $token) 
    ->where('Estado', '=', 1)  
    ->first(); 
 
    if(!empty($LinkFirma->ID_Documento_L)){ 

        $LinkFirma =  DB::table('LinkFirma') 
        ->select('ID_Documento_L','Titulo_T','ID_Funcionario_L','Nombres_L','Apellidos_L','mousePosX','mousePosY','Pagina','Ancho','Alto','Observacion') 
        ->where('ID_LinkFirma', '=', $ID_LinkFirma) 
        ->first(); 

        $Archivos =  DB::table('DestinoDocumento') 
        ->where('DOC_ID_Documento', '=',$LinkFirma->ID_Documento_L)
        ->get();

        if($LinkFirma->Observacion=="1010xq"){
            $LinkFirma->Observacion=""; 
        }

        return view('FirmarSolicitud/FirmarSolicitud')->with('ID_LinkFirma', $ID_LinkFirma)->with('Titulo_T', $LinkFirma->Titulo_T)->with('ID_Funcionario_T', $LinkFirma->ID_Funcionario_L )->with('Nombres', $LinkFirma->Nombres_L)->with('Apellidos', $LinkFirma->Apellidos_L)->with('Archivos', $Archivos)->with('mousePosX', $LinkFirma->mousePosX)->with('mousePosY', $LinkFirma->mousePosY)->with('Pagina', $LinkFirma->Pagina)->with('Ancho', $LinkFirma->Ancho)->with('Alto', $LinkFirma->Alto)->with('Observacion', $LinkFirma->Observacion);
    }
    else{
        return view('FirmarSolicitud/NoValido');

    }
});  


//Respuesta archivo firmado URL
Route::post('/FirmarSolicitudRecibida', [FirmandoSolicitudController::class, 'index'])->name('FirmandoSolicitud');  



//AGREGAR DOCUMENTOS
Route::post('/AgregarDocumentos',function () { 
    return view('Posts/Root/AgregarDocumentos');
})->middleware('auth')->name('AgregarDocumentos');
 
Route::get('/AgregarDocumentos',function () { 
    return view('Posts/Root/AgregarDocumentos');
})->middleware('auth'); 
//Fin   

//AGREGAR DIREC. DEPT.
Route::post('/AgregarDirDEP',function () { 
    return view('Posts/Root/AgregarDirDEP');
})->middleware('auth')->name('AgregarDirDEP');
 
Route::get('/AgregarDirDEP',function () { 
    return view('Posts/Root/AgregarDirDEP');
})->middleware('auth'); 
//Fin   



//MENSAJE
Route::post('/Mensaje',function () { 
    return view('Posts/Root/Mensaje');
})->middleware('auth')->name('Mensaje');
 
Route::get('/Mensaje',function () { 
    return view('Posts/Root/Mensaje');
})->middleware('auth'); 
//Fin   
















































 












//FIRMAR DOCUMENTO EXT.
 
    Route::post('/SubirDocumento',function () { 
    
        return view('Posts/Externo/SubirDocumentoExterno');

    })->middleware('auth')->name('DocumentoExt');  

    Route::get('/SubirDocumento',function () { 

        return view('Posts/Externo/SubirDocumentoExterno');

    })->middleware('auth'); 
     
    

 



    Route::post('/FirmarPDFExterrno', [SubirPDFController::class, 'index'])->middleware('auth')->name('FirmarExterno');    
    Route::get('/FirmarPDFExterrno',function () { 
        return view('Posts/Externo/DocumentoExterno');
    })->middleware('auth');

    Route::post('/MostrarPDFExterno', [MostrarPDFExterno::class, 'index'])->middleware('auth')->name('MostrarPDFExterno');    

    Route::post('/VisualizarPDFExterno', [VisualizarPDFExternos::class, 'index'])->middleware('auth')->name('VisualizarPDFExterno');    
//FIN

//ROLES

Route::post('/AgregarRoles',function () {  
    return view('Posts/Roles/RolesPosts');
})->middleware('auth')->name('AgregarRoles');

Route::get('/AgregarRoles',function () { 
    return view('Posts/Roles/RolesPosts');
})->middleware('auth');

//FIN ROLES 


//OPCIONES MENU

//ENCARGADO ODP
    Route::post('/AdministrarSecretaria',function () { 
        return view('Posts/EncargadoODP/EncargadoODP');
    })->middleware('auth')->name('AdministrarSecretaria');
    
    Route::get('/AdministrarSecretaria',function () { 
        return view('Posts/EncargadoODP/EncargadoODP');
    })->middleware('auth');

    Route::post('/PortafolioDirecto',function () { 

        if (isset($_SESSION['Ruta'])){
            $request->session()->flush();
        }
      
        if (isset($_SESSION['ID_Documento_T'])){
            $request->session()->flush();
        }

        return view('Posts/EncargadoODP/PortafolioDirecto');
    })->middleware('auth')->name('PortafolioDirecto'); 
    
    Route::get('/PortafolioDirecto',function () { 
        return view('Posts/EncargadoODP/PortafolioDirecto');
    })->middleware('auth');





    
//Fin envio


//FUNCIONARIOS
Route::post('/AutorizarRegistro',function () { 
    return view('Posts/Funcionarios/Autorizar');
})->middleware('auth')->name('AutorizarRegistro');

Route::get('/AutorizarRegistro',function () { 
    return view('Posts/Funcionarios/Autorizar');
})->middleware('auth');


Route::post('/ListaFuncionarios',function () { 
    return view('Posts/Funcionarios/ListaFuncionarios');
})->middleware('auth')->name('ListaFuncionarios');

Route::get('/ListaFuncionarios',function () { 
    return view('Posts/Funcionarios/ListaFuncionarios');
})->middleware('auth');
//Fin envio
  
  

//PORTAFOLIO PORTAFOLIO PORTAFOLIO PORTAFOLIO PORTAFOLIO PORTAFOLIO PORTAFOLIO PORTAFOLIO

Route::post('ImagenCreadaP', [ImagenCreada2::class, 'index'])->name('ImagenCreada2');

Route::post('ImagenCreadaP3', [ImagenCreada2::class, 'index2'])->name('ImagenCreada3');

Route::post('ImagenCreadaP4', [ImagenCreada2::class, 'index4'])->name('ImagenCreada4');

Route::get('/ImagenCreadaP4',function () { 
    return view('Posts/EncargadoODP/PortafolioDirecto');
})->middleware('auth');



Route::post('/NuevoPortafolio',function () { 
    return view('Posts/Portafolio/NuevoPortafolio');
})->middleware('auth')->name('CrearDocumento');

Route::get('/NuevoPortafolio',function () { 
    return view('Posts/Portafolio/NuevoPortafolio');
})->middleware('auth');


Route::post('/CrearDocumentoODP',function () { 
    return view('Posts/ODP/CrearDocumentoODP');
})->middleware('auth')->name('CrearDocumentoODP');

Route::get('/CrearDocumentoODP',function () { 
    return view('Posts/ODP/CrearDocumentoODP');
})->middleware('auth');

 

//SIN FIRMAR ODP
Route::post('/PortafolioSinFimrarODP',function () { 

    if (isset($_SESSION['Ruta'])){
        $request->session()->flush();
    }
  
    if (isset($_SESSION['ID_Documento_T'])){
        $request->session()->flush();
    }

    return view('Posts/ODP/FirmarDocumentosODP');
})->middleware('auth')->name('EnvioOficinaPartesODP');
 
Route::get('/PortafolioSinFimrarODP',function () { 
    return view('Posts/ODP/FirmarDocumentosODP');
})->middleware('auth'); 
//Fin


 
//PortafolioDirecto
Route::post('/PortafolioDirectoF',function () { 

    if (isset($_SESSION['Ruta'])){
        $request->session()->flush();
    }
    
    if (isset($_SESSION['ID_Documento_T'])){
        $request->session()->flush();
    }

    return view('Posts/EncargadoODP/PortafolioDirecto');
})->middleware('auth')->name('PortafolioDirectoF');
 
Route::get('/PortafolioDirectoF',function () { 
    return view('Posts/EncargadoODP/PortafolioDirecto');
})->middleware('auth'); 
//Fin
  
//EN PROCESO
Route::post('/PortafoliosEnProceso',function () { 
    return view('Posts/Portafolio/DocumentoEnProcesoOP');
})->middleware('auth')->name('DocumentoEnProcesoOP');
 
Route::get('/PortafoliosEnProceso',function () { 
    return view('Posts/Portafolio/DocumentoEnProcesoOP');
})->middleware('auth');
//Fin  
  
//PORTAFOLIOS FINALIZADOS 
Route::post('/PortafoliosFinalizados',function () { 
    return view('Posts/Portafolio/DocumentosFinalizados');
})->middleware('auth')->name('PortafoliosFinalizados');
 
Route::get('/PortafoliosFinalizados',function () { 
    return view('Posts/Portafolio/DocumentosFinalizados');
})->middleware('auth'); 
//Fin
 
//PORTAFOLIOS FINALIZADOSFIR
Route::post('/DocumentosFinalizadosFir',function () { 
    return view('Posts/Portafolio/DocumentosFinalizadosFir'); 
})->middleware('auth')->name('PortafoliosFinalizadosFir');
 
Route::get('/DocumentosFinalizadosFir',function () { 
    return view('Posts/Portafolio/DocumentosFinalizadosFir');
})->middleware('auth');
//Fin

//PORTAFOLIOS FINALIZADOS VB 
Route::post('/PortafoliosFinalizadosVB',function () { 
    return view('Posts/Portafolio/DocumentosFinalizadosVB');
})->middleware('auth')->name('PortafoliosFinalizadosVB');
 
Route::get('/PortafoliosFinalizadosVB',function () { 
    return view('Posts/Portafolio/DocumentosFinalizadosVB');
})->middleware('auth');
//Fin




//PORTAFOLIOS RECIBIDOS 
Route::post('/PortafoliosRecibidos',function () { 
    
    if (isset($_SESSION['Ruta'])){
        $request->session()->flush();
    }
  
    if (isset($_SESSION['ID_Documento_T'])){
        $request->session()->flush();
    }

    return view('Posts/Portafolio/Recibidos');
})->middleware('auth')->name('PortafoliosRecibidos');
 
Route::get('/PortafoliosRecibidos',function () { 
    return view('Posts/Portafolio/Recibidos');
})->middleware('auth');
//Fin


//PORTAFOLIOS RECIBIDOS 
Route::post('/PortafoliosRecibidosVB',function () { 
    return view('Posts/Portafolio/RecibidosVB');
})->middleware('auth')->name('PortafoliosRecibidosVB');
 
Route::get('/PortafoliosRecibidosVB',function () { 
    return view('Posts/Portafolio/RecibidosVB');
})->middleware('auth');
//Fin





//ODPODPODPODPODPODPODPODPODPODPODPODPODPODPODPODPODPODP

//ODPExternos
Route::post('/ODPExternos',function () { 
    return view('Posts/ODP/ODPExternos');
})->middleware('auth')->name('ODPExternos');
 
Route::get('/ODPExternos',function () { 
    return view('Posts/ODP/ODPExternos');
})->middleware('auth');
//Fin

Route::post('/ODPExternosVB',function () { 
    return view('Posts/ODP/ODPExternosVB');
})->middleware('auth')->name('ODPExternosVB');
 
Route::get('/ODPExternosVB',function () { 
    return view('Posts/ODP/ODPExternosVB');
})->middleware('auth');
//Fin






 
//Imagen creada de firma
//ODPExternos
Route::post('/ImagenCreada',function () { 
    return view('Posts/ODP/ODPExternos');
})->middleware('auth')->name('ImagenCreada');
 
Route::get('/ImagenCreada',function () { 
    return view('Posts/ODP/ODPExternos');
})->middleware('auth');
//Fin





Route::post('ImagenCreada', [ImagenCreada::class, 'index'])->name('ImagenCreada');

















 



Route::post('/IngresarQR', [IngresarQRController::class, 'index'])->middleware('auth')->name('IngresarQR');   

 






Route::post('/Firmar2', [FirmarDocumentoController4::class, 'index'])->middleware('auth')->name('Firmar4');  

Route::post('/SolicitarFirmaFuncionario', [SolicitarFirma::class, 'index'])->middleware('auth')->name('SolicitarFirmaFuncionario');  

Route::get('/SolicitarFirmaFuncionario',function () { 
    return view('Posts/ODP/FirmarDocumentosODP');
})->middleware('auth');



Route::post('/Documento', [PDFGestionController::class, 'index'])->middleware('auth')->name('MostrarPDF'); 

Route::post('/DocumentoPDF', [PDFGestionController::class, 'index'])->name('MostrarPDF2'); 


 


Route::post('/Distribuccion',function () { 
    return view('Posts/ODP/ODPInternos');
})->middleware('auth')->name('Distribuccion');

Route::get('/Distribuccion',function () { 
    return view('Posts/ODP/ODPInternos');
})->middleware('auth');

Route::post('/DocumentosDeEntrada',function () { 
    return view('Posts/Documentos/DocumentosDeEntradaPosts');
})->middleware('auth')->name('DocumentosDeEntrada');

Route::get('/DocumentosDeEntrada',function () { 
    return view('Posts/Documentos/DocumentosDeEntradaPosts');
})->middleware('auth');

Route::post('/DocumentosFinalizados',function () { 
    return view('Posts/Documentos/DocumentosFinalizadosPosts');
})->middleware('auth')->name('DocumentosFinalizados'); 

Route::get('/DocumentosFinalizados',function () { 
    return view('Posts/Documentos/DocumentosFinalizadosPosts');
})->middleware('auth'); 

Route::post('/DocumentosAvisos',function () { 
    return view('Posts/Documentos/DocumentosAvisos');
})->middleware('auth')->name('DocumentosAvisos'); 

Route::get('/DocumentosAvisos',function () { 
    return view('Posts/Documentos/DocumentosAvisos');
})->middleware('auth'); 


Route::get('/Menu',function () { 
    return view('Posts/Principal/PrincipalPosts');
})->middleware('auth')->name('Principal'); 





//SESSIONES
Route::post('/Sessiones', [SessionesController::class, 'index'])->middleware('auth')->name('Sessiones');  
Route::get('/Sessiones', [SessionesController::class, 'index'])->middleware('auth');   
Route::get('/EliminarVinculo',function () { 
    return view('Login/Login');
})->middleware('auth');
Route::post('/EliminarVinculo', [EliminarVinculo::class, 'index'])->middleware('auth')->name('EliminarVinculo'); 
 

 

Route::post('/FirmarExterno', [FirmarDocumento::class, 'index'])->middleware('auth')->name('FirmarPDF');    


 



  

Route::post('/SolicitarFirma', [PosicionFirmaController::class, 'index4'])->middleware('auth')->name('SolicitarFirma');   
Route::get('/SolicitarFirma',function () { 
    return view('Posts/ODP/SolicitarFirma');
})->middleware('auth');  











Route::post('/PosicionarQR', [PosicionFirmaController::class, 'QR'])->middleware('auth')->name('QR');   
Route::get('/PosicionarQR',function () { 
    return view('Posts/Portafolio/QR');
})->middleware('auth');   


 




Route::post('/AgregarFirma',function () { 
    return view('Posts/ImagenFirma/PostImagenFirma');
})->middleware('auth')->name('AgregarFirma');   

Route::get('/AgregarFirma',function () { 
    return view('Posts/ImagenFirma/PostImagenFirma');
})->middleware('auth');  
 

Route::post('/AgregarJefes',function () { 
    return view('Posts/Root/AgregarJefes');
})->middleware('auth')->name('AgregarJefes');   

Route::get('/AgregarJefes',function () { 
    return view('Posts/Root/AgregarJefes');
})->middleware('auth');  


 



//CrearDocumentos

Route::post('/CrearDocumentos', [NuevoDocumentoController::class, 'index'])->middleware('auth')->name('CrearDocumentos');  
Route::get('/CrearDocumentos', [NuevoDocumentoController::class, 'index'])->middleware('auth')->name('CrearDocumentos'); 


Route::post('/MostrarPlantillas', [MostrarPlantillas::class, 'index'])->middleware('auth')->name('MostrarPlantillas');  
Route::post('/GuardarPlantillas', [MostrarPlantillas::class, 'GuardarPlantillas'])->middleware('auth')->name('GuardarPlantillas');  


Route::get('/MostrarDocumentoQR/{ID}/{Token}',function ($ID,$Token) { 

    $Buscar =  DB::table('DestinoDocumento') 
        ->select('ID_DestinoDocumento') 
        ->where('ID_DestinoDocumento', '=', $ID) 
        ->where('Token', '=', $Token)
        ->first(); 

 
    
    if(!empty($Buscar->ID_DestinoDocumento)){

        $ID_DestinoDocumento= $Buscar->ID_DestinoDocumento;

        $Datos =  DB::table('DestinoDocumento') 
        ->leftjoin('DocumentoFirma', 'DestinoDocumento.ID_DestinoDocumento', '=', 'DocumentoFirma.ID_Documento')
        ->leftjoin('Funcionarios', 'DocumentoFirma.ID_Funcionario', '=', 'Funcionarios.ID_Funcionario_T')
        ->select('Nombres','Apellidos','FechaFirma','Firmado')
        ->where('ID_DestinoDocumento', '=',$ID_DestinoDocumento)->get();
        
        $BVB =  DB::table('DestinoDocumento') 
        ->leftjoin('Portafolio', 'DestinoDocumento.DOC_ID_Documento', '=', 'Portafolio.ID_Documento_T')
        ->leftjoin('InterPortaFuncionarioVB', 'Portafolio.ID_Documento_T', '=', 'InterPortaFuncionarioVB.IPF_Portafolio')
        ->leftjoin('Funcionarios', 'InterPortaFuncionarioVB.IPF_ID_Funcionario', '=', 'Funcionarios.ID_Funcionario_T')
        ->select('Nombres','Apellidos','FechaR','Visto','Estado')
        ->where('ID_DestinoDocumento', '=',$ID_DestinoDocumento)->get();

        return view('QR/MostrarDocumentoQR')->with('Datos', $Datos)->with('BVB', $BVB)->with('ID_DestinoDocumento', $ID_DestinoDocumento);
    }
    else{
        return Redirect::to('https://web.curico.cl/');

    }
});  



Route::post('/AgregarPlantillas',function () { 
    return view('Posts/Plantillas/AgregarPlantillas');
})->middleware('auth')->name('AgregarPlantillas');   
Route::get('/AgregarPlantillas',function () { 
    return view('Posts/Plantillas/AgregarPlantillas');
})->middleware('auth');   

Route::post('/DescargarPlantillasU',function () { 
    return view('Posts/Plantillas/DescargarPlantillas');
})->middleware('auth')->name('DescargarPlantillasU');  
Route::get('/DescargarPlantillasU',function () { 
    return view('Posts/Plantillas/DescargarPlantillas');
})->middleware('auth'); 

Route::post('/IngresarPlantillas', [IngresarPlantillas::class, 'index'])->middleware('auth')->name('IngresarPlantillas');   



Route::post('/DescargarPlantillas', [Plantillas::class, 'DescargarPlantillas'])->middleware('auth')->name('DescargarPlantillas');  
Route::post('/EliminarPlantillas', [Plantillas::class, 'EliminarPlantillas'])->middleware('auth')->name('EliminarPlantillas');  


Route::post('/SubirPlantilla',function () { 
    return view('Posts/GeneradorPlantillas/SubirPlantilla');
})->middleware('auth')->name('SubirPlantilla');   

Route::get('/SubirPlantilla',function () { 
    return view('Posts/GeneradorPlantillas/SubirPlantilla');
})->middleware('auth');  


Route::post('/Subrogante',function () { 
    return view('Posts/EncargadoODP/Subrogante');
})->middleware('auth')->name('Subrogante');   

Route::get('/Subrogante',function () { 
    return view('Posts/EncargadoODP/Subrogante');
})->middleware('auth');  

Route::post('/HabilitarFirmaMasiva',function () { 
    return view('Posts/Root/FirmaMasiva');
})->middleware('auth')->name('HabilitarFirmaMasiva');   

Route::get('/HabilitarFirmaMasiva',function () { 
    return view('Posts/Root/FirmaMasiva');
})->middleware('auth');  




Route::post('/IngresoPlantilla', [IngresarPlantillaController::class, 'index'])->middleware('auth')->name('IngresoPlantilla');    
Route::get('/IngresoPlantilla',function () { 
    return view('Posts/GeneradorPlantillas/GenerarPlantillas');
})->middleware('auth');  






 

Route::post('/CambiarCorreo',function () { 

    $ID_Funcionario_T = Auth::user()->ID_Funcionario_T;

    $Email =  DB::table('Funcionarios') 
    ->select('Email') 
    ->where('ID_Funcionario_T', '=', $ID_Funcionario_T) 
    ->first();
 
    return view('Sistema/CambiarCorreo/CambiarCorreo')->with('Email', $Email->Email); 
})->middleware('auth')->name('CambiarCorreo');   

Route::get('/CambiarCorreo',function () {  
    $ID_Funcionario_T = Auth::user()->ID_Funcionario_T;

    $Email =  DB::table('Funcionarios') 
    ->select('Email') 
    ->where('ID_Funcionario_T', '=', $ID_Funcionario_T)  
    ->first();
 
    return view('Sistema/CambiarCorreo/CambiarCorreo')->with('Email', $Email->Email); 
})->middleware('auth');

Route::post('/CambioContrasenia',function () { 
    return view('Sistema/CambiarContrasenia/CambiarContrasenia'); 
})->middleware('auth')->name('CambiarContrasenia');   

Route::get('/CambioContrasenia',function () { 
    return view('Sistema/CambiarContrasenia/CambiarContrasenia'); 
})->middleware('auth');   


//Envio formulario post cambio contrasenia inspectores
Route::post('/Sistema/CambiarContrasenia', [CambiarContController::class, 'index'])->middleware('auth')->name('FormContrasenia');
//Envio formulario post cambio correo inspectores
Route::post('/Sistema/CambiarCorreoinsp', [CambiarCorreoControler::class, 'index'])->middleware('auth')->name('FormCorreo');














 

