<?php
require_once("config.php");
require_once("init.php");

use SpseiMarketplace\Core\Router;
use SpseiMarketplace\Core\Filter;

$router = new Router();
$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

if (Filter::is_banned()) {
  $router->route("ip-adresa-zablokovana");
  die;
}

$router->filter($url);
$router->route($url);