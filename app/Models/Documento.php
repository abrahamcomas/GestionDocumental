<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

     //referencia a una tabla
     protected $table="Portafolio";
     protected $primaryKey="ID_Documento_T";
 
     //pongo los caampos para permitir insert multiple
     protected $fillable=[
         "ID_Funcionario_Sol",
         "NumeroInterno",
         "Folio",
         "Estado_T",
         "Titulo_T",
         "Tipo_T",
         "Fecha_T",
         "Anio",
         "FechaUrgencia_T",
         "Observacion_T"

     ]; 
}
 