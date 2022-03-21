<?php

namespace App;

use Carbon\Carbon;
use App\Common\Taggable;
use App\Common\Imageable;
use App\Common\Feedbackable;
use Laravel\Scout\Searchable;
use EloquentFilter\Filterable;
use App\Common\CascadeSoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Incevio\Package\Inspector\Traits\HasInspector;

class Inventory extends BaseModel
{
    use SoftDeletes, CascadeSoftDeletes, Taggable, Imageable, Searchable, Filterable, Feedbackable;
    // use SoftDeletes, CascadeSoftDeletes, Taggable, Imageable, Searchable, Filterable, Feedbackable, HasInspector;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'inventories';

    /**
     * Cascade Soft Deletes Relationships
     *
     * @var array
     */
    protected $cascadeDeletes = ['carts'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'offer_start', 'offer_end', 'available_from', 'expiry_date'];

    /**
     * The attributes that should be casted to boolean types.
     *
     * @var array
     */
    protected $casts = [
        'free_shipping' => 'boolean',
        'stuff_pick' => 'boolean',
        'active' => 'boolean',
    ];

    protected static $inspectable = [
        'title',
        'condition_note',
        'description',
        'key_features'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shop_id',
        'title',
        'warehouse_id',
        'product_id',
        'brand',
        'supplier_id',
        'sku',
        'condition',
        'condition_note',
        'description',
        'key_features',
        'stock_quantity',
        'damaged_quantity',
        'user_id',
        'purchase_price',
        'sale_price',
        'offer_price',
        'offer_start',
        'offer_end',
        'shipping_weight',
        'free_shipping',
        'stuff_pick',
        'available_from',
        'expiry_date',
        'min_order_quantity',
        'linked_items',
        'slug',
        'meta_title',
        'meta_description',
        'active'
    ];

    /**
     * Get the value used to index the model.
     *
     * @return mixed
     */
    // public function getScoutKey()
    // {
    //     return $this->slug;
    // }

    // public $asYouType = true;

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        $array['title'] = $this->title;
        $array['key_features'] = $this->key_features ? implode(', ', unserialize($this->key_features)) : Null;
        $array['active'] = $this->active;
        // $array['shop'] = $this->shop;
        // $array['product'] = $this->product;

        return $array;
    }

    /**
     * Get the shop of the inventory.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get all categories for the category.
     */
    // public function categories()
    // {
    //     return $this->belongsToMany(Category::class, 'category_product','product_id', null);
    // }

    /**
     * Get the Warehouse associated with the inventory.
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the product of the inventory.
     */
    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault();
    }

    /**
     * Get the supplier for the inventory.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class)->withDefault();
    }

    // public function manufacturer()
    // {
    //     return $this->product->belongsTo(Manufacturer::class);

    //     return $this->belongsTo(Manufacturer::class, null, null, 'manufacturer_id')
    //         ->join('products', 'manufacturers.id', '=', 'products.manufacturer_id');

    //     $instance = new Manufacturer();
    //     $instance->setTable('manufacturers');
    //     $query = $instance->newQuery();

    //     return (new BelongsTo($query, $this, 'product_id', $instance->getKeyName(), 'manufacturer'))
    //         ->join('products', 'manufacturers.id', '=', 'products.manufacturer_id')
    //         ->select(\DB::raw('manufacturers.name'));

    //     // $instance = new Manufacturer();
    //     // $instance->setTable('products');
    //     // $query = $instance->newQuery();

    //     // return (new BelongsTo($query, $this, 'product_id', $instance->getKeyName(), 'manufacturer'))
    //     //     ->join('manufacturers', 'manufacturers.id', '=', 'products.manufacturer_id')
    //     //     ->select(\DB::raw('manufacturers.name'));
    // }

    /**
     * Get the packagings for the order.
     */
    public function packagings()
    {
        return $this->belongsToMany(Packaging::class)->withTimestamps();
    }

    /**
     * Get the Attributes for the inventory.
     */
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_inventory')
            ->withPivot('attribute_value_id')->withTimestamps();
    }

    /**
     * Get the attribute values that owns the SubGroup.
     */
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'attribute_inventory')
            ->withPivot('attribute_id')->withTimestamps();
    }

    /**
     * Get the carts for the product.
     */
    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_items')
            ->withPivot('item_description', 'quantity', 'unit_price')->withTimestamps();
    }

    /**
     * Get the orders for the product.
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
            ->withPivot('item_description', 'quantity', 'unit_price', 'feedback_id')->withTimestamps();
    }

    /**
     * Get the manufacturer associated with the product.
     */
    public function getManufacturerAttribute()
    {
        return $this->product->manufacturer;
    }

    public function isLowQtt()
    {
        $alert_quantity = config('shop_settings.alert_quantity') ?: 0;

        return $this->stock_quantity <= $alert_quantity;
    }

    /**
     * Check if the item hase a valid offer price.
     */
    public function hasOffer()
    {
        if (
            ($this->offer_price > 0) &&
            ($this->offer_price < $this->sale_price) &&
            ($this->offer_start < Carbon::now()) &&
            ($this->offer_end > Carbon::now())
        ) {
            return TRUE;
        }

        return FALSE;
    }

    /**
     * Return currnt sale price
     *
     * @return number
     */
    public function current_sale_price()
    {
        return $this->hasOffer() ? $this->offer_price : $this->sale_price;
    }

    /**
     * Return the discount percentage
     *
     * @return number
     */
    public function discount_percentage()
    {
        return $this->hasOffer() ? get_percentage_of($this->sale_price, $this->offer_price) : 0;
    }

    /**
     * Setters
     */
    public function setMinOrderQuantityAttribute($value)
    {
        $this->attributes['min_order_quantity'] = $value > 1 ? $value : 1;
    }

    public function setOfferPriceAttribute($value)
    {
        $this->attributes['offer_price'] = $value > 0 ? $value : Null;
    }

    public function setWarehouseIdAttribute($value)
    {
        $this->attributes['warehouse_id'] = $value > 0 ? $value : Null;
    }

    public function setSupplierIdAttribute($value)
    {
        $this->attributes['supplier_id'] = $value > 0 ? $value : Null;
    }

    public function setAvailableFromAttribute($value)
    {
        if ($value) {
            $this->attributes['available_from'] = Carbon::createFromFormat('Y-m-d h:i a', $value);
        }
    }

    public function setOfferStartAttribute($value)
    {
        if ($value) {
            $this->attributes['offer_start'] = Carbon::createFromFormat('Y-m-d h:i a', $value);
        }
    }

    public function setOfferEndAttribute($value)
    {
        if ($value) {
            $this->attributes['offer_end'] = Carbon::createFromFormat('Y-m-d h:i a', $value);
        }
    }
    public function setExpiryDateAttribute($value)
    {
        if ($value) {
            $this->attributes['expiry_date'] = date('Y-m-d', strtotime($value));
        }
    }

    public function setFreeShippingAttribute($value)
    {
        $this->attributes['free_shipping'] = (bool) $value;
    }

    public function setKeyFeaturesAttribute($value)
    {
        if (is_array($value)) {
            $value = array_filter($value, function ($item) {
                return !empty($item[0]);
            });
        }

        $this->attributes['key_features'] = (bool) $value ? serialize($value) : Null;
    }

    public function setLinkedItemsAttribute($value)
    {
        $this->attributes['linked_items'] = (bool) $value ? serialize($value) : Null;
    }

    // /**
    //  * Set the alert_quantity for the inventory.
    //  */
    // public function setAlertQuantityAttribute($value)
    // {
    //     if ($value > 1) $this->attributes['alert_quantity'] = $value;
    //     else $this->attributes['alert_quantity'] = null;
    // }

    /**
     * Getters
     */
    public function getPackagingListAttribute()
    {
        if (count($this->packagings)) {
            return $this->packagings->pluck('id')->toArray();
        }
    }
    public function getExpiryDateAttribute($value)
    {
        if ($value) {
            return date('Y-m-d', strtotime($value));
        }
    }

    // This Mutators causes the searse error
    // public function getKeyFeaturesAttribute($value)
    // {
    //     return unserialize($value);
    // }
    // public function getLinkedItemsAttribute($value)
    // {
    //     return unserialize($value);
    // }

    /**
     * Scope a query to only include available for sale .
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        // return $query;

        // return $query->where([
        //     ['active', '=', 1],
        //     ['stock_quantity', '>', 0],
        //     ['available_from', '<=', Carbon::now()]
        // ]);

        $query = $query->whereHas('shop', function ($q) {
            $q->active();
        })->where([
            ['active', '=', 1],
            ['stock_quantity', '>', 0],
            ['available_from', '<=', Carbon::now()]
        ]);

        if (is_incevio_package_loaded('pharmacy')) {
            $query = $query->where('expiry_date', '>', Carbon::now());
        }

        return $query;
    }

    /**
     * Scope a query to only include available for sale .
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasOffer($query)
    {
        return $query->where([
            ['offer_price', '>', 0],
            ['offer_start', '<', Carbon::now()],
            ['offer_end', '>', Carbon::now()]
        ])
            ->whereColumn('offer_price', '<', 'sale_price');
    }

    /**
     * Scope a query to only include items with free Shipping.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFreeShipping($query)
    {
        return $query->where('free_shipping', 1);
    }

    /**
     * Scope a query to only include new Arraival Items.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNewArraivals($query)
    {
        return $query->where(
            'inventories.created_at',
            '>',
            Carbon::now()->subDays(config('system.filter.new_arraival', 7))
        );
    }

    /**
     * Scope a query to only include low qtt items.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLowQtt($query)
    {
        $alert_quantity = config('shop_settings.alert_quantity') ?: 0;

        return $query->where('stock_quantity', '<=', $alert_quantity);
    }

    /**
     * Scope a query to only include out of stock items.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStockOut($query)
    {
        return $query->where('stock_quantity', '<=', 0);
    }

    /**
     * Check if the item is an hot item
     *
     * @return boolean
     */
    public function isHotItem()
    {
        return $this->orders_count >= config('system.popular.hot_item.sell_count', 3);
    }

    /**
     * Returns translated name of condition
     *
     * @return str condition
     */
    public function getConditionAttribute($condition)
    {
        // Skip the mutator when ediding the model
        if (Route::currentRouteName() == 'admin.stock.inventory.edit') {
            return $condition;
        }

        switch ($condition) {
            case 'New':
                return trans('app.new');
            case 'Used':
                return trans('app.used');
            case 'Refurbished':
                return trans('app.refurbished');
        }
    }

    /**
     * Returns translated label text
     *
     * @return array labels
     */
    public function getLabels()
    {
        $labels = [];

        if ($this->isHotItem()) {
            $labels[] = trans('theme.hot_item');
        }

        if ($this->free_shipping) {
            $labels[] = trans('theme.free_shipping');
        }

        if ($this->stuff_pick) {
            $labels[] = trans('theme.stuff_pick');
        }

        if ($this->hasOffer()) {
            $labels[] = trans('theme.percent_off', ['value' => $this->discount_percentage()]);
        }

        return $labels;
    }

    /** if zipcode package active this function will return zipcode query
     * @param $query
     * @return mixed
     */
    public function scopeZipcode($query): object
    {
        if (is_incevio_package_loaded('zipcode')) {
            return $query->whereHas('shop.address', function ($builder) {
                return $builder->where('zip_code', session('zipcode_default'));
            });
        }

        return $query;
    }
}
