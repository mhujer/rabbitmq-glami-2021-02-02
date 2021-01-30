<?php declare(strict_types = 1);

use App\Product;
use App\RabbitUtils;
use App\Storage;
use PhpAmqpLib\Message\AMQPMessage;

require __DIR__ . '/vendor/autoload.php';

$channel = RabbitUtils::getRabbitChannel();

for ($i = 1; $i < 100; $i++) {
    $product = new Product($i, 'Product ' . md5((string) $i));

    $message = new AMQPMessage(json_encode($i % 5 == 0 ? '×pět' : $i), [ // neměnit
        'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
    ]);

    $channel->basic_publish($message, '', 'products');

    echo sprintf('Product %s sent for indexing', $i) . "\n";

    Storage::storeProduct($product);
}

