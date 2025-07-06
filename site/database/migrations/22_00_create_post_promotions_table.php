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
	public function up(): void
	{
		Schema::create('post_promotions', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('post_id')->unsigned();
			$table->integer('package_id')->unsigned();
			$table->bigInteger('payment_id')->unsigned()->nullable();
			
			// Type de promotion
			$table->enum('promotion_type', ['bump', 'top', 'featured', 'urgent', 'highlight']);
			
			// Dates
			$table->datetime('start_date');
			$table->datetime('end_date');
			
			// Métadonnées
			$table->decimal('price', 10, 2);
			$table->string('currency_code', 3);
			
			// Statut
			$table->enum('status', ['active', 'expired', 'cancelled', 'pending'])->default('pending');
			
			// Tracking
			$table->integer('views_count')->unsigned()->default(0);
			$table->integer('clicks_count')->unsigned()->default(0);
			
			// Audit
			$table->timestamps();
			
			// Foreign keys
			$table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
			$table->foreign('package_id')->references('id')->on('packages');
			$table->foreign('payment_id')->references('id')->on('payments');
			
			// Indexes
			$table->index(['post_id', 'promotion_type']);
			$table->index(['status', 'start_date', 'end_date']);
			$table->index(['promotion_type']);
			$table->index(['status', 'end_date']);
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(): void
	{
		Schema::dropIfExists('post_promotions');
	}
};