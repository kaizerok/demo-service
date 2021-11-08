<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectEntityRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_entity_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedSmallInteger('payment_system_id');
            $table->unsignedSmallInteger('country_id');
            $table->unsignedTinyInteger('processing_entity_id');
            $table->unsignedTinyInteger('tax_entity_id');
            $table->unsignedTinyInteger('payout_entity_id');
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('payment_system_id')->references('id')->on('payment_systems');
            $table->foreign('processing_entity_id')->references('id')->on('entities');
            $table->foreign('tax_entity_id')->references('id')->on('entities');
            $table->foreign('payout_entity_id')->references('id')->on('entities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_entity_rules');
    }
}
