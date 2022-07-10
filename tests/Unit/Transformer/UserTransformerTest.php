<?php

namespace App\Tests\Unit\Transformer;

use App\Entity\User;
use App\Transformer\UserTransformer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserTransformerTest extends TestCase
{
    public function testToArray(): void
    {
        $params = $this->getMockBuilder(ParameterBagInterface::class)->getMock();
        $user = new User();
        $user->setName('khajackie');
        $user->setRoles(['ROLE_USER']);
        $user->setEmail('kha@gmail.com');
        $user->setAddress('Can Tho');
        $user->setPhone('0911603179');
        $userTransformer = new UserTransformer($params);
        $result = $userTransformer->fromArray($user);
        $this->assertEquals([
            'id' => null,
            'name' => 'khajackie',
            'email' => 'kha@gmail.com',
            'phone' => '0911603179',
            'address' => 'Can Tho',
            'roles' => ['ROLE_USER'],
            'avatar' => null
        ], $result);
    }
}
