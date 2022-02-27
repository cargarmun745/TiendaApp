<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpleadoEditUserRequest extends FormRequest
{
    public function atributes()
    {
        return [
            'nombre'            =>  'nombre del empleado',
            'apellidos'         =>  'apellidos del empleado',
            'DNI'               =>  'DNI del empleado',
            'fechacontrato'     =>  'dirección del empleado',
            'IDUsuario'         =>  'usuario del empleado',
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
        $unique = 'El campo :attribute debe ser único en la tabla de empleados.';
        $exits = 'El campo :attribute no existe en la tabla de usuarios.';
        $date = 'El campo :attribute no es un formato correcto para una fecha.';
        $integer = 'El campo :attribute ha de ser un numero entero.';
        
        return [
            'nombre.required'        => $required,
            'nombre.min'             => $min,
            'nombre.max'             => $max,
            'apellidos.required'     => $required,
            'apellidos.min'          => $min,
            'apellidos.max'          => $max,
            'fechacontrato.required' => $required,
            'fechacontrato.date'     => $date,
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
                'nombre'        =>  'required|min:2|max:50',
                'apellidos'     =>  'required|min:2|max:50',
                'fechacontrato' =>  'required|date',
                'DNI'           =>  'required|min:9|max:9|unique:empleados,DNI,' . auth()->user()->id,
                'IDUsuario'     =>  'nullable|integer|exists:users,id',
            ];
    }
}
