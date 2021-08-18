<?php

namespace App\Http\Controllers;

use App\Http\Requests\Conversations\AddPrivateNote;
use App\Models\Conversation;
use App\Models\PrivateNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class PrivateNoteController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Conversation $conversation, AddPrivateNote $request)
    {
        $data = $request->validated();

        $conversation->notes()->create([
            'user_id' => $request->user()->id,
            'note' => $data['note'],
        ]);

        return Redirect::back()->banner( 'The note has been added to the conversation' );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PrivateNote  $privateNote
     * @return \Illuminate\Http\Response
     */
    public function show(PrivateNote $privateNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PrivateNote  $privateNote
     * @return \Illuminate\Http\Response
     */
    public function edit(PrivateNote $privateNote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PrivateNote  $privateNote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PrivateNote $privateNote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PrivateNote  $privateNote
     * @return \Illuminate\Http\Response
     */
    public function destroy(PrivateNote $privateNote)
    {
        //
    }
}
