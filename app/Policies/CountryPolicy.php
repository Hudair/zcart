<?php

namespace App\Policies;

use App\User;
use App\Country;
use App\Helpers\Authorize;
use Illuminate\Auth\Access\HandlesAuthorization;

class CountryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view countrys.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->isAdmin();
        // return (new Authorize($user, 'view_utility'))->check();
    }

    /**
     * Determine whether the user can view the Country.
     *
     * @param  \App\User  $user
     * @param  \App\Country  $country
     * @return mixed
     */
    public function view(User $user, Country $country)
    {
        return $user->isAdmin();
        // return (new Authorize($user, 'view_utility', $country))->check();
    }

    /**
     * Determine whether the user can create Countrys.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin();
        // return (new Authorize($user, 'add_utility'))->check();
    }

    /**
     * Determine whether the user can update the Country.
     *
     * @param  \App\User  $user
     * @param  \App\Country  $country
     * @return mixed
     */
    public function update(User $user, Country $country)
    {
        return $user->isAdmin();
        // return (new Authorize($user, 'edit_utility', $country))->check();
    }

    /**
     * Determine whether the user can delete the Country.
     *
     * @param  \App\User  $user
     * @param  \App\Country  $country
     * @return mixed
     */
    public function delete(User $user, Country $country)
    {
        return $user->isAdmin();
        // return (new Authorize($user, 'delete_utility', $country))->check();
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
        // return (new Authorize($user, 'delete_utility'))->check();
    }
}
