<?php

namespace EurekaClient\Discovery;

use EurekaClient\Interfaces\DiscoveryStrategy;

class RandomStrategy implements DiscoveryStrategy
{
    /**
     * @param array $instances
     * @return array|null
     */
    public function getInstance($instances): ?array
    {
        if (count($instances) == 0) {
            return null;
        }

        return $instances[rand(0, count($instances) - 1)];
    }
}