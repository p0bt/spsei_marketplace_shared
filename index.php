<?php
require_once("init.php");
require_once("config.php");

$router = new Router();
$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

if (Filter::is_banned()) {
  $router->route("ip-adresa-zablokovana");
  die;
}

$router->filter($url);
$router->route($url);