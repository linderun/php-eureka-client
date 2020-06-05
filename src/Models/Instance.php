<?php

namespace EurekaClient\Models;

/**
 * Class Instance
 * @package EurekaClient\Models
 */
class Instance extends Parameters
{
    /**
     * @param string $instanceId
     * @return $this
     */
    public function setInstanceId(string $instanceId)
    {
        return $this->set('instanceId', $instanceId);
    }

    /**
     * @param string $hostName
     * @return $this
     */
    public function setHostName(string $hostName)
    {
        return $this->set('hostName', $hostName);
    }

    /**
     * @param string $app
     * @return $this
     */
    public function setApp(string $app)
    {
        return $this->set('app', $app);
    }

    /**
     * @param string $ipAddr
     * @return $this
     */
    public function setIpAddr(string $ipAddr)
    {
        return $this->set('ipAddr', $ipAddr);
    }

    /**
     * @param int $port
     * @param bool $enabled
     * @return $this
     */
    public function setPort(int $port, $enabled = true)
    {
        return $this->set('port', [
            '$'        => $port,
            '@enabled' => ($enabled) ? 'true' : 'false'
        ]);
    }

    /**
     * @param int $port
     * @param bool $enabled
     * @return $this
     */
    public function setSecurePort(int $port, $enabled = true)
    {
        return $this->set('securePort', [
            '$'        => $port,
            '@enabled' => ($enabled) ? 'true' : 'false'
        ]);
    }

    /**
     * @param string $homePageUrl
     * @return $this
     */
    public function setHomePageUrl(string $homePageUrl)
    {
        return $this->set('homePageUrl', $homePageUrl);
    }

    /**
     * @param string $statusPageUrl
     * @return $this
     */
    public function setStatusPageUrl(string $statusPageUrl)
    {
        return $this->set('statusPageUrl', $statusPageUrl);
    }

    /**
     * @param string $healthCheckUrl
     * @return $this
     */
    public function setHealthCheckUrl(string $healthCheckUrl)
    {
        return $this->set('healthCheckUrl', $healthCheckUrl);
    }

    /**
     * @param string $secureHealthCheckUrl
     * @return $this
     */
    public function setSecureHealthCheckUrl(string $secureHealthCheckUrl)
    {
        return $this->set('secureHealthCheckUrl', $secureHealthCheckUrl);
    }

    /**
     * @param string $vipAddress
     * @return $this
     */
    public function setVipAddress(string $vipAddress)
    {
        return $this->set('vipAddress', $vipAddress);
    }

    /**
     * @param string $secureVipAddress
     * @return $this
     */
    public function setSecureVipAddress(string $secureVipAddress)
    {
        return $this->set('secureVipAddress', $secureVipAddress);
    }

    /**
     * @param Metadata $metadata
     * @return $this
     */
    public function setMetadata(Metadata $metadata)
    {
        return $this->set('metadata', $metadata->export());
    }

    /**
     * @param DataCenterInfo $dataCenterInfo
     * @return $this
     */
    public function setDataCenterInfo(DataCenterInfo $dataCenterInfo)
    {
        return $this->set('dataCenterInfo', $dataCenterInfo->export());
    }
}