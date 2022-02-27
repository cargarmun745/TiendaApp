<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TipoCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function atributes()
    {
        return [
            'nombre'       =>  'nombre del tipo',
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
        $unique = 'El campo :attribute debe ser único en la tabla de tipos.';
        return [
            'nombre.required'          => $required,
            'nombre.min'               => $min,
            'nombre.max'               => $max,
            'nombre.unique'            => $unique,
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
            'nombre'         =>  'required|min:2|max:50|unique:tipo_productos,nombre',
        ];
    }
}
