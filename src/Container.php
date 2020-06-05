<?php

namespace EurekaClient;

use EurekaClient\Discovery\RandomStrategy;
use EurekaClient\Interfaces\DiscoveryStrategy;
use EurekaClient\Interfaces\InstanceProvider;
use GuzzleHttp\Client;

/**
 * Class Container
 * @package EurekaClient
 */
class Container
{
    /**
     * @var DiscoveryStrategy
     */
    protected $discoveryStrategy;

    /**
     * @var InstanceProvider
     */
    protected $instanceProvider;

    /**
     * @var Client $httpClient
     */
    protected $httpClient;

    /**
     * EurekaConfig constructor.
     */
    public function __construct()
    {
        if (empty($this->httpClient)) {
            $this->httpClient = new Client();
        }

        if (empty($this->discoveryStrategy)) {
            $this->discoveryStrategy = new RandomStrategy();
        }
    }

    /**
     * @param Client $httpClient
     */
    public function setHttpClient(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return Client
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @param DiscoveryStrategy $discoveryStrategy
     */
    public function setDiscoveryStrategy(DiscoveryStrategy $discoveryStrategy)
    {
        $this->discoveryStrategy = $discoveryStrategy;
    }

    /**
     * @return DiscoveryStrategy
     */
    public function getDiscoveryStrategy()
    {
        return $this->discoveryStrategy;
    }

    /**
     * @param InstanceProvider $instanceProvider
     */
    public function setInstanceProvider(InstanceProvider $instanceProvider)
    {
        $this->instanceProvider = $instanceProvider;
    }

    /**
     * @return InstanceProvider
     */
    public function getInstanceProvider()
    {
        return $this->instanceProvider;
    }
}