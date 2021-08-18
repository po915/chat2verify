<?php

namespace App\Http\Requests\Conversations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ArchiveConversation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize( Request $request )
    {
        $conversation = $this->route( 'conversation' );
        $team = $this->user()->currentTeam;

        return $team->id === $conversation->team->id && $this->user()->hasTeamPermission( $team, 'archive_conversations' );
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
