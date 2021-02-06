<?php


namespace Meals\Application\Component\Validator;

use Meals\Domain\User\User;

interface UserAccessToPollsValidatorInterface
{
    public function validate(User $user): void;
}
