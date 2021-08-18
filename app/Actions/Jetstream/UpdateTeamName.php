<?php

namespace App\Actions\Jetstream;

use App\Exceptions\Teams\UnauthenticatedTwilioAccount;
use App\Repositories\TwilioRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\UpdatesTeamNames;
use Twilio\Exceptions\ConfigurationException;

class UpdateTeamName implements UpdatesTeamNames
{
    /**
     * Validate and update the given team's name.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param  array  $input
     * @return void
     */
    public function update( $user, $team, array $input)
    {
        $twilioRepository = resolve( TwilioRepository::class );

        Gate::forUser($user)->authorize('update', $team);

        Validator::make($input, [
            'name' => [ 'sometimes', 'required', 'string', 'max:255'],
        ])->validateWithBag('updateTeamName');

        // Twilio settings
        Validator::make($input, [
            'twilio_account_id' => ['required', 'sometimes', 'string', 'max:255'],
            'twilio_auth_token' => ['required', 'sometimes', 'string', 'max:255'],
        ])->validateWithBag('updateTeamTwilio');

        if ( isset( $input[ 'twilio_account_id' ] ) ) {
            $team->forceFill([
                'twilio_account_id' => Crypt::encryptString( $input['twilio_account_id'] ),
                'twilio_auth_token' => Crypt::encryptString( $input['twilio_auth_token'] ),
            ])->save();

            // Check connection
            try {
                $phone_numbers = $twilioRepository->searchPhoneNumbers("US");

                $team->forceFill([
                    'twilio_authentication_verified' => true,
                ])->save();

                return Redirect::back()->banner( 'We were able to successfully connect to Twilio. You may now purchase phone numbers and start using our messaging features.' );
            } catch( \Exception $e ) {
                $team->forceFill([
                    'twilio_authentication_verified' => false,
                ])->save();

                Log::info( 'Reported API Error: ' . $e->getMessage() );

                return Redirect::back()->dangerBanner( 'We was unable to connect to Twilio. Please ensure your account ID and authentication token are correct.' );
            }
        }

        $team->forceFill([
            'name' => $input['name'],
        ])->save();
    }
}
