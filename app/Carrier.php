<?php

namespace App;

use App\Common\Imageable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carrier extends BaseModel
{
    use SoftDeletes, Imageable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'carriers';

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
                    'phone',
                    'tracking_url',
                    'active'
                 ];

    /**
     * Get all of the shippingRates for the country.
     */
    public function shippingRates()
    {
        return $this->hasMany(ShippingRate::class);
    }

    /**
     * Get all of the shippingZones for the country.
     */
    public function shippingZones()
    {
        return $this->belongsToMany(ShippingZone::class, 'shipping_rates');
    }

    /**
     * Get the Shop associated with the carrier.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the carts for the carrier.
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Get the orders for the carrier.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}