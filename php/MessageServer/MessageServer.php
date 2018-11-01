<?php
	use Workerman\Worker;
	use Workerman\WebServer;
	use Workerman\Autoloader;
	use PHPSocketIO\SocketIO;
	
	require_once __DIR__ .'/../phpsocket.io/vendor/autoload.php';
	require_once __DIR__ .'/../doctrine2/bootstrap.php';
	require_once "MessageDispatcher.php";

	$io = new SocketIO(2021);
	
	$io->on('connection', function($socket) use($io, $entityManager) {
		$socket->addedUser = false;
	
		// 警情应答消息
		$socket->on("message", function($data) use($socket, $io, $entityManager) {
			if ( !array_key_exists('userName', $data) || !array_key_exists('cityId', $data) || !array_key_exists('msgType', $data) ) {
				return;
			}
			
			MessageDispatcher::getSingleton()->handle($data, $socket, $io, $entityManager);
		});
		
		
		// 当断开连接时
		$socket->on('disconnect', function () use($socket, $io, $entityManager) {
			if (!$socket->addedUser) {
				return;
			}
			
			MessageDispatcher::getSingleton()->handleDisconnect($socket, $io, $entityManager);
		});
	});
	
	if (!defined('GLOBAL_START')) {
		Worker::runAll();
	}
?>