<?php declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest', '/');
$channel = $connection->channel();

$channel->queue_declare('photos', false, true, false, false);

$i = 0;
while (true) {
    $msg = new AMQPMessage(
        json_encode([
            'message' => sprintf(
                'Hello World! %s (%s)',
                $i,
                date('Y-m-d H:i:s')
            ),
            'payload' => str_repeat('a', 1024 * 1024 * 5), // 5MB
            //'payload' => str_repeat('a', 1024 * 1024 * 50), // 50MB
        ]), ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]);
    $channel->basic_publish($msg, '', 'photos');
    echo date('Y-m-d H:i:s') . ": Sent 'Hello World ($i)!'\n";
    $i++;
    if ($i >= 80) break;
    //if ($i >= 8) break;
}

$channel->close();
$connection->close();
