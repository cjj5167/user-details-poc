<?php

namespace App;

interface FaultDetector
{
    public function recordSuccess(): void;
    public function recordFailure(\Exception $e = null): bool;
}
