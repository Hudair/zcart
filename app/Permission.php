<?php

namespace App;

class Permission extends BaseModel
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * Get the module associated with the permission.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get the roles for the permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

}
