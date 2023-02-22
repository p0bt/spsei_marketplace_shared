<?php

namespace SpseiMarketplace\Core;

// This has to be required because this is fired via CMD :D
// Vendor autoloading classes
require realpath('../vendor/autoload.php');
require realpath('../config.php');

use Channel\Server;
use Workerman\Worker;

class ChannelServer
{
	private $ip;
	private $server;

	public function __construct($ip)
	{
		$this->ip = $ip;
		$this->server = new Server($this->ip);
	}

	public function run()
	{
		Worker::runAll();
	}
}

// When this file is executed from CMD with php command -> start socket server
$server = new ChannelServer(SITE_IP);
$server->run();