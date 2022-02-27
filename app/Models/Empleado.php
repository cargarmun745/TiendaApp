<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;
    
    protected $table = 'empleados';
    
    public $timestamps = false;
    
    protected $fillable = ['nombre','apellidos', 'DNI', 'fechacontrato', 'IDCargo'];
    
    
    public function cargoempleado (){
        return $this->belongsTo ('App\Models\CargoEmpleado', 'id');
    }
    
    
    public function pedidos (){
        return $this->hasMany ('App\Models\Empleado', 'IDEmpleado');
    }
    
    public function usuario (){
        return $this->belongsTo ('App\Models\User', 'id');
    }
}
