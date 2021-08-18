<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhoneNumbers\SearchPhoneNumbers;
use App\Http\Requests\Twilio\PurchasePhoneNumber;
use App\Models\PhoneNumber;
use App\Repositories\TwilioRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Inertia\Inertia;

class PhoneNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index( TwilioRepository $twilioRepository, Request $request )
    {
	    $numbers = $twilioRepository->getPhoneNumbers( 100 )->toArray();

	    $team_numbers = $request->user()->currentTeam->phoneNumbers()->whereNotNull( 'sid' )->get();

	    foreach ( $numbers as $key => $number ) {
	    	if ( ! isset( $number['sid'] ) ) {
	    		continue;
		    }

			if ( $team_number = $team_numbers->where( 'sid', $number['sid'] )->first() ) {
				$numbers[ $key ][ 'id' ] = $team_number->id;
			}
	    }

	    return Inertia::render( 'PhoneNumbers/Import', [
		    'numbers' => $numbers,
	    ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    public function search( TwilioRepository $twilioRepository, SearchPhoneNumbers $request ) {
        $data = $request->validated();

        $numbers = $twilioRepository->searchPhoneNumbers( $data[ 'country' ], isset( $data['area_code'] ) ? $data[ 'area_code' ] : null );

        return Response::json([
            'success' => true,
            'numbers' => $numbers,
        ]);
    }

    public function purchase( TwilioRepository $twilioRepository, PurchasePhoneNumber $request ) {
        $data = $request->validated();

       if ( $data['phone_number'] ) {
           $number = $twilioRepository->purchasePhoneNumber( $data['phone_number' ] );

           if ( $number ) {
               $request->user()->currentTeam->phoneNumbers()->create([
                   'user_id' => $request->user()->id,
                   'phone_number' => $data['phone_number'],
                   'friendly_name' => isset( $number->friendlyName ) ? $number->friendlyName : $data['phone_number'],
                   'description' => isset( $data[ 'description' ] ) ? $data[ 'description' ] : null,
               ]);

               return Redirect::route( 'conversations.index' )->banner(
                   __('The phone number :number has been successfully purchased.', [ 'number' => $data['phone_number'] ] ),
               );
           } else {
               return Redirect::route( 'conversations.index' )->dangerBanner(
                   __('There was an error trying to purchase the number :number. Please check your Twilio account.', [ 'number' => $data['phone_number'] ] ),
               );
           }
       } else {
           $number = $twilioRepository->purchaseRandomPhoneNumber( $data[ 'country' ] );

           if ( $number ) {
               $request->user()->currentTeam->phoneNumbers()->create([
                   'user_id' => $request->user()->id,
                   'phone_number' => $number['phone_number'],
                   'friendly_name' => $number['phone_number'],
                   'description' => isset( $number['locality'] ) ? $number['locality'] : null,
               ]);

               return Redirect::route( 'conversations.index' )->banner(
                   __('The phone number :number has been successfully purchased.', [ 'number' => $number['phone_number'] ] ),
               );
           } else {
               return Redirect::route( 'conversations.index' )->dangerBanner(
                   __('There was an error trying to purchase a phone number. Please check your Twilio account.' ),
               );
           }
       }
    }

    public function import( TwilioRepository $twilioRepository, Request $request, $sid ) {
    	$number = $twilioRepository->importPhoneNumber( $sid );

	     $test = $request->user()->currentTeam->phoneNumbers()->updateOrCreate([
		    'phone_number' => $number->phoneNumber,
	    ], [
		    'user_id' => $request->user()->id,
		    'sid' => $number->sid,
		    'friendly_name' => $number->friendlyName,
		    'description' => isset( $number->origin ) ? $number->origin : null,
	    ]);

	    return Redirect::route( 'phone-numbers.index' )->banner(
		    __('The phone number :number has been successfully imported.', [ 'number' => $number->phoneNumber ] ),
	    );
    }

	/**
	 * @throws \Twilio\Exceptions\TwilioException
	 * @throws \App\Exceptions\Teams\UnauthenticatedTwilioAccount
	 * @throws \Twilio\Exceptions\ConfigurationException
	 */
//	public function release( TwilioRepository $twilioRepository, Request $request, $sid ) {
//	    if ( empty( $phoneNumber->sid ) ) {
//		    return redirect()->back()->dangerBanner( 'This number could not be released as we did not manage to find an SID. Please release the number manually through your Twilio dashboard.' );
//	    }
//
//		$model = $request->user()->currentTeam->phoneNumbers()->where( 'sid', $sid )->firstOrFail();
//
//		$number = $twilioRepository->releasePhoneNumber( $sid );
//
//		$phoneNumber = $model->phone_number;
//
//	    $model->delete();
//
//	    return Redirect::route( 'phone-numbers.index' )->banner(
//		    __('The phone number :number has been successfully released.', [ 'number' => $phoneNumber ] ),
//	    );
//    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PhoneNumber  $phoneNumber
     * @return \Illuminate\Http\Response
     */
    public function show(PhoneNumber $phoneNumber)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PhoneNumber  $phoneNumber
     * @return \Illuminate\Http\Response
     */
    public function edit(PhoneNumber $phoneNumber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PhoneNumber  $phoneNumber
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PhoneNumber $phoneNumber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PhoneNumber  $phoneNumber
     * @return \Illuminate\Http\Response
     */
    public function destroy(PhoneNumber $phoneNumber)
    {
    	$number = $phoneNumber->friendly_name;

    	$phoneNumber->delete();

    	return redirect()->back()->banner( 'We have successfully unlinked the number :number from your Chat2Verify account. You will still be charged by Twilio for the number until you release it.', [ 'number' => $number ] );
    }
}
