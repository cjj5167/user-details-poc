<?php

namespace App;

use Illuminate\Support\Facades\Cache;

class CircuitBreaker {
    private $name;
    private $redis;

    public function __construct(string $name) {
        $this->name = $name;
        $this->redis = Cache::store("redis");
    }

    public function isEnabled(): bool {
        $state_key = "circuit_breaker.{$this->name}.state";
        return $this->redis->get($state_key, "enabled") !== "disabled";
    }

    public function enable(): void {
        $this->setState("enabled");
    }

    public function disable(): void {
        $this->setState("disabled");
    }

    private function setState($state): void {
        $state_key = "circuit_breaker.{$this->name}.state";
        $this->redis->forever($state_key, $state);
    }
}
