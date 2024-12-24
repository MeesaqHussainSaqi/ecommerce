<?php

use App\Config\Constant;
use Illuminate\Database\Schema\Blueprint;

/**
 * Use this function in every migration in which created_by, updated_by & deleted_by columns are required
 *
 * @param Blueprint $table
 * @return void
 */

if (!function_exists('AddDefaultColumns')) {
    function AddDefaultColumns(Blueprint $table)
    {
        $table->bigInteger('created_by')->nullable();
        $table->foreign('created_by')->references('id')->on('users');

        $table->bigInteger('updated_by')->nullable();
        $table->foreign('updated_by')->references('id')->on('users');

        $table->bigInteger('deleted_by')->nullable();
        $table->foreign('deleted_by')->references('id')->on('users');

        $table->integer('is_enable')->default(Constant::RecordType['ENABLED']); // Add 'is_enable' field
    }
}
