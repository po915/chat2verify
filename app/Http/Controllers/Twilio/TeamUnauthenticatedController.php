<?php

namespace App\Http\Controllers\Twilio;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeamUnauthenticatedController extends Controller
{
    public function show() {
        return Inertia::render( 'Teams/TwilioUnauthenticated' );
    }
}
