<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_types', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name')->nullable();              // Nama Bank (jika bukan cash)
            $table->string('bank_code', 25)->nullable();           // Kode Bank (jika ada)
            $table->string('holder_name')->nullable();             // Atas nama
            $table->string('bank_account')->nullable();            // Nomor rekening
            $table->text('instruction')->nullable();               // Instruksi pembayaran
            $table->boolean('is_cash')->default(false);            // Apakah metode ini adalah cash?
            $table->enum('is_active', [0, 1])->default(1);         // Aktif atau tidak
            $table->timestamps();                                  // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_types');
    }
}
