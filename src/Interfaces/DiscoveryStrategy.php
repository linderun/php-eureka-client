<?php

namespace EurekaClient\Interfaces;

interface DiscoveryStrategy
{
    /**
     * @param array $instances
     * @return string
     */
    public function getInstance($instances);

}