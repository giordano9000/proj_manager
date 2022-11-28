<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use BenSampo\Enum\Rules\EnumValue;
use App\Enums\Status;

return new class extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {

            $table->uuid('id')->nullable(false);
            $table->string('title')->nullable(false);
            $table->text('description')->nullable();
            $table->string('slug')->nullable(false);
            $table->enum( 'status', Status::getValues() )->default( Status::OPEN );

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
        Schema::dropIfExists('projects');
    }

};
