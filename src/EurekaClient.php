<?php

namespace EurekaClient;

use EurekaClient\Exceptions\DeRegisterFailureException;
use EurekaClient\Exceptions\EurekaClientException;
use EurekaClient\Exceptions\HeartbeatFailureException;
use EurekaClient\Exceptions\RegisterFailureException;
use EurekaClient\Models\Instance;
use EurekaClient\Models\Metadata;

/**
 * Class EurekaClient
 * @package EurekaClient
 * @see https://github.com/Netflix/eureka/wiki/Eureka-REST-operations Eureka REST operations
 */
class EurekaClient
{
    /**
     * @var string host
     */
    protected $host;

    /**
     * @var int port
     */
    protected $port;

    /**
     * @var string $context
     */
    protected $context;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var array
     */
    protected $instances;

    /**
     * @var int $heartbeatInterval 心跳检测秒数
     */
    protected $heartbeatInterval = 30;

    /**
     * EurekaClient constructor.
     * @param string $host
     * @param int $port
     * @param string $context
     */
    public function __construct(string $host, int $port, string $context = 'eureka/v2')
    {
        $this->host = $host;
        $this->port = $port;
        $this->context = $context;

        if (empty($this->container)) {
            $this->container = new Container();
        }
    }

    /**
     * @return Container|null
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param string $message
     */
    protected function dump(string $message)
    {
        if (php_sapi_name() !== 'cli') {
            return;
        }

        $output = '[' . date("Y-m-d H:i:s") . '] ' . $message . "\n";

        echo $output;
    }

    /**
     * @return string
     */
    protected function getEurekaServerUri()
    {
        return $this->host . ':' . $this->port . '/' . $this->context;
    }

    /**
     * 注册app服务到eureka
     * @param string $appId
     * @param Instance $instance
     * @return \Exception|\Psr\Http\Message\ResponseInterface|\Throwable
     */
    public function register(string $appId, Instance $instance)
    {
        try {
            $response = $this->container->getHttpClient()->request('POST', $this->getEurekaServerUri() . '/apps/' . $appId, [
                'json' => [
                    'instance' => $instance->export()
                ]
            ]);

            if ($response->getStatusCode() != 204) {
                throw new RegisterFailureException("Could not register with Eureka.");
            }

            return $response;
        } catch (\Exception|\Throwable $e) {
            $this->dump("{$appId}服务注册失败，错误码：{$e->getCode()}，错误信息：{$e->getMessage()}");
            return $e;
        }
    }

    /**
     * 从eureka移除app服务
     * @param string $appId
     * @param string $instanceId
     * @return \Exception|\Psr\Http\Message\ResponseInterface|\Throwable
     */
    public function deRegister(string $appId, string $instanceId)
    {
        try {
            $response = $this->container->getHttpClient()
                ->request('DELETE', $this->getEurekaServerUri() . '/apps/' . $appId . '/' . $instanceId);

            if ($response->getStatusCode() != 200) {
                throw new DeRegisterFailureException("Cloud not de-register from Eureka.");
            }

            return $response;
        } catch (\Exception|\Throwable $e) {
            $this->dump("{$appId}|{$instanceId}服务移除失败，错误码：{$e->getCode()}，错误信息：{$e->getMessage()}");
            return $e;
        }
    }

    /**
     * 发送心跳检测到eureka
     * @param string $appId
     * @param string $instanceId
     * @return \Exception|\Psr\Http\Message\ResponseInterface|\Throwable
     */
    public function heartBeat(string $appId, string $instanceId)
    {
        try {
            $response = $this->container->getHttpClient()
                ->request('PUT', $this->getEurekaServerUri() . '/apps/' . $appId . '/' . $instanceId);

            if ($response->getStatusCode() != 200) {
                throw new HeartbeatFailureException("Heartbeat failed.");
            }

            return $response;
        } catch (\Exception|\Throwable $e) {
            $this->dump("{$appId}|{$instanceId}心跳检测失败，错误码：{$e->getCode()}，错误信息：{$e->getMessage()}");
            return $e;
        }
    }

    /**
     * 获取所有已注册的服务
     * @return array|bool|\Exception|\Throwable
     */
    public function getAllApps()
    {
        try {
            $response = $this->container->getHttpClient()->request('GET', $this->getEurekaServerUri() . '/apps', [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);

            if ($response->getStatusCode() != 200) {
                throw new EurekaClientException("Query for all instances failed.");
            }

            return json_decode($response->getBody(), true);

        } catch (\Exception|\Throwable $e) {
            $this->dump("获取所有已注册服务失败，错误码：{$e->getCode()}，错误信息：{$e->getMessage()}");
            return $e;
        }
    }

    /**
     * 获取某个服务所有实例
     * @param string $appId
     * @return array|bool|\Exception|\Throwable
     */
    public function getApp(string $appId)
    {
        try {
            $response = $this->container->getHttpClient()->request('GET', $this->getEurekaServerUri() . '/apps/' . $appId, [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);

            if ($response->getStatusCode() != 200) {
                throw new EurekaClientException("Query for all appID instances failed.");
            }

            return json_decode($response->getBody(), true);

        } catch (\Exception|\Throwable $e) {
            $this->dump("获取{$appId}服务所有实例失败，错误码：{$e->getCode()}，错误信息：{$e->getMessage()}");
            return $e;
        }
    }

    /**
     * 获取一个服务下面的一个实例
     * @param string $appId
     * @param string $instanceId
     * @return array|bool|\Exception|\Throwable
     */
    public function getAppInstance(string $appId, string $instanceId)
    {
        try {
            $response = $this->container->getHttpClient()->request('GET', $this->getEurekaServerUri() . '/apps/' . $appId . '/' . $instanceId, [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);

            if ($response->getStatusCode() != 200) {
                throw new EurekaClientException("Query for a specific appID/instanceID failed.");
            }

            return json_decode($response->getBody(), true);

        } catch (\Exception|\Throwable $e) {
            $this->dump("获取{$appId}|{$instanceId}服务所有实例失败，错误码：{$e->getCode()}，错误信息：{$e->getMessage()}");
            return $e;
        }
    }

    /**
     * 获取一个实例
     * @param $instanceId
     * @return array|bool|\Exception|\Throwable
     */
    public function getInstance(string $instanceId)
    {
        try {
            $response = $this->container->getHttpClient()->request('GET', $this->getEurekaServerUri() . '/instances/' . $instanceId, [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);

            if ($response->getStatusCode() != 200) {
                throw new EurekaClientException("Query for a specific instanceID failed.");
            }

            return json_decode($response->getBody(), true);

        } catch (\Exception|\Throwable $e) {
            $this->dump("获取{$instanceId}实例失败，错误码：{$e->getCode()}，错误信息：{$e->getMessage()}");
            return $e;
        }
    }

    /**
     * 下线一个服务实例
     * @param string $appId
     * @param string $instanceId
     * @return \Exception|\Psr\Http\Message\ResponseInterface|\Throwable
     */
    public function takeInstanceOut(string $appId, string $instanceId)
    {
        try {
            $response = $this->container->getHttpClient()->request('PUT', $this->getEurekaServerUri() . '/apps/' . $appId . '/' . $instanceId . '/status', [
                'query' => [
                    'value' => 'OUT_OF_SERVICE'
                ]
            ]);

            if ($response->getStatusCode() != 200) {
                throw new EurekaClientException("Take instance out of service failed.");
            }

            return $response;
        } catch (\Exception|\Throwable $e) {
            $this->dump("下线{$appId}|{$instanceId}实例失败，错误码：{$e->getCode()}，错误信息：{$e->getMessage()}");
            return $e;
        }
    }

    /**
     * 上线一个服务实例
     * @param string $appId
     * @param string $instanceId
     * @return \Exception|\Psr\Http\Message\ResponseInterface|\Throwable
     */
    public function putInstanceBack(string $appId, string $instanceId)
    {
        try {
            $response = $this->container->getHttpClient()->request('PUT', $this->getEurekaServerUri() . '/apps/' . $appId . '/' . $instanceId . '/status', [
                'query' => [
                    'value' => 'UP'
                ]
            ]);

            if ($response->getStatusCode() != 200) {
                throw new EurekaClientException("Move instance back into service (remove override) failed.");
            }

            return $response;
        } catch (\Exception|\Throwable $e) {
            $this->dump("上线{$appId}|{$instanceId}实例失败，错误码：{$e->getCode()}，错误信息：{$e->getMessage()}");
            return $e;
        }
    }

    /**
     * 更新一个服务实例的metadata
     * @param string $appId
     * @param string $instanceId
     * @param Metadata $metadata
     * @return \Exception|\Psr\Http\Message\ResponseInterface|\Throwable
     */
    public function updateAppInstanceMetadata(string $appId, string $instanceId, Metadata $metadata)
    {
        try {
            $response = $this->container->getHttpClient()->request('PUT', $this->getEurekaServerUri() . '/apps/' . $appId . '/' . $instanceId . '/metadata', [
                'query' => $metadata->export()
            ]);

            if ($response->getStatusCode() != 200) {
                throw new EurekaClientException("Update metadata failed.");
            }

            return $response;
        } catch (\Exception|\Throwable $e) {
            $this->dump("更新{$appId}|{$instanceId}metadata失败，错误码：{$e->getCode()}，错误信息：{$e->getMessage()}");
            return $e;
        }
    }

    /**
     * 根据vip address获取所有实例
     * @param string $vipAddress
     * @return array|bool|\Exception|\Throwable
     */
    public function getInstancesByVipAddress(string $vipAddress)
    {
        try {
            $response = $this->container->getHttpClient()->request('GET', $this->getEurekaServerUri() . '/vips/' . $vipAddress, [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);

            if ($response->getStatusCode() != 200) {
                throw new EurekaClientException("Query for all instances under a particular vip address failed.");
            }

            return json_decode($response->getBody(), true);

        } catch (\Exception|\Throwable $e) {
            $this->dump("获取{$vipAddress}下所有实例失败，错误码：{$e->getCode()}，错误信息：{$e->getMessage()}");
            return $e;
        }
    }

    /**
     * 根据secure vip address获取所有实例
     * @param string $secureVipAddress
     * @return array|bool|\Exception|\Throwable
     */
    public function getInstancesBySecureVipAddress(string $secureVipAddress)
    {
        try {
            $response = $this->container->getHttpClient()->request('GET', $this->getEurekaServerUri() . '/svips/' . $secureVipAddress, [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);

            if ($response->getStatusCode() != 200) {
                throw new EurekaClientException("Query for all instances under a particular secure vip address failed.");
            }

            return json_decode($response->getBody(), true);

        } catch (\Exception|\Throwable $e) {
            $this->dump("获取{$secureVipAddress}下所有实例失败，错误码：{$e->getCode()}，错误信息：{$e->getMessage()}");
            return $e;
        }
    }


    /**
     * 获取某服务下所有实例
     * @param string $appId
     * @return array|mixed
     */
    public function fetchInstances(string $appId)
    {
        if (!empty($this->instances[$appId])) {
            return $this->instances[$appId];
        }

        $provider = $this->container->getInstanceProvider();

        $instances = $this->getApp($appId);
        if (is_array($instances) && isset($instances['application']['instance'])) {
            $this->instances[$appId] = $instances['application']['instance'];
        } else if (!empty($provider)) {
            return $provider->getInstances($appId);
        }

        return $this->instances[$appId] ?? [];
    }

    /**
     * 按照策略返回某服务下面的一个实例
     * @param string $appId
     * @return array
     */
    public function fetchInstance(string $appId)
    {
        $instances = $this->fetchInstances($appId);

        return $this->container->getDiscoveryStrategy()->getInstance($instances);
    }
}
