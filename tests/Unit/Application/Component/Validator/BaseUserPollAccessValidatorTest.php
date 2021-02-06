<?php


namespace tests\Meals\Unit\Application\Component\Validator;


use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Application\Component\Validator\UserAccessToPollsValidatorInterface;
use Meals\Domain\User\Permission\PermissionList;
use Meals\Domain\User\User;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

abstract class BaseUserPollAccessValidatorTest extends TestCase
{
    use ProphecyTrait;

    abstract protected function getPermission(): string;

    abstract protected function getValidator(): UserAccessToPollsValidatorInterface;

    public function testSuccessful()
    {
        $permissionList = $this->prophesize(PermissionList::class);
        $permissionList->hasPermission($this->getPermission())->willReturn(true);

        $user = $this->prophesize(User::class);
        $user->getPermissions()->willReturn($permissionList->reveal());

        verify($this->getValidator()->validate($user->reveal()))->null();
    }

    public function testFail()
    {
        $this->expectException(AccessDeniedException::class);

        $permissionList = $this->prophesize(PermissionList::class);
        $permissionList->hasPermission($this->getPermission())->willReturn(false);

        $user = $this->prophesize(User::class);
        $user->getPermissions()->willReturn($permissionList->reveal());

        $this->getValidator()->validate($user->reveal());
    }
}
