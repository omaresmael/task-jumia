<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CountryRequest extends FormRequest
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

            'sync' =>'required|integer',
            'length' =>'required|integer',
            'searchCountry' =>'nullable|string',
            'searchState' =>'nullable|boolean',
            'column' =>'required|integer',
            'dir' => [
                'required',
                    Rule::in(['desc', 'asc']),
            ],
        ];
    }
}
