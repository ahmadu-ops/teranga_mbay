<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecolteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_recolte' => 'required|date',
            'quantite_recolte' => 'required|numeric|min:0.01',
            'id_variete' => 'required|exists:variete,id_variete',
        ];
    }

    public function messages(): array
    {
        return [
            'date_recolte.required' => 'La date de récolte est obligatoire.',
            'quantite_recolte.required' => 'La quantité est obligatoire.',
            'quantite_recolte.min' => 'La quantité doit être supérieure à 0.',
            'id_variete.exists' => 'La variété sélectionnée n\'existe pas.',
        ];
    }
}