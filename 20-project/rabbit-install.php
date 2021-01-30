<?php declare(strict_types = 1);

use App\RabbitUtils;

require __DIR__ . '/vendor/autoload.php';

$channel = RabbitUtils::getRabbitChannel();

// jde spouštět pořád dokola, ale failne, pokud by v Rabbitovi bylo nastavené jinak
$channel->queue_declare('products', false, true, false, false);
echo 'Declared queue "products"' . "\n";
