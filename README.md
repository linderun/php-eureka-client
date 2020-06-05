PHP Netflix Eureka Client
=========================
A PHP client for Spring Cloud Netflix Eureka service registration and discovery.


## Installation
Run
```
composer require darren/php-eureka-client
```
or add dependency to your composer.json file
```
"require": {
    ...
    "darren/php-eureka-client": "^1.0"
}

```
## Usage example
### 1. Use needed packages
```php
use EurekaClient\EurekaClient;
use EurekaClient\Models\Instance;
use EurekaClient\Models\Metadata;
use EurekaClient\Models\DataCenterInfo;
use GuzzleHttp\Client;
```
### 2. Create Eureka app instance
```php
// We will use app name and instance id for making requests below.
$appId = 'my_app';
$instanceId = 'app_instance_id';

// Create app instance metadata.
$metadata = new Metadata();
$metadata->set('instanceKey', 'instanceValue');

// Create data center metadata.
$dataCenterMetadata = new Metadata();
$dataCenterMetadata->set('dataCenterKey', 'dataCenterValue');

// Create data center info (Amazon example).
$dataCenterInfo = new DataCenterInfo();
$dataCenterInfo
  ->setName('Amazon')
  ->setClass('com.netflix.appinfo.AmazonInfo')
  ->setMetadata($dataCenterMetadata);

// Create Eureka app instance.
$instance = new Instance();
$instance
  ->setInstanceId($instanceId)
  ->setHostName('hostName')
  ->setApp($appName)
  ->setIpAddr('127.0.0.1')
  ->setPort(80)
  ->setSecurePort(433)
  ->setHomePageUrl('http://localhost')
  ->setStatusPageUrl('http://localhost/status')
  ->setHealthCheckUrl('http://localhost/health')
  ->setSecureHealthCheckUrl('https://localhost/health')
  ->setVipAddress('vipAddress')
  ->setSecureVipAddress('secureVipAddress')
  ->setMetadata($metadata)
  ->setDataCenterInfo($dataCenterInfo);
```
### 3. Create Eureka client
```php
// Create eureka client.
$eurekaClient = new EurekaClient('localhost', 8080);
```
### 4. Make requests
```php
  // Register new application instance.
  $response = $eurekaClient->register($appId, $instance);

  // De-register application instance.
  $response = $eurekaClient->deRegister($appId, $instanceId);

  // Query for all instances.
  $allApps = $eurekaClient->getAllApps();

  // Query for all appID instances.
  $app = $eurekaClient->getApp($appId);

  // Query for a specific appID/instanceID.
  $appInstance = $eurekaClient->getAppInstance($appId, $instanceId);

  // Query for a specific instanceID.
  $instance = $eurekaClient->getInstance($instanceId);

  // Send application instance heartbeat.
  $response = $eurekaClient->heartBeat($appId, $instanceId);

  // Take instance out of service.
  $response = $eurekaClient->takeInstanceOut($appId, $instanceId);

  // Move instance back into service (remove override).
  $response = $eurekaClient->putInstanceBack($appId, $instanceId);

  // Update metadata.
  $response = $eurekaClient->updateAppInstanceMetadata($appId, $instanceId, $metadata);

  // Query for all instances under a particular vip address.
  $instances = $eurekaClient->getInstancesByVipAddress('vipAddress');

  // Query for all instances under a particular secure vip address.
  $instances = $eurekaClient->getInstancesBySecureVipAddress('secureVipAddress');
```
