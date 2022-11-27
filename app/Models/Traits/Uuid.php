<?php

namespace App\Models\Traits;

use \Ramsey\Uuid\Uuid as RamseyUuidHandler;

trait Uuid
{

    public static function boot()
    {

        parent::boot();

        static::creating( function ( $obj ) {

            $obj->id = RamseyUuidHandler::uuid4()->toString();

        } );

    }

}
