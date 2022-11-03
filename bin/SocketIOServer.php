<?php

namespace SpseiMarketplace\Core;

// This has to be required because this is fired via CMD :D
// Vendor autoloading classes
require '../vendor/autoload.php';
require '../config.php';

use Workerman\Worker;
use PHPSocketIO\SocketIO;
use Emitter;

class SocketIOServer
{
	private $port;
	private $socket_io;

	public function __construct($port)
	{
		$this->port = $port;
		$this->socket_io = new SocketIO($port);
	}

	public function run()
	{
		$io = $this->socket_io;

		$io->on('workerStart', function () use ($io) {
			$io->adapter('\PHPSocketIO\ChannelAdapter');
		});
		$io->on('connection', function ($socket) use ($io) {
			echo "new connection\n";
			// Broadcast all information about new client to all connections (for debugging)
			$io->emit('broadcast', [
				'id' => $socket->id,
				'rooms' => $socket->rooms,
				'request' => $socket->request,
				'handshake' => $socket->handshake
			]);
		});

		Worker::runAll();
	}
}

// When this file is executed from CMD with php command -> start socket server
$server = new SocketIOServer(WEBSOCKETS_PORT);
$server->run();