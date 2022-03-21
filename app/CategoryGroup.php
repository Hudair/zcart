<?php

namespace App;

use App\Common\Imageable;
use App\Common\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryGroup extends BaseModel
{
    use SoftDeletes, CascadeSoftDeletes, Imageable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'category_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'slug', 'icon', 'order', 'active', 'meta_title', 'meta_description'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Cascade Soft Deletes Relationships
     *
     * @var array
     */
    protected $cascadeDeletes = ['subGroups'];

    /**
     * Get the subGroups associated with the CategoryGroup.
    */
    public function subGroups()
    {
        return $this->hasMany(CategorySubGroup::class, 'category_group_id')->orderBy('order', 'asc');
    }

    /**
     * Get the categories associated with the CategoryGroup.
    */
    public function categories()
    {
        return $this->hasManyThrough(Category::class, CategorySubGroup::class,
            'category_group_id', // Foreign key on CategorySubGroup table...
            'category_sub_group_id', // Foreign key on Category table...
            'id', // Local key on CategoryGroup table...
            'id' // Local key on CategorySubGroup table...
        );
    }

    /**
     * Setters
     */
    public function setOrderAttribute($value)
    {
        $this->attributes['order'] = $value ?: 100;
    }


}
