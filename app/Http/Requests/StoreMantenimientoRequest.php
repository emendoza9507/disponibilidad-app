<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMantenimientoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'codigoot' => 'required|unique:mantenimientos',
            'matricula' => 'required',
            'created_at' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'codigoot.unique' => 'Ya existe este mantenimiento',
            'matricula.required' => 'La matricula es requerida'
        ];
    }
}
