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
        Schema::create('member_projects', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('description', 120);
            $table->string('frequency', 120);
            $table->string('status', 120);
            $table->integer('member_id');
            $table->timestamps();
        });

        Schema::create('member_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('date', 120);
            $table->string('quantity', 120);
            $table->string('dataset', 120);
            $table->integer('project_id');
            $table->string('sites',255);
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
        Schema::dropIfExists('member_projects');
        Schema::dropIfExists('member_tasks');
        
    }
};