<?php


namespace tests\Meals\Unit\Application\Component\Validator;


use Meals\Application\Component\Validator\Exception\DateTimeIsNotAMondayException;
use Meals\Application\Component\Validator\IsDateTimeMondayValidator;
use PHPUnit\Framework\TestCase;

class IsDateTimeMondayValidatorTest extends TestCase
{
    public function testSuccessful()
    {
        $dateTime = new \DateTimeImmutable('first monday');

        $validator = new IsDateTimeMondayValidator();

        verify($validator->validate($dateTime))->null();
    }

    public function testFail()
    {
        $this->expectException(DateTimeIsNotAMondayException::class);

        $dateTime = new \DateTimeImmutable('first friday');
        $validator = new IsDateTimeMondayValidator();

        $validator->validate($dateTime);
    }
}
