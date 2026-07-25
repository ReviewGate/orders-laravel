<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** A schema change ships as a migration in the same pull request (ADR-008). */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->uuid('customer_id');
            $table->string('customer_email', 254);
            $table->string('status', 16)->default('new');
            $table->bigInteger('total_cents');
            $table->char('currency', 3)->default('USD');
            $table->string('payment_transaction_id', 64)->nullable();

            // Internal fields: purchase price, manager note, cancellation reason (they never go out).
            $table->bigInteger('cost_price_cents')->nullable();
            $table->text('manager_note')->nullable();
            $table->string('cancellation_reason', 200)->nullable();

            $table->timestamps();

            $table->index('status', 'idx_orders_status');
        });

        Schema::create('order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('sku', 64);
            $table->string('title', 200);
            $table->integer('quantity');
            $table->bigInteger('unit_price_cents');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
