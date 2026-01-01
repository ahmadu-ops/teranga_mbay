<?php

namespace App\Http\Requests;

use App\Models\Perte;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PerteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_perte' => 'required|date',
            'quantite_perdue' => 'required|numeric|min:0.01',
            'motif_perte' => ['required', Rule::in(Perte::MOTIFS)],
            'id_variete' => 'required|exists:variete,id_variete',
        ];
    }

    public function messages(): array
    {
        return [
            'date_perte.required' => 'La date de perte est obligatoire.',
            'quantite_perdue.required' => 'La quantité est obligatoire.',
            'quantite_perdue.min' => 'La quantité doit être supérieure à 0.',
            'motif_perte.required' => 'Le motif est obligatoire.',
            'motif_perte.in' => 'Le motif sélectionné n\'est pas valide.',
            'id_variete.exists' => 'La variété sélectionnée n\'existe pas.',
        ];
    }
}