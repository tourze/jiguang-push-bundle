<?php

namespace JiguangPushBundle\Tests\Integration\Repository;

use Doctrine\Persistence\ManagerRegistry;
use JiguangPushBundle\Entity\Device;
use JiguangPushBundle\Repository\DeviceRepository;
use PHPUnit\Framework\TestCase;

class DeviceRepositoryTest extends TestCase
{
    public function testRepositoryClassName(): void
    {
        $registry = $this->createMock(ManagerRegistry::class);
        $repository = new DeviceRepository($registry);
        
        $this->assertInstanceOf(DeviceRepository::class, $repository);
    }

    public function testDeviceEntity(): void
    {
        $device = new Device();
        $device->setRegistrationId('test_registration_id');
        
        $this->assertSame('test_registration_id', $device->getRegistrationId());
    }
}