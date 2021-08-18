<?php

namespace App\Http\Requests\PhoneNumbers;

use App\Models\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCurrentPhoneNumber extends FormRequest
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
            'phone_number' => [ 'required', 'int', 'exists:' . PhoneNumber::class . ',id' ],
        ];
    }
}
