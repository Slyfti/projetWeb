<?php

namespace App\Http\Requests\Settings;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['nullable', 'string', 'max:255'],
            'dateNaissance' => ['nullable', 'date'],
            'sexe' => ['nullable', 'in:Homme,Femme,Autre'],
            'typeMembre' => ['required', 'in:Spectateur,Athlète,Entraîneur,Personnel technique,Sécurité,Administratif'],
            'niveau' => ['required', 'in:Débutant,Intermédiaire,Avancé,Expert'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }
}
