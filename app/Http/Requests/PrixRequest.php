<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PrixRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'prix_kg' => 'required|numeric|min:1',
            'date_debut' => 'required|date',
            'id_variete' => 'required|exists:variete,id_variete',
        ];
    }

    public function messages(): array
    {
        return [
            'prix_kg.required' => 'Le prix est obligatoire.',
            'prix_kg.min' => 'Le prix doit être supérieur à 0.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'id_variete.exists' => 'La variété sélectionnée n\'existe pas.',
        ];
    }
}