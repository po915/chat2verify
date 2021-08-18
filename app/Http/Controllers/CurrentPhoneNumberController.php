<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhoneNumbers\UpdateCurrentPhoneNumber;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Redirect;

/**
 * Class CurrentPhoneNumberController
 * @package App\Http\Controllers
 */
class CurrentPhoneNumberController extends Controller
{

    /**
     * @param UserRepository $repository
     * @param UpdateCurrentPhoneNumber $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRepository $repository, UpdateCurrentPhoneNumber $request ) {
        $input = $request->validated();

        $repository->setActivePhoneNumber( $request->user(), $input[ 'phone_number' ] );

        return Redirect::route('conversations.index');
    }
}
