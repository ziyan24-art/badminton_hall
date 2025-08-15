<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShuttleTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shuttle_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();

            // Diubah dari foreignId menjadi string
            $table->string('shuttlecock_brand');
            $table->string('payment_type');

            $table->integer('quantity');
            $table->decimal('total_price', 12, 2);
            $table->string('payment_proof')->nullable();
            $table->enum('status', ['pending', 'paid', 'verified', 'canceled'])->default('pending');
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
        Schema::dropIfExists('shuttle_transactions');
    }
}
