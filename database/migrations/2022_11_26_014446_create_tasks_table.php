<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'tasks', function ( Blueprint $table ) {

            $table->uuid( 'id' )->nullable(false);
            $table->string( 'title' )->nullable( false );
            $table->text( 'description' );
            $table->smallInteger( 'difficulty' );
            $table->enum( 'status', [ 'open', 'block', 'close' ] );
            $table->enum( 'priority', [ 'low', 'medium', 'high', 'very high' ] )->default( 'low' );

            $table->uuid('assignee')->references('id')->on('users');
            $table->foreignUuid('project_id');

            $table->timestamps();

        } );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists( 'tasks' );
    }

};
