<?php

namespace tests\Meals\Functional\Interactor;

use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Application\Component\Validator\Exception\DateTimeIsNotAMondayException;
use Meals\Application\Component\Validator\Exception\DateTimeIsNotWorkTimeException;
use Meals\Application\Component\Validator\Exception\DishNotIncludedInDshListException;
use Meals\Application\Component\Validator\Exception\EmployeeHasAlreadyMadePollResultException;
use Meals\Application\Component\Validator\Exception\PollIsNotActiveException;
use Meals\Application\Feature\Poll\UseCase\EmployeeSaveActivePoll\Interactor;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Menu\Menu;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;
use Meals\Domain\User\Permission\Permission;
use Meals\Domain\User\Permission\PermissionList;
use Meals\Domain\User\User;
use tests\Meals\Functional\Fake\Provider\FakeDishProvider;
use tests\Meals\Functional\Fake\Provider\FakeEmployeeProvider;
use tests\Meals\Functional\Fake\Provider\FakePollProvider;
use tests\Meals\Functional\Fake\Provider\FakePollResultProvider;
use tests\Meals\Functional\FunctionalTestCase;

class EmployeeSaveActivePollTest extends FunctionalTestCase
{
    public function testSuccessful()
    {
        $pollResult = $this->performTestMethod(
            self::getEmployeeWithPermissions(),
            self::getPoll(),
            self::getDishIncludedInMenu(),
            self::getEmptyPollResultList(),
            self::getCorrectMonday()
        );
        verify($pollResult)->equals($pollResult);
    }

    public function testUserDoesNotHavePermissions()
    {
        $this->expectException(AccessDeniedException::class);

        $pollResult = $this->performTestMethod(
            self::getEmployeeWithNoPermissions(),
            self::getPoll(),
            self::getDishIncludedInMenu(),
            self::getEmptyPollResultList(),
            self::getCorrectMonday()
        );
        verify($pollResult)->equals($pollResult);
    }

    public function testPollIsNotActive()
    {
        $this->expectException(PollIsNotActiveException::class);

        $pollResult = $this->performTestMethod(
            self::getEmployeeWithPermissions(),
            self::getInactivePoll(),
            self::getDishIncludedInMenu(),
            self::getEmptyPollResultList(),
            self::getCorrectMonday()
        );
        verify($pollResult)->equals($pollResult);
    }

    public function testBlockedDoeNotMondayConstraint()
    {
        $this->expectException(DateTimeIsNotAMondayException::class);

        $pollResult = $this->performTestMethod(
            self::getEmployeeWithPermissions(),
            self::getPoll(),
            self::getDishIncludedInMenu(),
            self::getEmptyPollResultList(),
            self::getCorrectFriday()
        );

        verify($pollResult)->equals($pollResult);
    }

    public function testBlockedDueToTimeConstraints()
    {
        $this->expectException(DateTimeIsNotWorkTimeException::class);

        $pollResult = $this->performTestMethod(
            self::getEmployeeWithPermissions(),
            self::getPoll(),
            self::getDishIncludedInMenu(),
            self::getEmptyPollResultList(),
            self::getBadMonday()
        );

        verify($pollResult)->equals($pollResult);
    }

    public function testEmployeeHasAlreadyCreatedPollResult()
    {
        $this->expectException(EmployeeHasAlreadyMadePollResultException::class);

        $pollResult = $this->performTestMethod(
            self::getEmployeeWithPermissions(),
            self::getPoll(),
            self::getDishIncludedInMenu(),
            self::getPollResultWithPoll(),
            self::getCorrectMonday()
        );

        verify($pollResult)->equals($pollResult);
    }

    public function testDishNotInPollMenu()
    {
        $this->expectException(DishNotIncludedInDshListException::class);

        $pollResult = $this->performTestMethod(
            self::getEmployeeWithPermissions(),
            self::getPoll(),
            self::getDishNotIncludedInMenu(),
            self::getEmptyPollResultList(),
            self::getCorrectMonday()
        );

        verify($pollResult)->equals($pollResult);
    }

    /**
     * @param Employee $employee
     * @param Poll $poll
     * @param Dish $dish
     * @param PollResult[] $pollResultList
     * @param \DateTimeInterface $timeOfRequest
     *
     * @return PollResult
     *
     * @throws \Exception
     */
    private function performTestMethod(
        Employee $employee,
        Poll $poll,
        Dish $dish,
        array $pollResultList,
        \DateTimeInterface $timeOfRequest
    ): PollResult {
        $this->getContainer()->get(FakeEmployeeProvider::class)->setEmployee($employee);
        $this->getContainer()->get(FakePollProvider::class)->setPoll($poll);
        $this->getContainer()->get(FakeDishProvider::class)->setDish($dish);
        $this->getContainer()->get(FakePollResultProvider::class)->setPollResultList($pollResultList);

        /** @var Interactor $interactor */
        $interactor = $this->getContainer()->get(Interactor::class);
        $dateTimeConstraint = $interactor->getDateTimeValidatorCollection();
        $dateTimeConstraint->setDateTimeToValidate($timeOfRequest);
        $interactor->setDateTimeValidatorCollection($dateTimeConstraint);

        return $interactor->saveActivePoll($employee->getId(), $poll->getId(), $dish->getId());
    }

    private static function getCorrectMonday(): \DateTimeImmutable
    {
        return (new \DateTimeImmutable('first monday'))->setTime(9, 0, 0);
    }

    private static function getBadMonday(): \DateTimeImmutable
    {
        return (new \DateTimeImmutable('first monday'))->setTime(5, 0, 0);
    }

    private static function getCorrectFriday(): \DateTimeImmutable
    {
        return (new \DateTimeImmutable('first friday'))->setTime(9, 0, 0);
    }

    private static function getEmployeeWithPermissions(): Employee
    {
        return new Employee(1, self::getUserWithPermissions(), 4, 'Surname');
    }

    private static function getEmployeeWithNoPermissions(): Employee
    {
        return new Employee(1, self::getUserWithNoPermissions(), 4, 'Surname');
    }

    private static function getPollResultWithPoll(): array
    {
        return [new PollResult(10, self::getPoll(), self::getEmployeeWithPermissions(), self::getDishIncludedInMenu(), 4)];
    }

    private static function getEmptyPollResultList(): array
    {
        return [];
    }

    private static function getDishIncludedInMenu(): Dish
    {
        return new Dish(1, 'Awesome Cutlets', 'Cutlets with mashed potatoes');
    }

    private static function getDishNotIncludedInMenu(): Dish
    {
        return new Dish(2, 'Awful Cutlets', 'Cutlets with pasta');
    }


    private static function getUserWithPermissions(): User
    {
        return new User( 1, new PermissionList([new Permission(Permission::PARTICIPATION_IN_POLLS)]));
    }

    private static function getUserWithNoPermissions(): User
    {
        return new User(1, new PermissionList([]));
    }

    private static function getPoll(): Poll
    {
        return new Poll(1, true, new Menu(1, 'title', new DishList([self::getDishIncludedInMenu()])));
    }

    private static function getInactivePoll(): Poll
    {
        return new Poll(1, false, new Menu(1, 'title', new DishList([self::getDishIncludedInMenu()])));
    }
}
