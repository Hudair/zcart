<?php

namespace App;

use App\Common\Imageable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Packaging extends BaseModel
{
    use SoftDeletes, Imageable;

    const FREE_PACKAGING_ID = 1;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'packagings';

    /**
     * The attributes that should be casted to boolean types.
     *
     * @var array
     */
    protected $casts = [
        'default' => 'boolean',
        'active' => 'boolean',
    ];

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
                    'cost',
                    'height',
                    'width',
                    'depth',
                    'default',
                    'active',
                 ];

    /**
     * Get the Shop associated with the packaging.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the inventories for the packaging.
     */
    public function inventories()
    {
        return $this->belongsToMany(Inventory::class)->withTimestamps();
    }

    /**
     * Set the default for the packaging.
     */
    public function setDefaultAttribute($value)
    {
        $this->attributes['default'] = (bool) $value;
    }

}