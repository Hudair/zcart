<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('path');
            $table->text('name')->nullable();
            $table->string('extension')->nullable();
            $table->string('size')->nullable();
            $table->unsignedInteger('attachable_id');
            $table->string('attachable_type');
            $table->bigInteger('ownable_id')->nullable()->default(Null);
            $table->string('ownable_type')->nullable()->default(Null);
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
        Schema::dropIfExists('attachments');
    }
}
