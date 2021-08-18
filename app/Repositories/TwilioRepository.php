<?php

namespace App\Repositories;

use App\Exceptions\FailedPhoneNumberPurchase;
use App\Exceptions\Teams\UnauthenticatedTwilioAccount;
use App\Exceptions\Twilio\PhoneNumbers\SearchResultsEmpty;
use App\Models\Team;
use App\Models\User;
use App\Repositories\Messages\MessageComposer;
use App\Repositories\Traits\HasTeamFeatures;
use Illuminate\Support\Collection;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Rest\Api\V2010\Account\AvailablePhoneNumberCountry\LocalInstance;
use Twilio\Rest\Client;

/**
 * Class TwilioRepository
 * @package App\Repositories
 */
class TwilioRepository
{
    use HasTeamFeatures;

    /**
     * @param string $countryCode
     * @param null $areaCode
     * @param int $limit
     * @return LocalInstance[]
     * @throws ConfigurationException
     * @throws UnauthenticatedTwilioAccount
     */
    public function searchPhoneNumbers( $countryCode = "US", $areaCode = null, $limit = 20, Team $team = null ) {
        $client = $this->getTeamTwilioClient( $team );

        $arguments = [
            'mmsEnabled' => true,
            'smsEnabled' => true,
        ];

        if ( $areaCode ) {
            $arguments[ 'areaCode' ] = $areaCode;
        }

        $numbers = $client->availablePhoneNumbers( $countryCode )->local->read( $arguments, $limit );

        return collect($numbers)->map( function( $item ) {
            return [
                'locality' => trim( trim( sprintf( '%s, %s, %s', $item->locality, $item->region, $item->isoCountry ) ), ',' ),
                'friendly_name' => $item->friendlyName,
                'phone_number' => $item->phoneNumber,
                'capabilities' => $item->capabilities,
            ];
        });
    }

    /**
     * @param string $country
     * @throws ConfigurationException
     * @throws SearchResultsEmpty
     * @throws UnauthenticatedTwilioAccount
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function purchaseRandomPhoneNumber($country = "US", Team $team = null ) {
        $client = $this->getTeamTwilioClient( $team );

        $phone_numbers = $this->searchPhoneNumbers( $country, null, 5 );

        if ( count( $phone_numbers ) === 0 ) {
            throw new SearchResultsEmpty;
        }

        $number = $phone_numbers->first();

        if ( ! $number ) {
            return false;
        }

        $client->incomingPhoneNumbers->create([
            'phoneNumber' => $number['phone_number'],
            'addressSid' => null,
            'addressRequirements' => 'none',
            'smsMethod' => 'GET',
            'smsUrl' =>  route( 'twilio.incoming-message' ),
        ]);

        return $number;
    }

	/**
	 * @param $phone_number
	 * @param Team|null $team
	 * @return \Twilio\Rest\Api\V2010\Account\IncomingPhoneNumberInstance
	 * @throws ConfigurationException
	 * @throws UnauthenticatedTwilioAccount
	 * @throws \Twilio\Exceptions\TwilioException
	 */
    public function purchasePhoneNumber($phone_number, Team $team = null ) {
        $client = $this->getTeamTwilioClient( $team );

        return $client->incomingPhoneNumbers->create([
            'phoneNumber' => $phone_number,
            'addressSid' => null,
            'addressRequirements' => 'none',
            'smsMethod' => 'GET',
            'smsUrl' =>  route( 'twilio.incoming-message' ),
        ]);
    }

	/**
	 * A collection of phone numbers that the user owns.
	 *
	 * @param int $limit
	 * @param Team|null $team
	 * @return Collection
	 * @throws ConfigurationException
	 * @throws UnauthenticatedTwilioAccount
	 */
    public function getPhoneNumbers( $limit = 100, Team $team = null  ) : Collection {
		try {
			$client = $this->getTeamTwilioClient( $team );

			$numbers = collect( $client->incomingPhoneNumbers->local->read( [], $limit ) );
		} catch( UnauthenticatedTwilioAccount $twilioAccount ) {
			throw new UnauthenticatedTwilioAccount();
		} catch( \Exception $exception ) {
			// Do Nothing
			return collect( [] );
		}

        return $numbers->map( function( $item ) {
            return [
                'status' => $item->status,
                'sid' => $item->sid,
                'friendly_name' => $item->friendlyName,
                'phone_number' => $item->phoneNumber,
                'capabilities' => $item->capabilities,
                'created_at' => $item->dateCreated,
                'updated_at' => $item->dateUpdated,
                'address_requirements' => $item->addressRequirements,
                'sms_url' => $item->smsUrl,
                'sms_method' => $item->smsMethod,
                'external' => $item->smsUrl !== route( 'twilio.incoming-message' ),
            ];
        });
    }

//	/**
//	 * @throws \Twilio\Exceptions\TwilioException
//	 * @throws UnauthenticatedTwilioAccount
//	 * @throws ConfigurationException
//	 */
//	public function releasePhoneNumber( string $sid, Team $team = null  ) {
//	    $client = $this->getTeamTwilioClient( $team );
//
//	    if ( empty( $sid ) ) {
//	    	return false;
//	    }
//
//	    return $client->incomingPhoneNumbers( $sid )->delete();
//    }

    public function importPhoneNumber( string $sid, Team $team = null  ) {
	    $client = $this->getTeamTwilioClient( $team );

	    return $client->incomingPhoneNumbers( $sid )->update([
	    	'smsUrl' => route( 'twilio.incoming-message' ),
		    'smsMethod' => 'GET',
	    ]);
    }

	/**
	 * @param MessageComposer $composer
	 * @param Team|null $team
	 * @return \Twilio\Rest\Api\V2010\Account\MessageInstance
	 * @throws ConfigurationException
	 * @throws UnauthenticatedTwilioAccount
	 * @throws \Twilio\Exceptions\TwilioException
	 */
    public function sendSms(MessageComposer $composer, Team $team = null  ) {
        $client = $this->getTeamTwilioClient( $team );

        return $client->messages->create( $composer->getTo(), $composer->toArray() );
    }

	/**
	 * @param User|null $user
	 * @param Team|null $team
	 * @return Client
	 * @throws ConfigurationException
	 * @throws UnauthenticatedTwilioAccount
	 */
    protected function getTeamTwilioClient( Team $team = null, User $user = null ) : Client {
        $team = $team ?: $this->getActiveTeam( $user );

        if ( ! $team || ! $team->twilio_account_id || ! $team->twilio_auth_token ) {
            throw new UnauthenticatedTwilioAccount;
        }

        return new Client( $team->twilio_account_id, $team->twilio_auth_token );
    }

}
