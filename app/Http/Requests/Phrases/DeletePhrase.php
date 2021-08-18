<?php

namespace App\Http\Requests\Phrases;

use Illuminate\Foundation\Http\FormRequest;

class DeletePhrase extends FormRequest
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

        return $this->user()->can( 'delete_public_phrases' ) || $team->id === $phrase->team->id && $this->user()->hasTeamPermission( $team, 'delete_phrases' );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
