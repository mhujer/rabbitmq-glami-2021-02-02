<?php declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest', '/');
$channel = $connection->channel();

$channel->queue_declare('perfdemo', false, true, false, false);
$channel->basic_qos(null, 100, null);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$channel->basic_consume('perfdemo', '', false, false, false, false, function (AMQPMessage $message) {
    $i = (int) $message->getBody();

    if ($i % 1000 === 0) {
        echo date('Y-m-d H:i:s') . ' [x] Received ' . $i . "\n";
    }
    $message->ack();
});

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();
