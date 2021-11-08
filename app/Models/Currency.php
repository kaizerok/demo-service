<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Currency extends Model
{
    use HasFactory;

    const KEY_CACHED_LIST = 'currencies_cached_list';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get id of the currency.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Find currency by currency code.
     *
     * @param string $code
     * @param bool   $fromCached
     *
     * @return mixed
     */
    public static function findByCode($code, $fromCached = true)
    {
        if ($fromCached) {
            return self::getCachedList()->where('code', $code)->first();
        }

        return self::where('code', $code)->first();
    }

    /**
     * Return cached currencies list.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getCachedList()
    {
        return Cache::remember(self::KEY_CACHED_LIST, 60 * 24, function () {
            return self::all();
        });
    }
}
