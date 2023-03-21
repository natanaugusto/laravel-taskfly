<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;

interface EventModelMailableInterface
{
    public function getModel(): Model;

    public function getMailable(): Mailable;
}
