<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoProducto extends Model
{
    use HasFactory;
    
    protected $table = 'pedido_productos';
    
    protected $fillable = ['nombreProducto', 'precioProducto', 'cantidad', 'IDPedido', 'IDProducto'];
    
    public function pedido (){
        return $this->belongsTo ('App\Models\Pedido', 'id');
    }
    
    public function producto (){
        return $this->belongsTo ('App\Models\Producto', 'id');
    }
}
