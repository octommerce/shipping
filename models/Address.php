<?php namespace Octommerce\Shipping\Models;

use Model;

/**
 * Address Model
 */
class Address extends Model
{
    use \October\Rain\Database\Traits\Validation;

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
        'postcode',
        'latitude',
        'longitude'
    ];

    /**
     * Validation rules
     */
    public $rules = [
        'address_name'  => ['required', 'min:3', 'regex:/^[a-z A-Z]+$/'],
        'name'          => ['min:3', 'regex:/^[a-z A-Z]+$/'],
        'street'        => 'required|min:20|string',
        'phone'         => ['regex:/^(?:\+?62[^0]|0[^0])[0-9]{9,10}$/'],
        'location_code' => 'required',
    ];

    /**
     * @var array The array of custom error messages.
     */
    public $customMessages = [
        'location_code.required' => 'The subdistrict field is required.'
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

    public function beforeCreate()
    {
        if ( ! \Auth::getUser()->addresses()->primary()->count()) {
            $this->is_primary = true;
        }
    }

    public function afterDelete()
    {
        //TODO: If it's primary, set the replacement
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = !empty($value) ? $value : \Auth::getUser()->name;
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = !empty($value) ? $value : \Auth::getUser()->phone;
    }

    /**
     * Set the address to primary
     */
    public function setPrimary()
    {
        $this->user->addresses()->update(['is_primary' => false]);

        $this->is_primary = true;

        $this->save();
    }

    /**
     * Add scope to filter primary address
     *
     * @param Builder $query
     */
    public function scopePrimary($query)
    {
        $query->whereIsPrimary(true);
    }
}
