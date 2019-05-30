<?php declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('emails', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$channel->basic_consume('emails', '', false, true, false, false, function (AMQPMessage $message) {
    $data = $message->getBody();
    var_dump($data);

    /*
    $email = json_decode($data, true);
    echo sprintf('Sending e-mail "%s" to "%s" with text: %s',
        $email['subject'],
        $email['to'],
        $email['body']
    );
    */
});

while (count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();
