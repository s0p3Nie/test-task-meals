<?php

namespace Meals\Application\Feature\Poll\UseCase\EmployeeSaveActivePoll;

use Meals\Application\Component\Provider\DishProviderInterface;
use Meals\Application\Component\Provider\EmployeeProviderInterface;
use Meals\Application\Component\Provider\PollProviderInterface;
use Meals\Application\Component\Provider\PollResultProviderInterface;
use Meals\Application\Component\Validator\CanEmployeeCreateNewPollResultValidator;
use Meals\Application\Component\Validator\DateTimeValidatorCollection;
use Meals\Application\Component\Validator\DishIncludedInDishListValidator;
use Meals\Application\Component\Validator\PollIsActiveValidator;
use Meals\Application\Component\Validator\UserHasAccessToParticipatePollsValidator;
use Meals\Domain\Poll\PollResult;

class Interactor
{
    // providers
    /** @var DishProviderInterface */
    private $dishProvider;

    /** @var EmployeeProviderInterface */
    private $employeeProvider;

    /** @var PollProviderInterface */
    private $pollProvider;

    /** @var PollResultProviderInterface */
    private $pollResultProvider;

    // validators
    /** @var CanEmployeeCreateNewPollResultValidator */
    private $canEmployeeCreateNewPollResultValidator;

    /** @var DateTimeValidatorCollection */
    private $dateTimeValidatorCollection;

    /** @var DishIncludedInDishListValidator */
    private $dishInDishListValidator;

    /** @var UserHasAccessToParticipatePollsValidator */
    private $userHasAccessToParticipatePollsValidator;

    /** @var PollIsActiveValidator */
    private $pollIsActiveValidator;

    /**
     * Interactor constructor.
     *
     * @param DishProviderInterface $dishProvider
     * @param EmployeeProviderInterface $employeeProvider
     * @param PollProviderInterface $pollProvider
     * @param PollResultProviderInterface $pollResultProvider
     * @param CanEmployeeCreateNewPollResultValidator $canEmployeeCreateNewPollResultValidator
     * @param DateTimeValidatorCollection $dateTimeValidatorCollection
     * @param DishIncludedInDishListValidator $dishInDishListValidator
     * @param UserHasAccessToParticipatePollsValidator $userHasAccessToParticipatePollsValidator
     * @param PollIsActiveValidator $pollIsActiveValidator
     */
    public function __construct(
        DishProviderInterface $dishProvider,
        EmployeeProviderInterface $employeeProvider,
        PollProviderInterface $pollProvider,
        PollResultProviderInterface $pollResultProvider,
        CanEmployeeCreateNewPollResultValidator $canEmployeeCreateNewPollResultValidator,
        DateTimeValidatorCollection $dateTimeValidatorCollection,
        DishIncludedInDishListValidator $dishInDishListValidator,
        UserHasAccessToParticipatePollsValidator $userHasAccessToParticipatePollsValidator,
        PollIsActiveValidator $pollIsActiveValidator
    )
    {
        $this->dishProvider = $dishProvider;
        $this->employeeProvider = $employeeProvider;
        $this->pollProvider = $pollProvider;
        $this->pollResultProvider = $pollResultProvider;
        $this->canEmployeeCreateNewPollResultValidator = $canEmployeeCreateNewPollResultValidator;
        $this->dateTimeValidatorCollection = $dateTimeValidatorCollection;
        $this->dishInDishListValidator = $dishInDishListValidator;
        $this->userHasAccessToParticipatePollsValidator = $userHasAccessToParticipatePollsValidator;
        $this->pollIsActiveValidator = $pollIsActiveValidator;
    }

    public function saveActivePoll(int $employeeId, int $pollId, int $dishId): PollResult
    {
        $employee = $this->employeeProvider->getEmployee($employeeId);
        $poll = $this->pollProvider->getPoll($pollId);
        $dish = $this->dishProvider->getDish($dishId);
        $activePollResults = $this->pollResultProvider->getActivePollResults();

        $this->dateTimeValidatorCollection->validate();
        $this->userHasAccessToParticipatePollsValidator->validate($employee->getUser());
        $this->canEmployeeCreateNewPollResultValidator->validate($activePollResults, $employee);
        $this->pollIsActiveValidator->validate($poll);
        $this->dishInDishListValidator->validate($poll->getMenu()->getDishes(), $dish);

        return $this->pollResultProvider->createNewPollResult($poll, $employee, $dish);
    }

    /**
     * @param DateTimeValidatorCollection $dateTimeValidatorCollection
     */
    public function setDateTimeValidatorCollection(DateTimeValidatorCollection $dateTimeValidatorCollection): void
    {
        $this->dateTimeValidatorCollection = $dateTimeValidatorCollection;
    }
}
