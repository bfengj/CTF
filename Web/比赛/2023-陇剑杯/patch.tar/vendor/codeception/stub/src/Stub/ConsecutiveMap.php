<?php

declare(strict_types=1);

namespace Codeception\Stub;

/**
 * Holds matcher and value of mocked method
 */
class ConsecutiveMap
{
    private array $consecutiveMap = [];

    public function __construct(array $consecutiveMap)
    {
        $this->consecutiveMap = $consecutiveMap;
    }

    public function getMap(): array
    {
        return $this->consecutiveMap;
    }
}
