<?php

namespace App\Http\Requests\Conversations;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SendMessage extends FormRequest
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

        return $team->id === $conversation->team->id && $this->user()->hasTeamPermission( $team, 'send_messages' );
    }


    public function rules( Request $request )
    {
        return [
            'message' => [
                Rule::requiredIf(function () use ($request) {
                    return !$request->hasFile( 'attachment' );
                }),
                'sometimes',
                'nullable',
                'string'
            ],
            'attachment' => [ 'sometimes' ],
            'attachment.*' => [ 'image', 'mimes:jpg,jpeg,png,gif' ],
        ];
    }
}
