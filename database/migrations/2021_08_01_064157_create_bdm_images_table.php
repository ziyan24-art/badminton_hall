<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBdmImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bdm_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bdm_field_id')
                ->nullable()
                ->constrained('bdm_fields')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->text('img')->nullable();
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
        Schema::dropIfExists('bdm_images');
    }
}
