<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('factor_no')->unique();
            $table->unsignedBigInteger('portal_id')->nullable()->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('buyer_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('postal_code')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('purchased_at')->nullable(); // jalali from panel
            $table->string('issued_at')->nullable();
            $table->unsignedBigInteger('amount')->default(0);
            $table->unsignedBigInteger('shipping_amount')->default(0);
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->string('status')->default('new'); // new|done|settlement
            $table->boolean('is_confirmed')->default(false);
            $table->boolean('is_prepared')->default(false);
            $table->boolean('is_left_warehouse')->default(false);
            $table->boolean('is_shipped')->default(false);
            $table->boolean('detail_fetched')->default(false);
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('line_no')->default(1);
            $table->text('title')->nullable();
            $table->text('specs')->nullable();
            $table->string('qty')->nullable();
            $table->unsignedBigInteger('unit_price')->default(0);
            $table->unsignedBigInteger('line_total')->default(0);
            $table->timestamps();
        });

        Schema::table('settlements', function (Blueprint $table) {
            if (! Schema::hasColumn('settlements', 'factor_no')) {
                $table->string('factor_no')->nullable()->index()->after('invoice_no');
            }
            if (! Schema::hasColumn('settlements', 'portal_id')) {
                $table->unsignedBigInteger('portal_id')->nullable()->index()->after('user_id');
            }
            if (! Schema::hasColumn('settlements', 'deposit_ref')) {
                $table->string('deposit_ref')->nullable()->after('amount');
            }
            if (! Schema::hasColumn('settlements', 'payment_note')) {
                $table->string('payment_note')->nullable()->after('deposit_ref');
            }
            if (! Schema::hasColumn('settlements', 'paid_at_jalali')) {
                $table->string('paid_at_jalali')->nullable()->after('paid_at');
            }
            if (! Schema::hasColumn('settlements', 'meta')) {
                $table->json('meta')->nullable()->after('is_seen');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settlements', function (Blueprint $table) {
            foreach (['factor_no', 'portal_id', 'deposit_ref', 'payment_note', 'paid_at_jalali', 'meta'] as $col) {
                if (Schema::hasColumn('settlements', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
