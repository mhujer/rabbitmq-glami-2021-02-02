<?php declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest', '/');
$channel = $connection->channel();

$channel->queue_declare('emails', false, true, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$consumedMessagesCount = 0;

$channel->basic_consume('emails', '', false, false, false, false, function (AMQPMessage $message) use (&$consumedMessagesCount) {
    $data = $message->getBody();
    var_dump($data);
    $message->ack();

    $consumedMessagesCount++;
});

while (count($channel->callbacks)) {
    if ($consumedMessagesCount >= 5) {
        echo 'Consumed 5 messages, stopping...' . "\n";
        sleep(2);
        break;
    }
    $channel->wait();
}

$channel->close();
$connection->close();
