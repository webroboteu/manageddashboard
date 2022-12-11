<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_project', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('description', 120);
            $table->string('frequency', 120);
            $table->string('status', 120);
            $table->timestamps();
        });

        Schema::create('member_task', function (Blueprint $table) {
            $table->id();
            $table->string('date', 120);
            $table->string('quantity', 120);
            $table->string('dataset', 120);
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
        Schema::dropIfExists('member_project');
        Schema::dropIfExists('member_task');
        
    }
};