<?php declare(strict_types = 1);

require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare('order_completed', 'fanout', false, false, false);
$channel->queue_declare('order_email_notification', false, false, false, false);
$channel->queue_declare('order_sms_notification', false, false, false, false);
$channel->queue_declare('order_stock_recalculation', false, false, false, false);

$channel->queue_bind('order_email_notification', 'order_completed');
$channel->queue_bind('order_sms_notification', 'order_completed');
$channel->queue_bind('order_stock_recalculation', 'order_completed');

$message = new AMQPMessage(json_encode([
    'type' => 'order',
    'id' => random_int(1, 100),
]));
$channel->basic_publish($message, 'order_completed');
echo 'Order submitted' . "\n";

$channel->close();
$connection->close();
