<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Starting create regions...');

        collect(self::REGIONS)->map(function ($region) {
            $model = (new Region())->firstOrNew(['name' => $region]);

            if (optional($model)->getId()) {
                $this->command->warn("Skipped. Region `$region` already exist.");
            } else {
                $model->save();
                $this->command->info("Region `$region` has been created successfully.");
            }
        });

        $this->command->info('Cleaning cached list...');
        Cache::forget(Region::KEY_CACHED_LIST);

        $this->command->alert('Regions seed finished.');
    }

    const REGIONS = [
        'United States',
        'European Union',
        'non-EU European countries',
        'United Kingdom',
        'Canada',
        'Balkan countries',
        'Russia&CIS',
        'Asia',
        'South-East Asia',
        'China',
        'Brazil',
        'Latin America',
        'Middle East and North Africa',
        'Turkey',
        'Israel',
        'Africa',
        'Australia & Oceania',
        'Ukraine',
        'Sanctioned',
    ];
}
