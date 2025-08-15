<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBdmFieldHasBallTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bdm_field_has_ball_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId("bdm_field_id")
                ->nullable()
                ->constrained("bdm_fields")
                ->onUpdate("cascade")
                ->onDelete('cascade');
            $table->foreignId("ball_type_id")
                ->nullable()
                ->constrained("ball_types")
                ->onUpdate("cascade")
                ->onDelete('cascade');
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
        Schema::dropIfExists('bdm_field_has_ball_types');
    }
}
