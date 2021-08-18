<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::bind('conversation', function ($id) {
    return \App\Models\Conversation::withTrashed()->find($id);
});

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require_once( 'jetstream.php' );

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return \Illuminate\Support\Facades\Redirect::route( 'conversations.index' );
})->name('dashboard');

Route::get( '/twilio/incoming-message', [ \App\Http\Controllers\Twilio\IncomingMessageController::class, 'handle' ] )->name( 'twilio.incoming-message' );

Route::group( ['middleware' => [ 'auth:sanctum', 'verified'] ], function() {
    Route::get( '/responses/search', [ \App\Http\Controllers\PhraseController::class, 'search' ])->name( 'responses.search' );
    Route::get( '/phone-numbers/search', [ \App\Http\Controllers\PhoneNumberController::class, 'search' ])->name( 'phone-numbers.search' );
    Route::post( '/phone-numbers/purchase', [ \App\Http\Controllers\PhoneNumberController::class, 'purchase' ])->name( 'phone-numbers.purchase' );
	Route::get( '/phone-numbers/import/{number}', [ \App\Http\Controllers\PhoneNumberController::class, 'import' ])->name( 'phone-numbers.import-number' );

    Route::resource( 'phone-numbers', \App\Http\Controllers\PhoneNumberController::class );
    Route::resource( 'responses', \App\Http\Controllers\PhraseController::class );
    Route::resource( 'conversations', \App\Http\Controllers\ConversationController::class );
    Route::resource( 'conversations.messages', \App\Http\Controllers\MessageController::class );
    Route::resource( 'conversations.notes', \App\Http\Controllers\PrivateNoteController::class );
    Route::resource( 'contacts', \App\Http\Controllers\ContactController::class );

    Route::post( '/messages', [ \App\Http\Controllers\MessageController::class, 'compose' ])->name( 'messages.compose' );
    Route::get( '/twilio/unauthenticated', [ \App\Http\Controllers\Twilio\TeamUnauthenticatedController::class, 'show' ] )->name( 'twilio.unauthenticated' );
    Route::put( '/current-phone-number', [ \App\Http\Controllers\CurrentPhoneNumberController::class, 'update' ] )->name( 'current-phone-number.update' );
});
