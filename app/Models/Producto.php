<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    
    protected $table = 'productos';
    
    public $timestamps = false;
    
    protected $fillable = ['nombre','precio', 'IDMarca', 'IDTipo', 'unidadesDisponibles', 'mimetype', 'filename'];
    
    
    public function tipo (){
        return $this->belongsTo ('App\Models\TipoProducto', 'id');
    }
    
    public function marca (){
        return $this->belongsTo ('App\Models\MarcaProducto', 'id');
    }
    
    public function pedidoProducto (){
        return $this->hasMany ('App\Models\PedidoProducto', 'IDProducto');
    }
}
