<?php
 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cementerio extends Model
{
    use HasFactory;

    protected $connection = 'cementerio'; 
    //referencia a una tabla
    protected $table="FichaFuncionario";
    protected $primaryKey="Id_Funcionario"; 

    //pongo los caampos para permitir insert multiple
    protected $fillable=[
    	"Email",
    	"password",
    	"CorreoActivo",
        "remember_token"
    ]; 
    
    public $timestamps = false;
}
