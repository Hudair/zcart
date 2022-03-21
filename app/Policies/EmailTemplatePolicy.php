<?php

namespace App\Policies;

use App\User;
use App\EmailTemplate;
use App\Helpers\Authorize;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmailTemplatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view email_templates.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return (new Authorize($user, 'view_utility'))->check();
    }

    /**
     * Determine whether the user can view the EmailTemplate.
     *
     * @param  \App\User  $user
     * @param  \App\EmailTemplate  $emailTemplate
     * @return mixed
     */
    public function view(User $user, EmailTemplate $emailTemplate)
    {
        return (new Authorize($user, 'view_utility', $emailTemplate))->check();
    }

    /**
     * Determine whether the user can create EmailTemplates.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return (new Authorize($user, 'add_utility'))->check();
    }

    /**
     * Determine whether the user can update the EmailTemplate.
     *
     * @param  \App\User  $user
     * @param  \App\EmailTemplate  $emailTemplate
     * @return mixed
     */
    public function update(User $user, EmailTemplate $emailTemplate)
    {
        return (new Authorize($user, 'edit_utility', $emailTemplate))->check();
    }

    /**
     * Determine whether the user can delete the EmailTemplate.
     *
     * @param  \App\User  $user
     * @param  \App\EmailTemplate  $emailTemplate
     * @return mixed
     */
    public function delete(User $user, EmailTemplate $emailTemplate)
    {
        return (new Authorize($user, 'delete_utility', $emailTemplate))->check();
    }

    /**
     * Determine whether the user can delete the Product.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function massDelete(User $user)
    {
        return (new Authorize($user, 'delete_utility'))->check();
    }

}
