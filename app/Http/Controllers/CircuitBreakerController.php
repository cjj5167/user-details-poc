<?php

namespace App\Http\Controllers;

use App\CircuitBreaker;

class CircuitBreakerController extends Controller
{
    public function index(): array
    {
        $cs = new CircuitBreaker("social");
        return [$cs];
    }

    public function show(string $name): CircuitBreaker
    {
        return new CircuitBreaker($name);
    }

    public function reset(string $name): CircuitBreaker
    {
        $c = new CircuitBreaker($name);
        $c->enable();
        return $c;
    }
}
