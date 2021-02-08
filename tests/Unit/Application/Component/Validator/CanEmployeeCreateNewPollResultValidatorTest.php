<?php

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\CanEmployeeCreateNewPollResultValidator;
use Meals\Application\Component\Validator\Exception\EmployeeHasAlreadyMadePollResultException;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\PollResult;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class CanEmployeeCreateNewPollResultValidatorTest extends TestCase
{
    use ProphecyTrait;

    public function testSuccessful()
    {
        $employee = $this->prophesize(Employee::class);
        $validationEmployee = $this->prophesize(Employee::class);

        $employee->getId()->willReturn(1);
        $validationEmployee->getId()->willReturn(2);

        $pollResult = $this->prophesize(PollResult::class);
        $pollResult->getEmployee()->willReturn($employee->reveal());

        $validator = new CanEmployeeCreateNewPollResultValidator();
        verify($validator->validate([$pollResult->reveal()], $validationEmployee->reveal()))->null();
    }

    public function testFail()
    {
        $this->expectException(EmployeeHasAlreadyMadePollResultException::class);

        $employee = $this->prophesize(Employee::class);
        $employee->getId()->willReturn(1);

        $pollResult = $this->prophesize(PollResult::class);
        $pollResult->getEmployee()->willReturn($employee->reveal());

        $validator = new CanEmployeeCreateNewPollResultValidator();
        $validator->validate([$pollResult->reveal()], $employee->reveal());
    }
}
