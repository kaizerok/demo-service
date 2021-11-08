<?php

namespace App\Models;

use App\Processors\Paymentwall;
use App\Processors\Processor;
use App\Processors\ProcessorsFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ProcessorProject extends Model
{
    const KEY_CACHED_LIST = 'processor_projects_cached_list';

    private Processor $processor;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'settings' => 'array',
    ];

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * @return mixed
     */
    public function getProjectId()
    {
        return $this->getAttribute('project_id');
    }

    /**
     * @return mixed
     */
    public function getProcessorId()
    {
        return $this->getAttribute('processor_id');
    }

    /**
     * @return mixed
     */
    public function getExternalId()
    {
        return $this->getAttribute('external_id');
    }

    /**
     * @return mixed
     */
    public function getSettings()
    {
        return $this->getAttribute('settings');
    }

    public function getProcessor()
    {
        if (!isset($this->processor)) {
            $this->processor = ProcessorsFactory::getById($this->getProcessorId());
        }

        return $this->processor;
    }

    public function getSetting(string $settingName)
    {
        return collect($this->getSettings())->get($settingName, '');
    }

    public function getConcatMerchantAndProjectId()
    {
        return "{$this->getSetting('merchant_id')}_{$this->getExternalId()}";
    }

    public function getMerchantId()
    {
        return $this->getSetting('merchant_id');
    }

    public function getMerchantName()
    {
        return $this->getSetting('merchant_name');
    }



    /**
     * @param string $externalId
     * @param bool   $fromCached
     *
     * @return ProcessorProject
     */
    public static function findPWByExternalId($externalId, $fromCached = true)
    {
        return self::findByProcessorAndExternalId(Paymentwall::ID, $externalId, $fromCached);
    }

    /**
     * @param int    $processor
     * @param string $externalId
     * @param bool   $fromCached
     *
     * @return ProcessorProject
     */
    public static function findByProcessorAndExternalId($processor, $externalId, $fromCached = true)
    {
        if ($fromCached) {
            return self::getCachedList()
                ->where('processor_id', $processor)
                ->where('external_id', $externalId)
                ->first();
        }

        return self::where([
            'processor_id' => $processor,
            'external_id' => $externalId
        ])->first();
    }

    /**
     * @return Collection
     */
    public static function getCachedList(): Collection
    {
        return Cache::remember(self::KEY_CACHED_LIST, 60, function () {
            return self::all();
        });
    }
}
