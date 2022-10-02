<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notification;

interface ModelEventInterface
{
    function getModel(): Model;

    function getNotification(): Notification;
}
