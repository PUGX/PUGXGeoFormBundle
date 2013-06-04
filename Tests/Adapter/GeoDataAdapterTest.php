<?php

namespace PUGX\GeoFormBundle\Tests\Adapter;

use PUGX\GeoFormBundle\Adapter\GeoDataAdapter;

class GeoDataAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetFullAddressThrowException()
    {
        $form = $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock();
        $form
            ->expects($this->once())
            ->method('all')
            ->will($this->returnValue(array()));

        $adapter = new GeoDataAdapter();
        $adapter->getFullAddress(array(), $form);
    }

    public function testGetFullAddressReturnValues()
    {
        $form = $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock();
        $field = $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock();

        $configfield = $this->getMockBuilder('Symfony\Component\Form\FormConfigInterface')->disableOriginalConstructor()->getMock();

        $form
            ->expects($this->once())
            ->method('all')
            ->will(
                $this->returnValue(
                    array(
                        $field,
                        $field
                    )
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
                    array(),
                    array('geo_code_field' => true)
                )
            );

        $adapter = new GeoDataAdapter();
        $address = $adapter->getFullAddress(array('address' => 'Via XYZ'), $form);
        $this->assertEquals('Via XYZ', $address);
    }
}
