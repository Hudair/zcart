<?php

namespace App;

use App\Common\Imageable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manufacturer extends BaseModel
{
    use SoftDeletes, Imageable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'manufacturers';

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
                            'slug',
                            'email',
                            'url',
                            'phone',
                            'description',
                            'country_id',
                            'active'
                        ];

    /**
     * Get the country for the manufacturer.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the products for the manufacturer.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all of the inventories for the country.
     */
    public function inventories()
    {
        return $this->hasManyThrough(Inventory::class, Product::class);
    }

    /**
     * Setters
     */
    // public function setSlugAttribute($value)
    // {
    //     $this->attributes['slug'] = Str::slug($value);
    // }

}
