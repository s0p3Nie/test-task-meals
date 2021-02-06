<?php

namespace Meals\Application\Component\Validator;

class DateTimeValidatorCollection
{
    /** @var DateTimeValidatorInterface[] */
    private $dateTimeValidators = [];

    /** @var \DateTimeInterface */
    private $dateTimeToValidate;

    /**
     * DateTimeValidatorCollection constructor.
     *
     * @param IsDateTimeMondayValidator $dateTimeMondayValidator
     * @param IsDateTimeWorkTimeValidator $dateTimeWorkTimeValidator
     */
    public function __construct(
        IsDateTimeMondayValidator $dateTimeMondayValidator,
        IsDateTimeWorkTimeValidator $dateTimeWorkTimeValidator
    )
    {
        $this->dateTimeValidators[] = $dateTimeMondayValidator;
        $this->dateTimeValidators[] = $dateTimeWorkTimeValidator;
        // set NOW() by default
        $this->setDateTimeToValidate(new \DateTimeImmutable());
    }

    public function validate(): void
    {
        foreach ($this->dateTimeValidators as $validator) {
            $validator->validate($this->dateTimeToValidate);
        }
    }

    public function setDateTimeToValidate(\DateTimeInterface $dateTime): void
    {
        $this->dateTimeToValidate = $dateTime;
    }
}
