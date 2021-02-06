<?php


namespace tests\Meals\Unit\Application\Component\Validator;


use Meals\Application\Component\Validator\UserAccessToPollsValidatorInterface;
use Meals\Application\Component\Validator\UserHasAccessToParticipatePollsValidator;
use Meals\Domain\User\Permission\Permission;

class UserHasAccessToParticipatePollsValidatorValidatorTest extends BaseUserPollAccessValidatorTest
{
    protected function getPermission(): string
    {
        return Permission::PARTICIPATION_IN_POLLS;
    }

    protected function getValidator(): UserAccessToPollsValidatorInterface
    {
        return new UserHasAccessToParticipatePollsValidator();
    }
}
