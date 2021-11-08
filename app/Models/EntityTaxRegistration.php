<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class EntityTaxRegistration extends Model
{
    use HasFactory;

    const KEY_CACHED_LIST = 'entity_tax_registrations_cached_list';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entity_id',
        'country_id',
        'registered_at',
        'declared_at',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'registered_at' => 'datetime',
        'declared_at' => 'datetime',
    ];

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $code
     * @param bool   $fromCached
     *
     * @return mixed
     */
    public static function findByEntity($code, $fromCached = true)
    {
        if ($fromCached) {
            return self::getCachedList()->where('code', $code)->first();
        }

        return self::where('code', $code)->first();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function getCachedList(): Collection
    {
        return Cache::remember(self::KEY_CACHED_LIST, 60 * 24, function () {
            return self::all();
        });
    }
}
