<?php

namespace App\Repositories\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait HasTeamFeatures
{

    /**
     * @param User|null $user
     * @return mixed
     */
    protected function getActiveTeam( User $user = null ) {
        if ( ! $user ) {
            $user = Auth::user();
        }

        return $user->currentTeam;
    }

}
