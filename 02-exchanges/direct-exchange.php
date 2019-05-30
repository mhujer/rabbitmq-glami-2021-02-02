<?php declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare('mailing', 'direct', false, false, false);
$channel->queue_declare('emails', false, false, false, false);

$channel->queue_bind('emails', 'mailing', '');

$message = new AMQPMessage('Hello!');
$channel->basic_publish($message, 'mailing', '');

echo 'Message sent' . "\n";

$channel->close();
$connection->close();
