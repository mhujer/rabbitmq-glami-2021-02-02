<?php declare(strict_types = 1);

namespace App;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class RabbitUtils
{

    public static function getRabbitChannel(): \PhpAmqpLib\Channel\AMQPChannel
    {
        static $channel;

        if ($channel === null) {
            $connection = new AMQPStreamConnection('rabbitmq', 5672, 'guest', 'guest');
            $channel = $connection->channel();
        }

        return $channel;
    }
}
