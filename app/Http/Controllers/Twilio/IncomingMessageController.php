<?php

namespace App\Http\Controllers\Twilio;

use App\Http\Controllers\Controller;
use App\Http\Requests\Twilio\IncomingMessage;
use App\Models\Contact;
use App\Models\FailedAttachmentDownload;
use App\Models\MessageLog;
use App\Models\PhoneNumber;
use App\Repositories\MessageRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class IncomingMessageController extends Controller
{
    public function handle( IncomingMessage $request, MessageRepository $messageRepository ) {
        $data = $request->validated();

        MessageLog::create([
            'payload' => $data,
        ]);

        $contact = $messageRepository->getContactByPhoneNumber( $data[ 'From' ] );
        $phone = PhoneNumber::query()->where( 'phone_number', $data[ 'To' ] )->firstOrFail();

        if ( ! $contact ) {
            $contact = Contact::create([
                'team_id' => $phone->team->id,
                'phone_number' => $data[ 'From' ],
                'friendly_name' => $data[ 'From' ],
            ]);
        }

        $conversation = $phone->conversations()->where( 'contact_id', $contact->id )->first();

        if ( ! $conversation ) {
            $conversation = $messageRepository->createConversation( $phone->team, [
                'contact_id' => $contact->id,
                'phone_number_id' => $phone->id,
                'phone_number' => $phone->phone_number,
            ] );
        }

        $message = $conversation->messages()->create([
            'user_id' => null,
            'message' => $data[ 'Body' ],
            'from' => $data[ 'From' ],
        ]);

        // Send broadcast
        event( new \App\Events\IncomingMessage( $conversation, $message ) );

        // Short
        $conversation->update([ 'read_at' => null ]);

        if ( isset($data['MediaUrl0'] ) && $data[ 'MediaUrl0' ] ) {
            try {
                $client   = new \GuzzleHttp\Client();
                $response = $client->get( $data[ 'MediaUrl0' ] );

                if ( $response->getStatusCode() === 200 ) {
                    $response_string = $response->getBody()->__toString();

                    if ( $response_string ) {
                        $base64 = base64_encode( $response_string );
                        $message->addMediaFromBase64( $base64 )->toMediaCollection( 'images' );
                    } else {
                        FailedAttachmentDownload::create([
                            'conversation_id' => $conversation->id,
                            'message_id' => $message->id,
                            'type' => 'EMPTY_RESPONSE',
                            'url' => $data[ 'MediaUrl0' ],
                        ]);
                    }
                }
            } catch ( Exception $e ) {
                FailedAttachmentDownload::create([
                    'conversation_id' => $conversation->id,
                    'message_id' => $message->id,
                    'url' => $data[ 'MediaUrl0' ],
                    'exception' => $e->getMessage(),
                ]);
            }
        }

        return response();
    }
}
