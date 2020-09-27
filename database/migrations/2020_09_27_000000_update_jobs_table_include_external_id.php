<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateJobsTableIncludeExternalId extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('external_id', 100);
            $table->unique("external_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropUnique("external_id");
            $table->dropColumn('external_id');
        });
    }
}
