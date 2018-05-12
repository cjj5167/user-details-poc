<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CircuitBreaker extends Model
{
    public function isEnabled(): bool
    {
        return $this->state === "enabled";
    }
}
