<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$config = require __DIR__ . '/../config/app.php';

if ($config['app_env'] === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

session_start();

define('BASE_PATH', str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])));
require __DIR__ . '/../src/Core/helpers.php';

$db = new App\Core\Database($config['db']);
$auth = new App\Core\Auth($db);
$mailService = new App\Services\MailService($config['mail']);

$router = new App\Core\Router();

require __DIR__ . '/../src/routes.php';

$services = [
    'db' => $db,
    'auth' => $auth,
    'mail' => $mailService,
];

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $services);