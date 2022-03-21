<?php

namespace App\Policies;

use App\User;
use App\ChatConversation;
use App\Helpers\Authorize;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatConversationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view ChatConversations.
     *
     * @param  \App\User  $user
     * @param  \App\ChatConversation  $conversation
     * @return mixed
     */
    public function index(User $user)
    {
        return (new Authorize($user, 'view_chat_conversation'))->check();
    }

    /**
     * Determine whether the user can view the ChatConversation.
     *
     * @param  \App\User  $user
     * @param  \App\ChatConversation  $conversation
     * @return mixed
     */
    public function view(User $user, ChatConversation $conversation)
    {
        return (new Authorize($user, 'view_chat_conversation', $conversation))->check();
    }

    /**
     * Determine whether the user can create ChatConversations.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function reply(User $user, ChatConversation $conversation)
    {
        return (new Authorize($user, 'reply_chat_conversation', $conversation))->check();
    }

    /**
     * Determine whether the user can delete the ChatConversation.
     *
     * @param  \App\User  $user
     * @param  \App\ChatConversation  $conversation
     * @return mixed
     */
    public function delete(User $user, ChatConversation $conversation)
    {
        return (new Authorize($user, 'delete_chat_conversation', $conversation))->check();
    }

    /**
     * Determine whether the user can delete the Product.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function massDelete(User $user)
    {
        return (new Authorize($user, 'delete_chat_conversation'))->check();
    }

}