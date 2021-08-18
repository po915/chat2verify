<?php

namespace App\Repositories;

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
class UserRepository
{

    use HasTeamFeatures;

    public function setActivePhoneNumber( User $user, $phone_number_id ) {
        // Check the phone number id exists
        $phone_number = PhoneNumber::findOrFail( $phone_number_id );

        $user->forceFill([
            'current_phone_number_id' => $phone_number->id,
        ]);

        $user->save();
    }

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected function getUser() {
        return Auth::user();
    }

}
