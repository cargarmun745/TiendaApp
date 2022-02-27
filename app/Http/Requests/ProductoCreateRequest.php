<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductoCreateRequest extends FormRequest
{
    public function atributes()
    {
        return [
            'nombre'                =>  'nombre del producto',
            'precio'                =>  'precio del producto',
            'unidadesDisponibles'   =>  'unidades disponible del producto',
            'IDMarca'               =>  'marca del producto',
            'IDTipo'                =>  'tipo del producto',
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
        $max = 'El campo :attribute no puede tener más de :max caracteres.';
        $min = 'El campo :attribute no puede tener menos de :min caracteres.';
        $gte = 'El campo :attribute debe ser mayor o igual que :value.';
        $integer = 'El campo :attribute ha de ser un numero entero.';
        $lte = 'El campo :attribute debe ser menor o igual que :value.';
        $required = 'El campo :attribute es obligatorio.';
        $numeric = 'El campo :attribute debe ser numérico.';
        $date = 'El campo :attribute no es un formato correcto para una fecha.';
        $exitsmarca = 'El campo :attribute no existe en la tabla de marcas.';
        $exitstipo = 'El campo :attribute no existe en la tabla de tipos.';
        $unique = 'El campo :attribute debe ser único en la tabla de productos.';
        
        return [
            'nombre.required'               => $required,
            'nombre.min'                    => $min,
            'nombre.max'                    => $max,
            'nombre.unique'                 => $unique,
            
            'precio.required'               => $required,
            'precio.gte'                    => $gte,
            'precio.lte'                    => $lte,
            'precio.numeric'                => $numeric,
            
            'unidadesDisponibles.required'  => $required,
            'unidadesDisponibles.integer'   => $integer,
            
            'IDMarca.exits'                 => $exitsmarca,
            'IDMarca.integer'               => $integer,
            
            'IDTipo.exits'                  => $exitstipo,
            'IDTipo.integer'                => $integer,
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
                'nombre'                =>  'required|min:2|max:50|unique:productos,nombre',
                'precio'                =>  'required|gte:0.01|lte:9999999.99|numeric',
                'unidadesDisponibles'   =>  'required|gte:0|lte:9999999|integer',
                'IDMarca'               =>  'integer|exists:marca_productos,id',
                'IDTipo'                =>  'nullable|integer|exists:tipo_productos,id',
            ];
    }
}
