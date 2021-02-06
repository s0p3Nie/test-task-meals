<?php


namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\DateTimeIsNotWorkTimeException;

class IsDateTimeWorkTimeValidator implements DateTimeValidatorInterface
{
    const SIX_AM = 60000;
    const TEN_PM = 220000;

    /**
     * @param \DateTimeInterface $dateTime
     */
    public function validate(\DateTimeInterface $dateTime)
    {
        // format time '03:12:56 '-> '031256', casting to Int '031256' -> 31256
        $currentTimeInt = (int) $dateTime->format('His');
        if (self::SIX_AM > $currentTimeInt || self::TEN_PM < $currentTimeInt) {
            throw new DateTimeIsNotWorkTimeException();
        }
    }
}
