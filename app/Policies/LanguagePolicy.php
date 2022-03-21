<?php

namespace App\Policies;

use App\User;
use App\Language;
use App\Helpers\Authorize;
use Illuminate\Auth\Access\HandlesAuthorization;

class LanguagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view Languages.
     *
     * @param  \App\User  $user
     * @param  \App\Language  $language
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->isAdmin();
        // return (new Authorize($user, 'view_language'))->check();
    }

    /**
     * Determine whether the user can view the Language.
     *
     * @param  \App\User  $user
     * @param  \App\Language  $language
     * @return mixed
     */
    public function view(User $user, Language $language)
    {
        return $user->isAdmin();
        // return (new Authorize($user, 'view_language', $language))->check();
    }

    /**
     * Determine whether the user can create Languages.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
        // return (new Authorize($user, 'add_language'))->check();
    }

    /**
     * Determine whether the user can update the Language.
     *
     * @param  \App\User  $user
     * @param  \App\Language  $language
     * @return mixed
     */
    public function update(User $user, Language $language)
    {
        return $user->isAdmin();
        // return (new Authorize($user, 'edit_language', $language))->check();
    }

    /**
     * Determine whether the user can delete the Language.
     *
     * @param  \App\User  $user
     * @param  \App\Language  $language
     * @return mixed
     */
    public function delete(User $user, Language $language)
    {
        return $user->isAdmin();
        // return (new Authorize($user, 'delete_language', $language))->check();
    }

    /**
     * Determine whether the user can delete the Product.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function massDelete(User $user)
    {
        return $user->isAdmin();
    }

}