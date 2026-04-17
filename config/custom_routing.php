<?php
$routes = [
    'login' => 'members/login',
    'register' => 'members/register',
    'tg-admin' => 'trongate_administrators/login',
    'tg-admin/submit_login' => 'trongate_administrators/submit_login'
];
define('CUSTOM_ROUTES', $routes);