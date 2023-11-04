<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->text('registration_number')->nullable();
            $table->text('name')->nullable();
            $table->text('additional_contact_info')->nullable();
            $table->json('cancellation_info')->nullable();
            $table->string('starting_price')->nullable();
            $table->string('currency_title')->nullable();
            $table->timestamp('purchase_start_datetime')->nullable();
            $table->timestamp('purchase_end_datetime')->nullable();
            $table->text('deliverable_group_title')->nullable();
            $table->boolean('with_lots')->nullable();
            $table->integer('proposal_validity_days')->nullable();
            $table->integer('fee_amount')->nullable();
            $table->json('supplier_requirements')->nullable();
            $table->unsignedBigInteger('status')->nullable();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->json('edms')->nullable();
            $table->json('actions')->nullable();
            $table->json('deliverables')->nullable();
            $table->json('product')->nullable();
            $table->json('contract_draft_attachment')->nullable();
            $table->json('notice_supplementary_attachments')->nullable();
            $table->json('proposals')->nullable();
            $table->unsignedBigInteger('category')->nullable();
            $table->text('gpt_analyze')->nullable();
            $table->longText('gpt_description')->nullable();
            $table->json('envelopes_opening_protocol')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
