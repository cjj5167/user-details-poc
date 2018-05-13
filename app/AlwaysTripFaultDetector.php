<?php

namespace App;

class AlwaysTripFaultDetector implements FaultDetector
{
    public function recordSuccess(): void
    {
        //NOOP
    }

    public function recordFailure(\Exception $e = null): bool
    {
        return true;
    }
}
