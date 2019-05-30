<?php declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest', '/');
$channel = $connection->channel();

$channel->exchange_declare('photos', 'direct', false, false, false);
$channel->queue_declare('photos_resize', false, false, false, false);
$channel->queue_declare('photos_compress', false, false, false, false);

$channel->queue_bind('photos_resize', 'photos', 'photos_resize');
$channel->queue_bind('photos_compress', 'photos', 'photos_compress');

$channel->close();
$connection->close();
