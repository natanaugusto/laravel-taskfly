<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ModelEventInterface
{
    public function getModel(): Model;
}
