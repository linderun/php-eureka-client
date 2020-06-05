<?php

namespace EurekaClient\Models;

/**
 * Class DataCenterInfo
 * @package EurekaClient\Models
 */
class DataCenterInfo extends Parameters
{
    /**
     * 默认设置
     * ['com.netflix.appinfo.InstanceInfo$DefaultDataCenterInfo', 'MyOwn']
     * @param string $class
     * @param string $name
     * @param Metadata $metadata
     */
    public function __construct(string $class = 'com.netflix.appinfo.InstanceInfo$DefaultDataCenterInfo', string $name = 'MyOwn', Metadata $metadata = null)
    {
        $class && $this->setClass($class);
        $name && $this->setName($name);
        !is_null($metadata) && $this->setMetadata($metadata);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name)
    {
        // MyOwn
        return $this->set('name', $name);
    }

    /**
     * @param string $class
     * @return $this
     */
    public function setClass(string $class)
    {
        // com.netflix.appinfo.InstanceInfo$DefaultDataCenterInfo
        return $this->set('@class', $class);
    }

    /**
     * @param Metadata $metadata
     * @return $this
     */
    public function setMetadata(Metadata $metadata)
    {
        return $this->set('metadata', $metadata->export());
    }
}
