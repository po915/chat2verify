<?php

namespace App\Http\Controllers;

use App\Http\Requests\Phrases\DeletePhrase;
use App\Http\Requests\Phrases\SearchPhrase;
use App\Http\Requests\Phrases\StorePhrase;
use App\Http\Requests\Phrases\UpdatePhrase;
use App\Models\Phrase;
use App\Models\User;
use App\Repositories\PhraseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Inertia\Inertia;

/**
 * Class PhraseController
 * @package App\Http\Controllers
 */
class PhraseController extends Controller
{
    /**
     *
     */
    const PHRASE_PAGINATION_PER_PAGE = 20;


    /**
     * @param PhraseRepository $phraseRepository
     * @param Request $request
     * @return \Inertia\Response
     */
    public function index(PhraseRepository  $phraseRepository, Request $request)
    {
        if ( ! $request->has( 'filter' ) ) {
            return Inertia::render( 'Phrases/Index', [
                'can' => [
                    'edit_public_phrases' => $request->user()->can( 'edit_public_phrases' ),
                    'delete_public_phrases' => $request->user()->can( 'delete_public_phrases' ),
                ],
                'phrases' => $phraseRepository->getTeamPhrases( true, null, self::PHRASE_PAGINATION_PER_PAGE ),
            ]);
        }

        switch( strtolower( $request->input( 'filter' ) ) ) {
            case 'public':
                $phrases = $phraseRepository->getPublicPhrases( null, self::PHRASE_PAGINATION_PER_PAGE );
                break;

            case 'team':
                $phrases = $phraseRepository->getTeamPhrases( false, null, self::PHRASE_PAGINATION_PER_PAGE );
                break;

            case 'all':
            default:
                $phrases = $phraseRepository->getTeamPhrases( true, null, self::PHRASE_PAGINATION_PER_PAGE );
                break;
        }

        return Inertia::render( 'Phrases/Index', [
            'can' => [
                'edit_public_phrases' => $request->user()->can( 'edit_public_phrases' ),
                'delete_public_phrases' => $request->user()->can( 'delete_public_phrases' ),
            ],
            'phrases' => $phrases,
        ]);
    }

    /**
     * @param PhraseRepository $phraseRepository
     * @param SearchPhrase $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(PhraseRepository $phraseRepository, SearchPhrase $request ) {
        $data = $request->validated();

        if ( ! isset( $data['search_term'] ) || ! $data['search_term'] ) {
            $phrases = $phraseRepository->getTeamPhrases( true, null, null );
        } else {
            $phrases = $phraseRepository->getTeamPhrases( true, $data['search_term'], 4 );
        }

        return Response::json([ 'success' => true, 'phrases' => $phrases ]);
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
    public function store(StorePhrase $request)
    {
        $data = $request->validated();

        Phrase::create([
            'user_id' => $request->user()->id,
            'team_id' => $request->user()->currentTeam->id,
            'friendly_name' => $data['friendly_name'],
            'shortcode' => $data['shortcode'],
            'message' => $data[ 'message' ],
            'publicized_at' => isset( $data['public'] ) && $data['public'] ? Carbon::now() : null,
        ]);

        return Redirect::route( 'responses.index' )->banner(
            __('Your response has been added successfully.'),
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Phrase  $phrase
     * @return \Illuminate\Http\Response
     */
    public function show(Phrase $phrase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Phrase  $phrase
     * @return \Illuminate\Http\Response
     */
    public function edit(Phrase $phrase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Phrase  $phrase
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePhrase $request, Phrase $response)
    {
        $data = $request->validated();

        $response->update([
            'friendly_name' => $data['friendly_name'],
            'message' => $data['message'],
            'shortcode' => $data['shortcode'],
            'publicized_at' => isset( $data['public'] ) && $data['public'] ? Carbon::now() : null,
        ]);

        return Redirect::back()->banner( 'The phrase has been successfully updated.' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Phrase  $phrase
     * @return \Illuminate\Http\Response
     */
    public function destroy( DeletePhrase $request, Phrase $response)
    {
        $request->validated();

        $response->delete();

        return Redirect::back()->banner( 'The phrase has been successfully deleted.' );
    }
}
