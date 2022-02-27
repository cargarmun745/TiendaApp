<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoProducto extends Model
{
    use HasFactory;
    
    protected $table = 'tipo_productos';
    
    public $timestamps = false;
    
    protected $fillable = ['nombre'];
    
    
    public function productos (){
        return $this->hasMany ('App\Models\Producto', 'IDTipo');
    }
}
