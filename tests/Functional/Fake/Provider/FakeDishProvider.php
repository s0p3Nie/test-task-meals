<?php

namespace tests\Meals\Functional\Fake\Provider;

use Meals\Application\Component\Provider\DishProviderInterface;
use Meals\Domain\Dish\Dish;

class FakeDishProvider implements DishProviderInterface
{
    /** @var Dish */
    private $dish;

    public function getDish(int $id): Dish
    {
        return $this->dish;
    }

    /**
     * @param Dish $dish
     */
    public function setDish(Dish $dish): void
    {
        $this->dish = $dish;
    }
}
