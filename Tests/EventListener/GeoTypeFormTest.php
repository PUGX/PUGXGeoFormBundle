<?php

namespace PUGX\GeoFormBundle\Tests\EventListener;

use PUGX\GeoFormBundle\EventListener\GeoTypeForm;

class GeoTypeFormTest extends \PHPUnit_Framework_TestCase
{
    protected $formEvent;
    protected $form;
    protected $dataAdapter;
    protected $manager;
    protected $listener;
    protected $location;

    public function setUp()
    {
        $this->formEvent   = $this->getMockBuilder('Symfony\Component\Form\FormEvent')->disableOriginalConstructor()->getMock();
        $this->form        = $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock();
        $this->dataAdapter = $this->getMockBuilder('PUGX\GeoFormBundle\Adapter\GeoDataAdapterInterface')->getMock();
        $this->manager     = $this->getMockBuilder('PUGX\GeoFormBundle\Manager\GeoCodeManager')->disableOriginalConstructor()->getMock();
        $this->location    = $this->getMockBuilder('Geo\Location')->disableOriginalConstructor()->getMock();
        $this->listener    = new GeoTypeForm($this->manager, $this->dataAdapter);
    }

    public function testOnFormPreSubmit()
    {
        $address = 'Via XYZ 22';
        $data = array(
            'address' => $address,
        );

        $this->formEvent
            ->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($data));

        $this->formEvent
            ->expects($this->once())
            ->method('getForm')
            ->will($this->returnValue($this->form));

        $this->dataAdapter
            ->expects($this->once())
            ->method('getFullAddress')
            ->with($data, $this->form)
            ->will($this->returnValue($address));

        $this->manager
            ->expects($this->once())
            ->method('query')
            ->with($address);

        $this->manager
            ->expects($this->once())
            ->method('getFirst')
            ->will($this->returnValue($this->location));

        $this->location
            ->expects($this->once())
            ->method('getLatitude')
            ->will($this->returnValue(123));

        $this->location
            ->expects($this->once())
            ->method('getLongitude')
            ->will($this->returnValue(456));

        $this->formEvent
            ->expects($this->once())
            ->method('setData')
            ->with(array('address' => $address, 'latitude' => 123, 'longitude' => 456));


        $this->listener->onFormPreBind($this->formEvent);
    }
}
