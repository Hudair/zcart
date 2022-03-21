<?php

namespace App;

use Auth;

class Refund extends BaseModel
{
    const STATUS_NEW       = 1;         //Default
    const STATUS_APPROVED  = 2;
    const STATUS_DECLINED  = 3;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'refunds';

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    // protected $touches = ['order'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
                    'shop_id',
                    'order_id',
                    'order_fulfilled',
                    'return_goods',
                    'amount',
                    'description',
                    'status',
                ];

    /**
     * Get the shop for the refund.
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get the order for the refund.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the customer for the refund.
     */
    public function customer()
    {
        return $this->order->customer();
    }

    /**
     * Set the order_fulfilled.
     */
    public function setOrderFulfilledAttribute($value)
    {
        $this->attributes['order_fulfilled'] = (bool) $value;
    }

    /**
     * Set the return_goods.
     */
    public function setReturnGoodsAttribute($value)
    {
        $this->attributes['return_goods'] = (bool) $value;
    }

    /**
     * Scope a query to only include records from the users shop.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
        return $query->where('status' , '<', static::STATUS_APPROVED);
    }

    /**
     * Scope a query to only include records from the users shop.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClosed($query)
    {
        return $query->where('status', '>=', static::STATUS_APPROVED);
    }

    /**
     * Scope a query to only include records that have the given status.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatusOf($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if the refund is open
     *
     * @return boolean
     */
    public function isOpen()
    {
        return $this->status < static::STATUS_APPROVED;
    }

    /**
     * Check if the refund has been approved
     *
     * @return boolean
     */
    public function isApproved()
    {
        return $this->status == static::STATUS_APPROVED;
    }

    /**
     * Check if the refund has been declined
     *
     * @return boolean
     */
    public function isDeclined()
    {
        return $this->status == static::STATUS_DECLINED;
    }

    public function statusName()
    {
        switch ($this->status) {
            case static::STATUS_NEW:
                return '<span class="label label-outline">' . trans('app.statuses.new') . '</span>';

            case static::STATUS_APPROVED:
                return '<span class="label label-primary">' . trans('app.statuses.approved') . '</span>';

            case static::STATUS_DECLINED:
                return '<span class="label label-danger">' . trans('app.statuses.declined') . '</span>';
        }
    }
}