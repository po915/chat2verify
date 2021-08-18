<?php

namespace App\Http\Requests\Phrases;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhrase extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $phrase = $this->route( 'response' );
        $team = $this->user()->currentTeam;

        return $this->user()->can( 'edit_public_phrases' ) || $team->id === $phrase->team->id && $this->user()->hasTeamPermission( $team, 'edit_phrases' );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'shortcode' => [ 'sometimes', 'nullable', 'string', 'max:20' ],
            'friendly_name' => [ 'required', 'nullable', 'string', 'max:255' ],
            'message' => [ 'required', 'string' ],
            'public' => [ 'required', 'bool', 'sometimes', 'nullable' ],
        ];
    }
}
