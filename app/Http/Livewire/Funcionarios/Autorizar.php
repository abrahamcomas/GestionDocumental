<?php

namespace App\Http\Livewire\Funcionarios;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;  
use App\Models\FuncionarioModels;
use App\Models\Roles;
use App\Models\LugarDeTrabajo;
use Illuminate\Support\Facades\Auth; 
 
class Autorizar extends Component
{ 

    public $Ayuda=0; 
    
    public function Ayuda(){
        $this->Ayuda = 1;
    }
    public function VolverAyuda(){
        $this->Ayuda = 0;
    }
    
    use WithPagination;  
    public $search; 
  
    public function Aceptar($ID_Funcionario_T){ 

        $FuncionarioModels          = FuncionarioModels::find($ID_Funcionario_T);
        $FuncionarioModels->Activo  = 1;
        $FuncionarioModels->save();

        $ID_LugarDeTrabajo  =  DB::table('Funcionarios')
        ->leftjoin('LugarDeTrabajo', 'Funcionarios.ID_Funcionario_T', '=', 'LugarDeTrabajo.ID_Funcionario_LDT')
        ->select('ID_LugarDeTrabajo')
        ->where('ID_Funcionario_T', '=', $ID_Funcionario_T)->first();

        $FuncionarioModels               = LugarDeTrabajo::find($ID_LugarDeTrabajo->ID_LugarDeTrabajo);
        $FuncionarioModels->Estado_LDT   = 1;
        $FuncionarioModels->save();

    }  
 

    public function Eliminar($ID_Funcionario_T){

        $FuncionarioModels          = FuncionarioModels::find($ID_Funcionario_T);
        $FuncionarioModels->delete();

    }


    public $NombreDireccion;
    public $ListaFuncionariosOP;
    public $mostrar=0;
    protected $paginationTheme = 'bootstrap'; 
    public function render()
    {
        $ID_Funcionario  =  Auth::user()->ID_Funcionario_T;

        $OficinaPartes =  DB::table('OficinaPartes')
        ->select('Id_OP','ID_OP_LDT','ID_Jefatura')
        ->where('id_Funcionario_OP', '=', $ID_Funcionario)
        ->where('Original', '=', 1)->first(); 
               
        $this->ListaFuncionariosOP =  DB::table('Funcionarios')
            ->leftjoin('LugarDeTrabajo', 'Funcionarios.ID_Funcionario_T', '=', 'LugarDeTrabajo.ID_Funcionario_LDT')
            ->where('Activo', '=', 0)
            ->where('ID_DepDirecciones_LDT', '=', $OficinaPartes->ID_OP_LDT)->get();

        return view('livewire.funcionarios.autorizar',[
            'Lista' =>  DB::table('Funcionarios') 
                ->leftjoin('ImagenFirma', 'Funcionarios.ID_Funcionario_T', '=', 'ImagenFirma.id_Funcionario_T') 
                ->where(function($query) { 
                    $query->orwhere('Rut', 'like', "%{$this->search}%");
              }) 
            ->paginate(10),
        ]);
    }
}
