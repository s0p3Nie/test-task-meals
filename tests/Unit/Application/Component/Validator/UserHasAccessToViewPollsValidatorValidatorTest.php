<?php

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\UserAccessToPollsValidatorInterface;
use Meals\Application\Component\Validator\UserHasAccessToViewPollsValidator;
use Meals\Domain\User\Permission\Permission;

class UserHasAccessToViewPollsValidatorValidatorTest extends BaseUserPollAccessValidatorTest
{
    protected function getPermission(): string
    {
        return Permission::VIEW_ACTIVE_POLLS;
    }

    protected function getValidator(): UserAccessToPollsValidatorInterface
    {
        return new UserHasAccessToViewPollsValidator();
    }
}
