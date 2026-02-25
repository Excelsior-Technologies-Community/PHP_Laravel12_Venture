<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('podcasts', function (Blueprint $table) {
            $table->unsignedBigInteger('workflow_id')->nullable()->after('is_processed');
        });
    }

    public function down()
    {
        Schema::table('podcasts', function (Blueprint $table) {
            $table->dropColumn('workflow_id');
        });
    }
};