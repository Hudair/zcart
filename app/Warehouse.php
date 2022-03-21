<?php

namespace App;

use App\Common\Imageable;
use App\Common\Addressable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends BaseModel
{
    use SoftDeletes, Addressable, Imageable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'warehouses';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
                    'shop_id',
                    'name',
                    'email',
                    'incharge',
                    'description',
                    'active',
                ];

    /**
     * Get the country for the warehouse.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the manager of the warehouse.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'incharge')->withDefault();
    }

    /**
     * Get the Users associated with the warehouse.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * Get staff list for the user.
     *
     * @return array
     */
    public function getUserListAttribute()
    {
        return $this->users->pluck('id')->toArray();
    }

    /**
     * Get the Shop associated with the blog post.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the Inventories for the warehouse.
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Get all of the products for the warehouse.
     */
    public function products()
    {
        return $this->hasManyThrough(Product::class, Inventory::class);
    }

}
