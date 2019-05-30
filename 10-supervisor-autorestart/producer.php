<?php declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest', '/');
$channel = $connection->channel();

$channel->queue_declare('emails', false, false, false, false);

$email = [
    'subject' => 'Hello RabbitMQ',
    'to' => 'foobar@example.org',
    'body' => sprintf('Hello Rabbit, how are you? It\'s %s here', date('Y-m-d H:i:s')),
];

$message = new AMQPMessage(json_encode($email));

$channel->basic_publish($message, '', 'emails');

echo 'Message sent' . "\n";

$channel->close();
$connection->close();
