<?php declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare('images', AMQPExchangeType::DIRECT, false, false, false);
$channel->queue_declare('images_resize', false, false, false, false);
$channel->queue_declare('images_compress', false, false, false, false);

$channel->queue_bind('images_resize', 'images', 'images_resize');
$channel->queue_bind('images_compress', 'images', 'images_compress');

$message = new AMQPMessage('big-photo.jpg');
$channel->basic_publish($message, 'images', 'images_resize');
echo 'Message sent' . "\n";

$message = new AMQPMessage('my-scan.bmp');
$channel->basic_publish($message, 'images', 'images_compress');
echo 'Message sent' . "\n";

$message = new AMQPMessage('other.png');
$channel->basic_publish($message, 'images', '');
echo 'Message sent' . "\n";

$channel->close();
$connection->close();
