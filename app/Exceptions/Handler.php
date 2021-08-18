<?php

namespace App\Exceptions;

use App\Exceptions\Teams\UnauthenticatedTwilioAccount;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        UnauthenticatedTwilioAccount::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
//        'twilio_account_id',
//        'twilio_authentication_token',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
//        $this->renderable( function( UnauthenticatedTwilioAccount $e, $request ) {
//            $team = $request->user()->currentTeam;
//
//            return redirect()->route( 'twilio.unauthenticated' )->withErrors([
//                'There was an error'
//            ]);
//        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
