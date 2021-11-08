<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Region extends Model
{
    use HasFactory;

    const KEY_CACHED_LIST = 'regions_cached_list';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get all countries of region.
     *
     * @return HasMany
     */
    public function countries()
    {
        return $this->hasMany(Country::class);
    }

    /**
     * Find region by id.
     *
     * @param int  $id
     * @param bool $fromCached
     *
     * @return mixed
     */
    public static function findById($id, $fromCached = true)
    {
        if ($fromCached) {
            return self::getCachedList()->where('id', $id)->first();
        }

        return self::find($id);
    }

    /**
     * Return cached list regions with countries.
     *
     * @return Collection
     */
    public static function getCachedList()
    {
        return Cache::remember(self::KEY_CACHED_LIST, 60 * 24, function () {
            return self::with('countries')->get();
        });
    }
}
