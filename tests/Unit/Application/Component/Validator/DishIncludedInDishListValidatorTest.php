<?php

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\DishIncludedInDishListValidator;
use Meals\Application\Component\Validator\Exception\DishNotIncludedInDshListException;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class DishIncludedInDishListValidatorTest extends TestCase
{
    use ProphecyTrait;

    public function testSuccessful()
    {
        $dish = $this->prophesize(Dish::class);
        $dishList = $this->prophesize(DishList::class);

        $dishList->hasDish($dish->reveal())->willReturn(true);

        $validator = new DishIncludedInDishListValidator();
        verify($validator->validate($dishList->reveal(), $dish->reveal()))->null();
    }

    public function testFail()
    {
        $this->expectException(DishNotIncludedInDshListException::class);

        $dish = $this->prophesize(Dish::class);
        $dishList = $this->prophesize(DishList::class);

        $dishList->hasDish($dish->reveal())->willReturn(false);

        $validator = new DishIncludedInDishListValidator();
        $validator->validate($dishList->reveal(), $dish->reveal());
    }
}
