<?php

namespace App\Http\Requests\Twilio;

use Illuminate\Foundation\Http\FormRequest;

class IncomingMessage extends FormRequest
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
            'To' => [ 'required', 'string' ],
            'From' => [ 'required', 'string' ],
            'Body' => [ 'required_without:MediaUrl0', 'sometimes', 'string', 'nullable'],
            'MediaUrl0' => [ 'required', 'nullable', 'sometimes' ],
        ];
    }
}
