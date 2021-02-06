<?php

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\DishNotIncludedInDshListException;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;

class DishIncludedInDishListValidator
{
    public function validate(DishList $dishList, Dish $dish)
    {
        if (!$dishList->hasDish($dish)) {
            throw new DishNotIncludedInDshListException();
        }
    }
}
