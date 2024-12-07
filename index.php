<?php
require './vendor/autoload.php';
require_once './app/utils/Autoload.php';

// Register autoloader first
Autoload::register();

// Then start session
session_start();

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router();
$router->dispatch($_SERVER['REQUEST_URI']);
