<?php

namespace App\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    const DUE_DATETIME_FORMAT = 'Y-m-d H:i:s';
    const NAMESPACE_UUID = 'c8bc2dc4-0495-11ed-b939-0242ac120002';

    public function save(array $options = []): bool
    {
        if (!$this->exists && empty($this->uuid)) {
            $this->uuid = Uuid::uuid5(ns: self::NAMESPACE_UUID, name: $this->title);
        }
        return parent::save($options);
    }

}
