<?php

namespace App\Models;

use App\Processors\Paymentwall;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Class ProcessorPaymentSystem
 *
 * @property PaymentSystem $paymentSystem
 *
 * @package App\Models
 */
class ProcessorPaymentSystem extends Model
{
    use HasFactory;

    const KEY_CACHED_LIST = 'processor_payment_systems_cached_list';

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
     * @return ProcessorPaymentSystem
     */
    public static function findPWByCode($code, $fromCached = true)
    {
        return self::findByProcessorAndCode(Paymentwall::ID, $code, $fromCached);
    }

    /**
     * @param int    $processor
     * @param string $code
     * @param bool   $fromCached
     *
     * @return ProcessorPaymentSystem
     */
    public static function findByProcessorAndCode($processor, $code, $fromCached = true)
    {
        if ($fromCached) {
            return self::getCachedList()
                ->where('processor_id', $processor)
                ->where('code', $code)
                ->first();
        }

        return self::where([
            'processor_id' => $processor,
            'code' => $code
        ])->first();
    }

    /**
     * @return Collection
     */
    public static function getCachedList(): Collection
    {
        return Cache::remember(self::KEY_CACHED_LIST, 60, function () {
            return self::with('paymentSystem')->get();
        });
    }

    /**
     * @return BelongsTo
     */
    public function paymentSystem()
    {
        return $this->belongsTo(PaymentSystem::class);
    }
}
