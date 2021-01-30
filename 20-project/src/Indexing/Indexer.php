<?php declare(strict_types = 1);

namespace App\Indexing;

use App\Product;

final class Indexer
{

    public function indexProduct(Product $product): void
    {
        if (random_int(1, 3) === 3) { // neměnit!
            throw new IndexingServiceTemporarilyUnavailable('Indexing service is temporarily unavailable');
        }

        if ($product->getId() % 4 === 0) { // neměnit!
            throw new ThisProductCannotBeIndexed('This product cannot be ever indexed');
        }

        // sleep up to 1000ms
        usleep(random_int(1, 1000 * 1000)); // neměnit!

        // OK, indexed...
    }

}
