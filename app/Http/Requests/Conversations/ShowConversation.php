<?php

namespace App\Http\Requests\Conversations;

use Illuminate\Foundation\Http\FormRequest;

class ShowConversation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $conversation = $this->route( 'conversation' );
        $team = $this->user()->currentTeam;

        return $team->id === $conversation->team->id && $this->user()->hasTeamPermission( $team, 'read_messages' );
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
