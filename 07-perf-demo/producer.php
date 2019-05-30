<?php declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest', '/');
$channel = $connection->channel();

$channel->queue_declare('perfdemo', false, true, false, false);

for ($i = 0; $i < 100000; $i++) {
    $msg = new AMQPMessage(
        (string) $i,
        ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
    );
    $channel->basic_publish($msg, '', 'perfdemo');
    if ($i % 5000 === 0) {
        echo date('Y-m-d H:i:s') . ": Sent 'Hello World ($i)!'\n";
    }
}

$channel->close();
$connection->close();
