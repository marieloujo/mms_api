<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class RegisterRequest extends FormRequest
{

    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'integer', 'min:0','digits_between:8,11','unique:users'],
            'adresse' => ['sometimes', 'string', 'max:255'],
            'commune' => 'required',
            'sexe' => 'required',
        'date_naissance' => ['required'/*, 'date' ,'date_format:Y-m-d' /*,'before_or_equal:74 years','after_or_equal:18 years'*/ ],
            'situation_matrimoniale' => 'sometimes',
            'email' => ['sometimes', 'email', 'max:255','unique:users']
        ];

    }


}
