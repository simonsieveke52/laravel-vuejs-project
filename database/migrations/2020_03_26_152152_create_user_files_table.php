<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_files', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->boolean('processed')->default(false);
            $table->integer('total_rows')->default(0);
            $table->integer('current_row')->default(0);
            $table->longText('file_errors')->nullable();
            $table->string('file_type')->default('boh');
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();
            $table->boolean('status')->nullable()->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_files');
    }
}
