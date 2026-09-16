<?php

declare(strict_types=1);

namespace MaxServ\App\Service;

final class ProductPriceCalculator
{
    public function calculateDiscountPrice(
        float $price,
        float $discountPercentage
    ): float {
        return round(
            $price * (1 - ($discountPercentage / 100)),
            2
        );
    }
}
