<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargoEmpleado extends Model
{
    use HasFactory;
    
    protected $table = 'cargo_empleados';
    
    public $timestamps = false;
    
    protected $fillable = ['nombre','descripcion'];
    
    
    public function empleados (){
        return $this->hasMany ('App\Models\Empleado', 'IDCargo');
    }
}
