<?php

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\DateTimeIsNotAMondayException;

class IsDateTimeMondayValidator implements DateTimeValidatorInterface
{
    /**
     * @param \DateTimeInterface $dateTime
     */
    public function validate(\DateTimeInterface $dateTime)
    {
        if ('Mon' !== $dateTime->format('D')) {
            throw new DateTimeIsNotAMondayException();
        }
    }
}
