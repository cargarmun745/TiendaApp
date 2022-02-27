<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarcaProducto extends Model
{
    use HasFactory;
    
    protected $table = 'marca_productos';
    
    public $timestamps = false;
    
    protected $fillable = ['nombre'];
    
    
    public function productos (){
        return $this->hasMany ('App\Models\Producto', 'IDMarca');
    }
}
