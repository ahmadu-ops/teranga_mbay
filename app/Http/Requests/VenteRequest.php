<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VenteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_vente' => 'required|date',
            'quantite_vendu' => 'required|numeric|min:0.01',
            'nom_client' => 'nullable|string|max:100',
            'id_variete' => 'required|exists:variete,id_variete',
        ];
    }

    public function messages(): array
    {
        return [
            'date_vente.required' => 'La date de vente est obligatoire.',
            'quantite_vendu.required' => 'La quantité est obligatoire.',
            'quantite_vendu.min' => 'La quantité doit être supérieure à 0.',
            'id_variete.exists' => 'La variété sélectionnée n\'existe pas.',
        ];
    }
}