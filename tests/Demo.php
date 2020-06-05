<?php

use EurekaClient\EurekaClient;
use EurekaClient\Models\DataCenterInfo;
use EurekaClient\Models\Instance;

require_once __DIR__ . '/../vendor/autoload.php';

$appId = 'my_app';
$instanceId = 'app_instance_id';

$eurekaClient = new EurekaClient('localhost', 8080);

$dataCenterInfo = new DataCenterInfo();

$instance = new Instance();
$instance->setApp($appId)
    ->setInstanceId($instanceId)
    ->setHostName('hostName')
    ->setIpAddr('127.0.0.1')
    ->setPort(80)
    ->setHomePageUrl('http://localhost')
    ->setStatusPageUrl('http://localhost/status')
    ->setHealthCheckUrl('http://localhost/health')
    ->setDataCenterInfo($dataCenterInfo);

$response = $eurekaClient->register($appId, $instance);

$instance = $eurekaClient->fetchInstance($appId);
var_dump($instance);
