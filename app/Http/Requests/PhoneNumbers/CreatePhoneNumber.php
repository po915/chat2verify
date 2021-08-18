<?php

namespace App\Http\Requests\PhoneNumbers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CreatePhoneNumber extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize( Request $request )
    {
        $team = $request->user()->currentTeam;

        dd( $team );
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone_number' => [ 'required', 'phone' ],
            'phone_number_country' => [ 'required_with:phone_number' ],
            'friendly_name' => [ 'string', 'nullable', 'max:30' ],
            'description' => [ 'string', 'nullable', 'max:255' ],
        ];
    }
}
