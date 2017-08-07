<?php namespace Octommerce\Shipping\Models;

use Model;
use Form;

/**
 * Location Model
 */
class Location extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'octommerce_shipping_locations';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    /**
     * @var array Cache for nameList() method
     */
    protected static $nameList = [];

    public static function getNameList($locationCode = 'base')
    {
        if (isset(self::$nameList[$locationCode])) {
            return self::$nameList[$locationCode];
        }

        $arg = self::getArgument($locationCode);

        return self::$nameList[$locationCode] = self::where('code', 'LIKE', $arg)
            ->orderBy('name')
            ->lists('name', 'code');
    }

    public function getParentAttribute()
    {
        $codes = explode('.', $this->code);

        if (count($codes) <= 1) return null;

        array_pop($codes);

        return self::whereCode(implode('.', $codes))->first();
    }

    public function children()
    {
        return self::where('code', 'like', $this->code . '%');
    }

    public function getChildrenAttribute()
    {
        return $this->children()->get();
    }

    public static function getArgument($locationCode)
    {
        $arg = '';

        switch (strlen($locationCode)) {
            case 2:
            case 5:
                $arg = $locationCode . '.__';
                break;
            case 8:
                $arg = $locationCode . '.____';
                break;
            default:
                $arg = '__';
        }

        return $arg;
    }

    public static function formSelectProvince($name, $selectedValue = null, $options = [])
    {
        return Form::select($name, self::getNameList(), $selectedValue, $options);
    }

    public static function formSelectCity($name, $province, $selectedValue = null, $options = [])
    {
        return Form::select($name, self::getNameList($province), $selectedValue, $options);
    }

    public static function formSelectDistrict($name, $city, $selectedValue = null, $options = [])
    {
        return Form::select($name, self::getNameList($city), $selectedValue, $options);
    }

    public static function formSelectSubdistrict($name, $district, $selectedValue = null, $options = [])
    {
        return Form::select($name, self::getNameList($district), $selectedValue, $options);
    }

    public function getProvinceAttribute()
    {
        $arg = substr($this->code, 0, 2);

        return self::where('code', $arg)->first();
    }

    public function getCityAttribute()
    {
        $arg = substr($this->code, 0, 5);

        return self::where('code', $arg)->first();
    }

    public function getDistrictAttribute()
    {
        $arg = substr($this->code, 0, 8);

        return self::where('code', $arg)->first();
    }

    public function getSubdistrictAttribute()
    {
        $arg = $this->code;

        return self::where('code', $arg)->first();
    }

}
