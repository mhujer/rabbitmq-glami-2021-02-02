<?php declare(strict_types = 1, ticks = 1);

function shutdown()
{
    echo "Shutting down!\n";
    exit;
}

pcntl_signal(SIGTERM, 'shutdown');
pcntl_signal(SIGINT, 'shutdown');

while (true) {
    sleep(1);
    echo '.';
}
