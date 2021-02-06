<?php

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\DishIncludedInDishListValidator;
use Meals\Application\Component\Validator\Exception\DishNotIncludedInDshListException;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class DishIncludedInDishListValidatorTest extends TestCase
{
    use ProphecyTrait;

    public function testSuccessful()
    {
        /** @var Dish|ObjectProphecy $dish */
        $dish = $this->prophesize(Dish::class);

        /** @var DishList|ObjectProphecy $dishList */
        $dishList = $this->prophesize(DishList::class);
        $dishList->hasDish($dish->reveal())->willReturn(true);

        $validator = new DishIncludedInDishListValidator();
        verify($validator->validate($dishList->reveal(), $dish->reveal()))->null();
    }

    public function testFail()
    {
        $this->expectException(DishNotIncludedInDshListException::class);

        /** @var Dish|ObjectProphecy $dish */
        $dish = $this->prophesize(Dish::class);

        /** @var DishList|ObjectProphecy $dishList */
        $dishList = $this->prophesize(DishList::class);
        $dishList->hasDish($dish->reveal())->willReturn(false);

        $validator = new DishIncludedInDishListValidator();
        $validator->validate($dishList->reveal(), $dish->reveal());
    }
}
