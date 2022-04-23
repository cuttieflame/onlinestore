<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->foreign("user_id")->references("id")->on("users")->onDelete('cascade');
            $table->string("name",200);
            $table->string("currency",50);
            $table->integer("amount");
            $table->string("last4",5);
            $table->string("card_brand",25);
            $table->string("country",100);
            $table->string("customer",100);
            $table->string("risk_level",100);
            $table->integer("risk_score");
            $table->string("pi",200);
            $table->string("pm",200);
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
        Schema::dropIfExists('payments');
    }
}
