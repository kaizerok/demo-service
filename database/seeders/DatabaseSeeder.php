<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(ProjectSeeder::class);
        $this->call(ProcessorProjectsSeeder::class);
        $this->call(RegionSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(PaymentSystemSeeder::class);
        $this->call(ProcessorPaymentSystemSeeder::class);
        $this->call(EntitySeeder::class);
        $this->call(EntityTaxRegistrationSeeder::class);
        $this->call(ProjectEntityRuleSeeder::class);
    }
}
