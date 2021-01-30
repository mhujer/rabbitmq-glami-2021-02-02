<?php declare(strict_types = 1);

use App\Indexing\Indexer;
use App\RabbitUtils;
use App\Storage;
use PhpAmqpLib\Message\AMQPMessage;

require __DIR__ . '/vendor/autoload.php';

$indexer = new Indexer();

$channel = RabbitUtils::getRabbitChannel();
echo " [*] Waiting for messages. To exit press CTRL+C\n";
$channel->basic_consume('products', '', false, false, false, false, function (AMQPMessage $message) use ($indexer) {
    echo '.';
    //$productId = $message->getBody();

    //$product = Storage::loadProduct($productId);
    //echo sprintf('Indexing product %s', $product->getId()) . "\n";

    //$indexer->indexProduct($product);
});

while (count($channel->callbacks)) {
    $channel->wait();
}
