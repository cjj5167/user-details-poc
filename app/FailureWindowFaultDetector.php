<?php

namespace App;

use Carbon\CarbonInterval;
use Predis\Client as Redis;
use Predis\Pipeline\Pipeline;

class FailureWindowFaultDetector implements FaultDetector
{
    private $name;
    private $max_failures;
    private $sampling_window;
    private $redis;

    public function __construct(string $name, int $max_failures, CarbonInterval $window)
    {
        $this->name = $name;
        $this->max_failures = $max_failures;
        $this->sampling_window = $window;
        $this->redis = app(Redis::class);
    }

    public function recordSuccess(): void
    {
        //NOOP
    }

    /**
     * For Redis geeks only: use a sorted set of failure timestamps for windowing
     */
    public function recordFailure(\Exception $e = null): bool
    {
        $key = "failure_window.{$this->name}";
        $now = time();
        $window_duration_seconds = $this->sampling_window->seconds;
        $beginning_of_window = $now - $window_duration_seconds;
        $redis_ops = function (Pipeline $pipeline) use ($key, $now, $window_duration_seconds, $beginning_of_window) {
            $pipeline->zremrangebyscore($key, 0, $beginning_of_window); //delete everything that is too old
            $pipeline->zadd($key, [(string) $now => $now]);  //add this failure
            $pipeline->expire($key, $window_duration_seconds); //update the TTL
            $pipeline->zcard($key);  //see how many failures we have now
        };
        $batch_result = $this->redis->pipeline(["atomic" => true], $redis_ops);
        $failure_count = $batch_result[count($batch_result)-1];
        return $failure_count > $this->max_failures;
    }
}
