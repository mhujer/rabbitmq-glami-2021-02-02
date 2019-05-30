<?php declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest', '/');
$channel = $connection->channel();

// DLX setup
$channel->exchange_declare('emails-dlx', AMQPExchangeType::FANOUT, false, true, false);
$channel->queue_declare('emails-dlx', false, true, false, false);
$channel->queue_bind('emails-dlx', 'emails-dlx');

// send dead messages from "emails" queue to "emails-dlx" exchange
$channel->queue_declare('emails', false, true, false, false, false, new AMQPTable([
    'x-dead-letter-exchange' => 'emails-dlx',
]));

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
