<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PaymentSystem extends Model
{
    use HasFactory;

    const KEY_CACHED_LIST = 'payment_systems_cached_list';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ps_code',
        'public_name',
        'short_code',
        'partner_name',
        'is_active',
        'default_currency',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'is_active',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'bool',
    ];

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

//    public function isActive()
//    {
//        return (bool)$this->is_active;
//    }
//
    /**
     * Return ps name combined.
     *
     * @return string
     */
    public function getName(): string
    {
        return "{$this->public_name} ({$this->short_code})";
    }

    /**
     * Find payment system by short code.
     *
     * @param string $code
     * @param bool   $fromCached
     *
     * @return PaymentSystem
     */
    public static function findByShortCode($code, $fromCached = false)
    {
        if ($fromCached) {
            return self::getCachedList()->where('short_code', $code)->first();
        }

        return self::where('short_code', $code)->first();
    }

    /**
     * Return the list payment systems with cached.
     *
     * @return Collection
     */
    public static function getCachedList(): Collection
    {
        return Cache::remember(self::KEY_CACHED_LIST, 60, function () {
            return self::all();
        });
    }
}
