<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQuizStatusToQuizTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quiz', function (Blueprint $table) {
            $table->enum('quiz_status', ['DRAFTED', 'EXECUTION_STARTED', 'EXECUTION_DONE', 'RESULTS_OUT'])->default('DRAFTED');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quiz', function (Blueprint $table) {
            $table->dropColumn('quiz_status');
        });
    }
}
