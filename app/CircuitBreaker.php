<?php

namespace App;

use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Cache;

class CircuitBreaker {
    private $name;
    private $redis;
    private $fault_detector;

    public function __construct(string $name) {
        $this->name = $name;
        $this->redis = Cache::store("redis");
        $this->fault_detector = new FailureWindowFaultDetector($name, 2, CarbonInterval::minutes(1));
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

    public function recordSuccess(): void {
        $this->fault_detector->recordSuccess();
    }

    public function recordFailure(\Exception $e = null): void {
        $should_trip = $this->fault_detector->recordFailure($e);
        if ($should_trip) {
            $this->disable();
        }
    }
}
