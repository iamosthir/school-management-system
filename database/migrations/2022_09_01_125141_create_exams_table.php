<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->dateTime("start_time")->nullable();
            $table->dateTime("end_time")->nullable();
            $table->integer("school_id");
            $table->integer("class_id");
            $table->integer("section_id");
            $table->integer("category_id");
            $table->integer("user_id");
            $table->string("created_by");
            $table->string("status")->default("draft");
            $table->text("formla")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams');
    }
};
