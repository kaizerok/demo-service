<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Country extends Model
{
    use HasFactory;

    const KEY_CACHED_LIST = 'countries_cached_list';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'region_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get id of the country.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get code of the country.
     *
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Get name of the country.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int  $id
     * @param bool $fromCached
     *
     * @return Country
     */
    public static function findById($id, $fromCached = true)
    {
        if ($fromCached) {
            return self::getCachedList()->where('id', $id)->first();
        }

        return self::find($id);
    }

    /**
     * Find country by country code.
     *
     * @param string $code
     * @param bool   $fromCached
     *
     * @return Country
     */
    public static function findByCode($code, $fromCached = true)
    {
        if ($fromCached) {
            return self::getCachedList()->where('code', $code)->first();
        }

        return self::where('code', $code)->first();
    }

    /**
     * Return cached countries list.
     *
     * @return Collection
     */
    public static function getCachedList()
    {
        return Cache::remember(self::KEY_CACHED_LIST, 60 * 24, function () {
            return self::all();
        });
    }

    /**
     * Get the region of country.
     *
     * @return BelongsTo
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
