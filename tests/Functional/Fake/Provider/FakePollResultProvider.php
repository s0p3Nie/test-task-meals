<?php

namespace tests\Meals\Functional\Fake\Provider;

use Assert\Assertion;
use Meals\Application\Component\Provider\PollResultProviderInterface;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;

class FakePollResultProvider implements PollResultProviderInterface
{
    /** @var PollResult[] */
    private $pollResultList = [];

    public function createNewPollResult(Poll $poll, Employee $employee, Dish $dish): PollResult
    {
        return new PollResult(1, $poll, $employee, $dish, $employee->getFloor());
    }

    public function getActivePollResults(): array
    {
        return $this->pollResultList;
    }

    /**
     * @param PollResult[] $pollResultList
     */
    public function setPollResultList(array $pollResultList): void
    {
        Assertion::allIsInstanceOf($pollResultList,PollResult::class);
        $this->pollResultList = $pollResultList;
    }
}
