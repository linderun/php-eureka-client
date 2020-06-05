<?php

namespace EurekaClient\Interfaces;

interface InstanceProvider
{
    /**
     * @param string $appId
     * @return array
     */
    public function getInstances($appId);

}