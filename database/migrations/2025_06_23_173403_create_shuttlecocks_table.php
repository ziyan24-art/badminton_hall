<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shuttlecocks', function (Blueprint $table) {
            $table->id();
            $table->string('brand'); // merek bola
            $table->integer('stock')->default(0); // stok
            $table->bigInteger('price')->default(0); // harga satuan
            $table->boolean('is_available')->default(true); // status ketersediaan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shuttlecocks');
    }
};
