<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel orders
            $table->foreignId("order_id")
                ->nullable()
                ->constrained("orders")
                ->onUpdate("cascade")
                ->onDelete("cascade");

            // Jenis transaksi (DP / Pelunasan)
            $table->foreignId("transaction_type_id")
                ->nullable()
                ->constrained("transaction_types")
                ->onUpdate("cascade")
                ->onDelete("set null");

            // Metode pembayaran (Transfer / Tunai)
            $table->foreignId("payment_type_id")
                ->nullable()
                ->constrained("payment_types")
                ->onUpdate("cascade")
                ->onDelete("set null");

            // Upload bukti pembayaran (file path di storage)
            $table->string("proof_file")->nullable();

            // Kode unik tambahan (misalnya: 123 dari 50.123)
            $table->unsignedSmallInteger("code")->nullable();

            // Jumlah pembayaran (contoh: 50000.00)
            $table->decimal("amount", 12, 2)->nullable();

            // Validasi transaksi (0 = belum valid, 1 = valid)
            $table->enum("is_valid", ['0', '1'])->default('0');

            // Waktu maksimal pembayaran
            $table->timestamp("expired_payment")->nullable();

            // Waktu create/update
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
}
