<?php

namespace EurekaClient\Interfaces;

interface DiscoveryStrategy
{
    /**
     * @param array $instances
     * @return array
     */
    public function getInstance($instances);

}