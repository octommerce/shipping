<?php namespace Octommerce\Shipping\Models;

use Model;

/**
 * Address Model
 */
class Address extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'octommerce_shipping_addresses';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'user_id',
        'location_code',
        'address_name',
        'name',
        'phone',
        'street',
        'latitude',
        'longitude'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'location' => [
            'Octommerce\Shipping\Models\Location',
            'key'      => 'location_code',
            'otherKey' => 'code'
        ],
        'user' => [
            'RainLab\User\Models\User'
        ]
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    /**
     * Set the address to primary
     */
    public function setPrimary()
    {
        $this->user->addresses()->update(['is_primary' => false]);

        $this->is_primary = true;

        $this->save();
    }
}
