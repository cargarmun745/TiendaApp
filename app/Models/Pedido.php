<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;
    
    protected $table = 'pedidos';
    
    public $timestamps = false;
    
    protected $fillable = ['metodoDePago', 'fechaPedido', 'IDCliente', 'IDEmpleado'];
    
    public function cliente (){
        return $this->belongsTo ('App\Models\Cliente', 'id');
    }
    
    public function empleado (){
        return $this->belongsTo ('App\Models\Empleado', 'id');
    }
    
    public function pedidoProducto (){
        return $this->hasMany ('App\Models\PedidoProducto', 'IDPedido');
    }
}
