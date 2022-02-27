<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CargoCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function atributes()
    {
        return [
            'nombre'       =>  'nombre del cargo',
            'descripcion'  =>  'descripcion del cargo',   
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
        $numeric = 'El campo :attribute debe ser numérico.';
        $unique = 'El campo :attribute debe ser único en la tabla de cargos.';
        return [
            'nombre.required'          => $required,
            'nombre.min'               => $min,
            'nombre.max'               => $max,
            'nombre.unique'            => $unique,
            'descripcion.required'     => $required,
            'descripcion.min'          => $min,
            'descripcion.max'          => $max,
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
            'nombre'         =>  'required|min:2|max:50|unique:cargo_empleados,nombre',
            'descripcion'    =>  'required|min:2|max:250',
        ];
    }
}
