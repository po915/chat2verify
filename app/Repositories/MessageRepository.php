<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Models\Conversation;
use App\Models\PhoneNumber;
use App\Models\Team;
use App\Models\User;
use App\Repositories\Interfaces\MessageRepositoryInterface;
use App\Repositories\Messages\MessageComposer;
use App\Repositories\Traits\HasTeamFeatures;
use Illuminate\Support\Facades\Auth;

/**
 * Class MessageRepository
 * @package App\Repositories
 */
class MessageRepository implements MessageRepositoryInterface
{

    use HasTeamFeatures;

    /**
     * @param $to
     * @param $from
     * @param $message
     * @return MessageComposer
     */
    public function compose($to, $from, $message ) : MessageComposer {
        return new MessageComposer( $to, $from, $message );
    }

    /**
     * @param Team|null $team
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createConversation( Team $team = null, $attributes = [] ) {
        $team = $team ?: $this->getActiveTeam();

        $conversation = $team->conversations()->create( array_merge( [
            'starting_user_id' => Auth::check() ? Auth::user()->id : null,
            'team_id' => $team->id,
            'phone_number' => '',
        ], $attributes ) );

        return $conversation;
    }

    /**
     * @return mixed
     */
    public function getConversations() {
        $user = $this->getUser();

        if ( ! $user->currentPhoneNumber ) {
            return [];
        }

        return $user->currentPhoneNumber->conversations()->orderByDesc('updated_at')->paginate(40);
    }

    /**
     * @return mixed
     */
    public function getArchivedConversations() {
        $user = $this->getUser();

        if ( ! $user->currentPhoneNumber ) {
            return [];
        }

        return $user->currentPhoneNumber->conversations()->onlyTrashed()->orderByDesc('updated_at')->paginate(40);
    }

    /**
     * @return mixed
     */
    public function getAllConversations() {
        $team = $this->getActiveTeam();

        return $team->conversations()->orderByDesc('id')->get();
    }

    /**
     * @return mixed
     */
    public function getLatestConversation() {
        $user = $this->getUser();

        if ( ! $user->currentPhoneNumber ) {
            return null;
        }

        return $user->currentPhoneNumber->conversations()->orderByDesc('updated_at')->first();
    }

    /**
     * @param $number
     * @return mixed
     */
    public function getConversationByPhoneNumber($number ) {
        return Conversation::where( 'phone_number', $number )->first();
    }

    /**
     * @param $number
     * @return false|\Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
     */
    public function getTeamByPhoneNumber($number ) {
        $phone_number = PhoneNumber::query()->where( 'phone_number', $number )->first();

        if ( ! $phone_number ) {
            return false;
        }

        return $phone_number->team;
    }

    /**
     * @param $number
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function getContactByPhoneNumber($number ) {
        return Contact::query()->where( 'phone_number', $number )->first();
    }

    /**
     * @param User|null $user
     * @return mixed
     */
    protected function getCurrentPhoneNumber(User $user = null ) {
        if ( ! $user ) {
            $user = Auth::user();
        }

        return $user->currentPhoneNumber;
    }

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function getUser() {
        return Auth::user();
    }

}
