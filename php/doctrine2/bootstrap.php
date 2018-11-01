<?php
	// bootstrap.php
	
	
	use Doctrine\ORM\Tools\Setup;
	use Doctrine\ORM\EntityManager;
	
	require_once "vendor/autoload.php";
	
	$isDevMode = true;
	$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/src"), $isDevMode);
	
	$conn = array(
		'driver' => 'pdo_mysql',
		'host' => 'localhost',
		'user' => 'root',
		'password' => '123456',
		'dbname' => 'panoPlatform'
	);
	
	$entityManager = EntityManager::create($conn, $config);
/*	
	use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Configuration;
use Doctrine\Common\Proxy\ProxyFactory;
require_once "doctrine2/vendor/autoload.php";

// ...
$applicationMode = "development";
if ($applicationMode == "development") {
    $cache = new \Doctrine\Common\Cache\ArrayCache;
} else {
    $cache = new \Doctrine\Common\Cache\ApcCache;
}

$config = new Configuration;
$config->setMetadataCacheImpl($cache);
$driverImpl = $config->newDefaultAnnotationDriver(__DIR__ . "/src");
$config->setMetadataDriverImpl($driverImpl);
$config->setQueryCacheImpl($cache);
$config->setProxyDir(__DIR__ . "/Proxies");
$config->setProxyNamespace('Project\Proxies');
$config->setAutoGenerateProxyClasses($applicationMode === 'development');


$connectionOptions = [
		'driver' => 'pdo_mysql',
		'host' => 'localhost',
		'user' => 'root',
		'password' => '',
		'dbname' => 'SmartFire'
];

$entityManager = EntityManager::create($connectionOptions, $config);*/