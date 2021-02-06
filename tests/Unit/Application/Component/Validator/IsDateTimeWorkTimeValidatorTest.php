<?php

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\DateTimeIsNotWorkTimeException;
use Meals\Application\Component\Validator\IsDateTimeWorkTimeValidator;
use PHPUnit\Framework\TestCase;

class IsDateTimeWorkTimeValidatorTest extends TestCase
{
    public function testSuccessful()
    {
        $minDateTime = new \DateTimeImmutable('11.11.2011 06:00:00');
        $maxDateTime = new \DateTimeImmutable('11.11.2011 22:00:00');

        $validator = new IsDateTimeWorkTimeValidator();

        verify($validator->validate($minDateTime))->null();
        verify($validator->validate($maxDateTime))->null();
    }

    public function testFail()
    {
        $this->expectException(DateTimeIsNotWorkTimeException::class);

        $minDateTime = new \DateTimeImmutable('11.11.2011 05:59:59');
        $maxDateTime = new \DateTimeImmutable('11.11.2011 22:00:01');

        $validator = new IsDateTimeWorkTimeValidator();

        try {
            $validator->validate($minDateTime);
        } catch (DateTimeIsNotWorkTimeException $exception) {
            $validator->validate($maxDateTime);
        }

        $this->fail('Exception was not thrown!');
    }
}
