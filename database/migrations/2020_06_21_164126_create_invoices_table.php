<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->foreignId('invoice_serie_id');
            $table->string('number')->unique();
            $table->date('date');
            $table->date('due_date')->nullable();
            $table->text('notes')->nullable();
            $table->integer('subtotal')->default(0);
            $table->integer('taxes')->default(0);
            $table->integer('total')->default(0);
            $table->boolean('sent')->default(0);
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('invoice_serie_id')->references('id')->on('invoice_series');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
