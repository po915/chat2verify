<?php

namespace App\Http\Controllers;

use App\Http\Requests\Conversations\ArchiveConversation;
use App\Http\Requests\Conversations\ShowConversation;
use App\Models\Conversation;
use App\Repositories\Interfaces\MessageRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

/**
 * Class ConversationController
 * @package App\Http\Controllers
 */
class ConversationController extends Controller
{

    /**
     * @var MessageRepositoryInterface
     */
    protected $messageRepository;

    /**
     * ConversationController constructor.
     * @param MessageRepositoryInterface $messageRepository
     */
    public function __construct(MessageRepositoryInterface $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $conversation = $this->messageRepository->getLatestConversation();

        if ($conversation) {
            return Redirect::route('conversations.show', $conversation->id);
        }

        return Inertia::render('Conversations/ViewAllMessages', [
            'conversations' => $this->messageRepository->getConversations(),
            'archived_conversations' => $this->messageRepository->getArchivedConversations()
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowConversation $request, Conversation $conversation)
    {
        $request->validated();

        $conversation->timestamps = false;
        $conversation->read_at = Carbon::now();
        $conversation->save();

        return Inertia::render('Conversations/Show', [
            'conversations' => $this->messageRepository->getConversations(),
            'archived_conversations' => $this->messageRepository->getArchivedConversations(),
            'active_conversation' => $conversation,
            'active_conversation_messages' => $conversation->messages()->orderByDesc('id')->paginate(20, ['*'], 'messages'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy( Conversation $conversation, ArchiveConversation $request )
    {
        $request->validated();

        $conversation->delete();

        return Redirect::route( 'conversations.index' )->banner( 'The conversation was successfully archived.' );
    }
}
