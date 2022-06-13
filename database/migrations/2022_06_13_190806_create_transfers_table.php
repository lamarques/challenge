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
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payer')->unsigned();
            $table->unsignedBigInteger('payee')->unsigned();
            $table->date('appointment_date');
            $table->decimal('amount', 12, 2);
            $table->char('status', 1);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('payer')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('payee')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfers');
    }
};
