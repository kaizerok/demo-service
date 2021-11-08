<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Class Entity
 *
 * @property Country $country
 *
 * @package App\Models
 */
class Entity extends Model
{
    use HasFactory;

    const KEY_CACHED_LIST = 'entities_cached_list';

    const CODE_MASTER = 'master';
    const CODE_VAT_PL = 'vat_pl';
    const CODE_PW_INC = 'pw_inc';
    const CODE_PAYMENTWALL_INC = 'paymentwall_inc';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'short_name',
        'name',
        'country_id',
        'currency_id',
        'address',
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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getShortName()
    {
        return $this->short_name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isMaster()
    {
        return $this->getCode() === self::CODE_MASTER;
    }

    /**
     * @param int  $id
     * @param bool $fromCached
     *
     * @return Entity
     */
    public static function findById($id, $fromCached = true)
    {
        if ($fromCached) {
            return self::getCachedList()->where('id', $id)->first();
        }

        return self::find($id);
    }

    /**
     * @param string $code
     * @param bool   $fromCached
     *
     * @return Entity
     */
    public static function findByCode($code, $fromCached = true)
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

    /**
     * @return Entity
     */
    public static function getMaster()
    {
        return self::findByCode(self::CODE_MASTER);
    }

    /**
     * @return Entity
     */
    public static function getDefaultProcessing()
    {
        return self::findByCode(self::CODE_PW_INC);
    }

    /**
     * @return HasMany
     */
    public function entityTaxRegistrations()
    {
        return $this->hasMany(EntityTaxRegistration::class);
    }

    public function isTaxPayable(int $countryId)
    {
        return $this->entityTaxRegistrations()
            ->where('country_id', $countryId)
            ->whereDate('declared_at', '<', time())
            ->latest()->exists();
    }

    /**
     * @return BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
