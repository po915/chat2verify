<?php

namespace App\Http\Requests\PhoneNumbers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class SearchPhoneNumbers extends FormRequest
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
            'country' => [ 'required', 'string', 'max:4' ],
            'area_code' => [ 'required', 'string', 'sometimes', 'nullable' ],
        ];
    }
}
