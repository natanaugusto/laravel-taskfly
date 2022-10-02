<?php

namespace App\Contracts;

use Illuminate\Mail\Mailable;
use Illuminate\Database\Eloquent\Model;

interface EventModelMailableInterface
{
    function getModel(): Model;
    function toMailable(): Mailable;
}
