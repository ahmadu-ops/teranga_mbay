<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProduitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $produitId = $this->route('produit')?->id_produit;

        return [
            'nom_produit' => [
                'required',
                'string',
                'max:50',
                Rule::unique('produit', 'nom_produit')->ignore($produitId, 'id_produit'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nom_produit.required' => 'Le nom du produit est obligatoire.',
            'nom_produit.max' => 'Le nom ne peut pas dépasser 50 caractères.',
            'nom_produit.unique' => 'Ce produit existe déjà.',
        ];
    }
}