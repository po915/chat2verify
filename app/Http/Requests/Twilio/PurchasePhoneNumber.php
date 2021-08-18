<?php

namespace App\Http\Requests\Twilio;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PurchasePhoneNumber extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize( Request $request )
    {
        $team = $request->user()->currentTeam;

        return $request->user()->hasTeamPermission( $team, 'purchase_numbers' );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'option' => [ 'required', 'string', 'in:random_number,local_number'],
            'phone_number' => [ 'nullable', 'string' ],
            'country' => [ 'required', 'string' ],
            'description' => [ 'sometimes', 'nullable', 'string', 'max:60', ],
        ];
    }
}
