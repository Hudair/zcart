<?php

namespace App;

class ShippingRate extends BaseModel
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shipping_rates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
                    'name',
                    'shipping_zone_id',
                    'delivery_takes',
                    'carrier_id',
                    'based_on',
                    'maximum',
                    'minimum',
                    'rate',
                ];

    public function shippingZone()
    {
        return $this->belongsTo(ShippingZone::class);
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class)->withDefault([
            'name' => ' ',
        ]);
    }

    /**
     * Getters
     */
    public function getCarrierNameAttribute()
    {
        return $this->carrier->name;
    }
}
