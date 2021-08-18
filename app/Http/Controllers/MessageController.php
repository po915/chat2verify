<?php

namespace App\Http\Controllers;

use App\Http\Requests\Conversations\SendMessage;
use App\Http\Requests\StartConversation;
use App\Models\Contact;
use App\Models\Conversation;
use App\Models\Message;
use App\Repositories\MessageRepository;
use App\Repositories\TwilioRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(TwilioRepository $twilioRepository, MessageRepository $messageRepository, Conversation $conversation, SendMessage $request)
    {
        $data = $request->validated();

        $phone_number = $request->user()->currentPhoneNumber;

        $message = $conversation->messages()->create([
            'user_id' => $request->user()->id,
            'message' => $data['message'],
            'from' => $phone_number->phone_number,
        ]);

        $composer = $messageRepository->compose($conversation->contact->phone_number, $phone_number->phone_number, $data['message']);

        if (count($data['attachment']) > 0) {
            $message->addMediaFromRequest('attachment')->toMediaCollection('images');

            foreach( $message->getMedia('images') as $attachment ) {
                $composer->addAttachment( $attachment->getFullUrl() );
            }
        }

        // Send text message
        $twilioRepository->sendSms( $composer );

        return Redirect::route('conversations.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function compose(TwilioRepository $twilioRepository, MessageRepository $messageRepository, StartConversation $request)
    {
        $data = $request->validated();

        $phone_number = $request->user()->currentPhoneNumber;
        $team = $request->user()->currentTeam;

        if (!$phone_number) {
            return Redirect::route('conversations.index')->dangerBanner(
                __('We were unable to send your message. Please purchase a phone number for your team to get started.'),
            );
        }

        $contact = Contact::query()->firstOrCreate([
            'phone_number' => $data['phone_number'],
            'team_id' => $team->id,
        ], [
            'friendly_name' => $data['phone_number'],
        ]);

        $conversation = $messageRepository->createConversation(null, [
            'contact_id' => $contact->id,
            'phone_number_id' => $phone_number->id,
            'phone_number' => $phone_number->phone_number,
        ]);

        $conversation->messages()->create([
            'user_id' => $request->user()->id,
            'message' => $data['message'],
            'from' => $phone_number->phone_number,
        ]);

        // Send text message
        $twilioRepository->sendSms(
            $messageRepository->compose($data['phone_number'], $phone_number->phone_number, $data['message'])
        );

        return Redirect::route('conversations.index')->banner(
            __('Your message has been sent successfully.'),
        );
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Message $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Message $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Message $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Message $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
