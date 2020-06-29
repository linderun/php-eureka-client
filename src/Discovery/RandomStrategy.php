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

        // 获取上线状态的实例
        $onlineInstances = [];
        foreach ($instances as $_instance) {
            if (strtoupper($_instance['status']) == 'UP') {
                $onlineInstances[] = $_instance;
            }
        }

        return $onlineInstances[rand(0, count($onlineInstances) - 1)];
    }
}