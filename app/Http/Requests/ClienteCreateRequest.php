<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteCreateRequest extends FormRequest
{
    public function atributes()
    {
        return [
            'nombre'      =>  'nombre del cliente',
            'apellidos'   =>  'apellidos del cliente',
            'DNI'         =>  'DNI del cliente',
            'direccion'   =>  'dirección del cliente',
            'IDUsuario'   =>  'usuario del cliente',
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
        $required = 'El campo :attribute es obligatorio.';
        $unique = 'El campo :attribute debe ser único en la tabla de clientes.';
        $exits = 'El campo :attribute no existe en la tabla de usuarios.';
        $integer = 'El campo :attribute ha de ser un numero entero.';
        return [
            'nombre.required'        => $required,
            'nombre.min'             => $min,
            'nombre.max'             => $max,
            'apellidos.required'     => $required,
            'apellidos.min'          => $min,
            'apellidos.max'          => $max,
            'direccion.required'     => $required,
            'direccion.min'          => $min,
            'direccion.max'          => $max,
            'DNI.required'           => $required,
            'DNI.min'                => $min,
            'DNI.max'                => $max,
            'DNI.unique'             => $unique,
            'IDUsuario.exits'        => $exits,
            'IDUsuario.integer'      => $integer,
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
                'nombre'    =>  'required|min:2|max:50',
                'apellidos' =>  'required|min:2|max:50',
                'direccion' =>  'required|min:5|max:100',
                'DNI'       =>  'required|min:9|max:9|unique:clientes,DNI',
                'IDUsuario' =>  'nullable|integer|exists:users,id',
            ];
    }
}
