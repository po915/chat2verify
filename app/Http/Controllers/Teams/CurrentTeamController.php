<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Laravel\Jetstream\Jetstream;

class CurrentTeamController extends Controller
{
    /**
     * Update the authenticated user's current team.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, UserRepository $userRepository )
    {
        $team = Jetstream::newTeamModel()->findOrFail($request->team_id);

        if (! $request->user()->switchTeam($team)) {
	        $request->user()->forceFill([ 'current_team_id' => null ])->save();
	        return redirect()->route( 'home' );
        }

        if ( $phone_number = $team->phoneNumbers()->first() ) {
            $userRepository->setActivePhoneNumber( $request->user(), $phone_number->id );
        } else {
            $request->user()->forceFill([ 'current_phone_number_id' => null ])->save();
        }

        return Redirect::back(303);
    }
}
