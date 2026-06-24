<?php
$router->add('GET', '/', 'App\Controllers\HomeController', 'index');
$router->add('GET', '/register', 'App\Controller\AuthController', 'showRegister');
$router->add('POST', '/register', 'App\Controllers\AuthController', 'register');
$router->add('GET', '/login', 'App\Controllers\AuthController', 'showLogin');
$router->add('POST', '/login', 'App\Controllers\AuthController', 'login');
$router->add('POST', '/logout', 'App\Controllers\AuthController', 'logout');