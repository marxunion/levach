<?php 
require __DIR__ . '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

return 
[
	'displayErrorDetails' => false,
    'db' => [
        'host' => 'localhost',
        'user' => $_ENV['POSTGRES_USER'],
        'pass' => $_ENV['POSTGRES_PASSWORD'],
        'dbname' => 'levach'
    ]
];