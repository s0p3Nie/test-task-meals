<?php


namespace Meals\Application\Component\Validator;

use Assert\Assertion;
use Meals\Application\Component\Validator\Exception\EmployeeHasAlreadyMadePollResultException;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\PollResult;

class CanEmployeeCreateNewPollResultValidator
{
    /**
     * @param PollResult[] $pollResultList
     * @param Employee     $employee
     */
    public function validate(array $pollResultList, Employee $employee)
    {
        Assertion::allIsInstanceOf($pollResultList, PollResult::class);
        foreach ($pollResultList as $pollResult) {
            if ($employee->getId() === $pollResult->getEmployee()->getId()) {
                throw new EmployeeHasAlreadyMadePollResultException();
            }
        }
    }
}
