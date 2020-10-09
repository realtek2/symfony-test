<?php

namespace App\DataFixtures\Provider;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * CollectionProvider.
 */
final class CollectionProvider
{
    public static function collection(array $objects): ArrayCollection
    {
        return new ArrayCollection($objects);
    }
}
