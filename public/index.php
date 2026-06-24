<?php
declare(strict_types=1);

// LOAD COMPOSER AUTOLOADER
require __DIR__ . '/../vendor/autoload.php';

// LOAD ENVIROMENT
// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
// $dotenv->load();

if (class_exists('Dotenv\Dotenv')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
} else {
    echo 'ERROR: Dotenv class not found.<br>';
}

// LOAD APP CONFIGURATION
$config = require __DIR__ . '/../config/app.php';

// ERROR HANDLING BASED ON THE ENVIROMENT
if ($config['app_env'] === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// SESSION START
session_start();

// INSTANTIATE CORE OBJECTS
$db = new \App\Core\Database($config['db']);
$auth = new \App\Core\Auth($db);
$router = new \App\Core\Router();

// LOAD ROUTES
require __DIR__ . '/../src/routes.php';

// DISPATCH
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
