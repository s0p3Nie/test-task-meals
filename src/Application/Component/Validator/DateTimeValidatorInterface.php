<?php

namespace Meals\Application\Component\Validator;

interface DateTimeValidatorInterface
{
    public function validate(\DateTimeInterface $dateTime): void;
}
