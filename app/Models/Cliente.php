<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    
    protected $table = 'clientes';
    
    public $timestamps = false;
    
    
    protected $fillable = ['nombre','apellidos', 'DNI', 'direccion', 'IDUsuario'];
    
    
    
    public function pedidos (){
        return $this->hasMany ('App\Models\Pedido', 'IDCliente');
    }
    
    public function usuario (){
        return $this->belongsTo ('App\Models\User', 'id');
    }
}
