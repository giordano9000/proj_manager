<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use BenSampo\Enum\Rules\EnumValue;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;

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
            $table->string( 'title' )->unique()->nullable( false );
            $table->text( 'description' );
            $table->smallInteger( 'difficulty' );
            $table->enum( 'status', TaskStatus::getValues() )->default( TaskStatus::OPEN );
            $table->enum( 'priority', TaskPriority::getValues() )->default( TaskPriority::LOW );

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
