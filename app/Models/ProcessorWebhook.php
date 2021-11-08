<?php

namespace App\Models;

use Exception;
use GuzzleHttp\Utils;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ProcessorWebhook extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'processor_project_id',
        'reference',
        'payload',
    ];

    /**
     * @param int    $processorProjectId
     * @param string $reference
     * @param array  $payload
     *
     * @return self|null
     */
    public static function add(int $processorProjectId, string $reference, array $payload)
    {
        try {
            $model = self::create([
                'processor_project_id' => $processorProjectId,
                'reference' => $reference,
                'payload' => Utils::jsonEncode($payload)
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

        return $model ?? null;
    }

    public static function getDataByReference($reference)
    {
        $webhook = self::where('reference', $reference)->first();
        $webhookData = $webhook->getAttribute('payload');
        return $webhookData ? json_decode($webhookData, true) : [];
    }

    public static function getProcessorProjectByReference($reference)
    {
        $processorProjectId = (self::where('reference', $reference)->first())
            ->getAttribute('processor_project_id');

        return ProcessorProject::find($processorProjectId);
    }
}
