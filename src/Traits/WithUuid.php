<?php

namespace RBFraphael\LaravelBoilerplate\Traits;

use Illuminate\Support\Str;

trait WithUuid
{
    protected static function bootWithUuid()
    {
        static::creating(function($model){
            $model->uuid = Str::uuid7();
        });
    }

    public static function resolveIdFromUuid(string $uuid): ?int
    {
        return self::where('uuid', $uuid)->value('id');
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected function getArrayableItems(array $values)
    {
        if (!in_array('id', $this->hidden)) {
            $this->hidden[] = 'id';
        }
        return parent::getArrayableItems($values);
    }
}
