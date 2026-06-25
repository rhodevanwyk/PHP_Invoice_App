<?php
$router->add('GET',  '/',                  'App\Controllers\AuthController', 'showLogin');
$router->add('GET',  '/register',          'App\Controllers\AuthController', 'showRegister');
$router->add('POST', '/register',          'App\Controllers\AuthController', 'register');
$router->add('GET',  '/login',             'App\Controllers\AuthController', 'showLogin');
$router->add('POST', '/login',             'App\Controllers\AuthController', 'login');
$router->add('POST', '/logout',            'App\Controllers\AuthController', 'logout');
$router->add('GET',  '/forgot-password',   'App\Controllers\AuthController', 'showForgotPassword');
$router->add('POST', '/forgot-password',   'App\Controllers\AuthController', 'sendResetLink');
$router->add('GET',  '/reset-password',    'App\Controllers\AuthController', 'showResetForm');
$router->add('POST', '/reset-password',    'App\Controllers\AuthController', 'resetPassword');
$router->add('GET',  '/dashboard',         'App\Controllers\DashboardController', 'index');