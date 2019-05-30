<?php declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest', '/');
$channel = $connection->channel();

$channel->queue_declare('emails', false, true, false, false);

for ($i = 0; $i < 10; $i++) {
    $message = new AMQPMessage(sprintf('It\'s %s here', date('Y-m-d H:i:s')));

    $channel->basic_publish($message, '', 'emails');

    echo 'Message sent' . "\n";
}

$channel->close();
$connection->close();
