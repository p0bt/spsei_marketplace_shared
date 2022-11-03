<?php

namespace SpseiMarketplace\Core;

// This has to be required because this is fired via CMD :D
// Vendor autoloading classes
require '../vendor/autoload.php';
require '../config.php';

use Channel\Server;
use Workerman\Worker;

class ChannelServer
{
	private $ip;
	private $server;

	public function __construct($ip)
	{
		$this->server = new Server($ip);
	}

	public function run()
	{
		Worker::runAll();
	}
}

// When this file is executed from CMD with php command -> start socket server
$server = new ChannelServer(SITE_IP);
$server->run();