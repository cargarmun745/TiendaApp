<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PedidoEditRequest extends FormRequest
{
    public function atributes()
    {
        return [
            'precioTotal'    =>  'precio total del pedido',
            'metodoDePago'   =>  'método de pago del pedido',
            'fechaPedido'    =>  'fecha de realización del pedido',
            'IDCliente'      =>  'cliente que ha realizado el pedido',
            'IDEmpleado'     =>  'empleado que ha realizado el pedido',
        ];
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function authorize()
    {
        return true;
    }


    public function messages() {
        $gte = 'El campo :attribute debe ser mayor o igual que :value.';
        $integer = 'El campo :attribute ha de ser un numero entero.';
        $lte = 'El campo :attribute debe ser menor o igual que :value.';
        $in = 'El campo :attribute no se encuentra entre los valores posibles.';
        $required = 'El campo :attribute es obligatorio.';
        $numeric = 'El campo :attribute debe ser numérico.';
        $date = 'El campo :attribute no es un formato correcto para una fecha.';
        $exitscliente = 'El campo :attribute no existe en la tabla de clientes.';
        $exitsempleado = 'El campo :attribute no existe en la tabla de empleados.';
        
        return [
            'precioTotal.gte'       => $gte,
            'precioTotal.lte'       => $lte,
            'precioTotal.numeric'   => $numeric,
            
            'metodoDePago.required' => $required,
            'metodoDePago.in'       => $in,
            
            'fechaPedido.date'      => $date,
            
            'IDCliente.exits'       => $exitscliente,
            'IDCliente.integer'     => $integer,
            
            'IDEmpleado.exits'      => $exitsempleado,
            'IDEmpleado.integer'    => $integer,
        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                
                'precioTotal'   =>  'gte:0.01|lte:9999999.99|numeric',
                'metodoDePago'  =>  'required|in:efectivo,tarjeta',
                'fechaPedido'   =>  'date',
                'IDCliente'     =>  'nullable|integer|exists:clientes,id',
                'IDEmpleado'     =>  'nullable|integer|exists:empleados,id',
            ];
    }
}
