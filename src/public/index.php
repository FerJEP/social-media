<?php


require_once __DIR__ . '/../../vendor/autoload.php';

use app\Database as DB;
use app\Controller;
use app\Router;

session_start();

$pathToPublic = __DIR__;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

DB::connect(
    $_ENV['DB_HOST'],
    $_ENV['DB_NAME'],
    $_ENV['DB_USERNAME'],
    $_ENV['DB_PASSWORD']
);

Router::get('/', [Controller::class, 'home']);

Router::get('/user/:username', [Controller::class, 'user']);

Router::get('/settings', [Controller::class, 'settings']);
Router::post('/settings', [Controller::class, 'settings']);

Router::get('/login', [Controller::class, 'login']);
Router::post('/login', [Controller::class, 'login']);

Router::get('/signup', [Controller::class, 'signup']);
Router::post('/signup', [Controller::class, 'signup']);

Router::post('/create_post', [Controller::class, 'create_post']);

Router::get('/logout', [Controller::class, 'logout']);

Router::resolve();
