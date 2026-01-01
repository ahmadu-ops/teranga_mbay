<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VarieteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom_variete' => 'required|string|max:50',
            'description' => 'nullable|string|max:255',
            'id_produit' => 'required|exists:produit,id_produit',
            'prix_kg' => 'nullable|numeric|min:0',
            'date_debut' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'nom_variete.required' => 'Le nom de la variété est obligatoire.',
            'id_produit.required' => 'Le produit est obligatoire.',
            'id_produit.exists' => 'Le produit sélectionné n\'existe pas.',
            'prix_kg.min' => 'Le prix doit être positif.',
        ];
    }
}