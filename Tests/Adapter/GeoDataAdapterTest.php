<?php

namespace PUGX\GeoFormBundle\Tests\Adapter;

use PUGX\GeoFormBundle\Adapter\GeoDataAdapter;

class GeoDataAdapterTest extends \PHPUnit\Framework\TestCase
{
    public function testGetFullAddressThrowException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $form = $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock();
        $form
            ->expects($this->once())
            ->method('all')
            ->will($this->returnValue([]));

        $adapter = new GeoDataAdapter();
        $adapter->getFullAddress([], $form);
    }

    public function testGetFullAddressReturnValues(): void
    {
        $form = $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock();
        $field = $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock();

        $configfield = $this->getMockBuilder('Symfony\Component\Form\FormConfigInterface')->disableOriginalConstructor()->getMock();

        $form
            ->expects($this->once())
            ->method('all')
            ->will(
                $this->returnValue(
                    [
                        $field,
                        $field,
                    ]
                )
            );

        $field
            ->expects($this->at(0))
            ->method('getConfig')
            ->will(
                $this->returnValue(
                    $configfield
                )
            );

        $field
            ->expects($this->at(1))
            ->method('getConfig')
            ->will(
                $this->returnValue(
                    $configfield
                )
            );

        $field
            ->expects($this->at(2))
            ->method('getName')
            ->will(
                $this->returnValue(
                    'address'
                )
            );

        $configfield
            ->expects($this->exactly(2))
            ->method('getOptions')
            ->will(
                $this->onConsecutiveCalls(
                    [],
                    ['geo_code_field' => true]
                )
            );

        $adapter = new GeoDataAdapter();
        $address = $adapter->getFullAddress(['address' => 'Via XYZ'], $form);
        $this->assertEquals('Via XYZ', $address);
    }
}
