<?php

namespace PUGX\GeoFormBundle\Tests\EventListener;

use Geocoder\Location;
use Geocoder\Model\Coordinates;
use PUGX\GeoFormBundle\Adapter\GeoDataAdapterInterface;
use PUGX\GeoFormBundle\EventListener\GeoTypeForm;

class GeoTypeFormTest extends \PHPUnit\Framework\TestCase
{
    protected $formEvent;
    protected $form;
    protected $dataAdapter;
    protected $manager;
    protected $listener;
    protected $location;

    protected function setUp(): void
    {
        $this->formEvent = $this->getMockBuilder('Symfony\Component\Form\FormEvent')->disableOriginalConstructor()->getMock();
        $this->form = $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock();
        $this->dataAdapter = $this->createMock(GeoDataAdapterInterface::class);
        $this->manager = $this->getMockBuilder('PUGX\GeoFormBundle\Manager\GeoCodeManager')->disableOriginalConstructor()->getMock();
        $this->location = $this->createMock(Location::class);
        $this->listener = new GeoTypeForm($this->manager, $this->dataAdapter, ['lat' => 'latitude', 'lng' => 'longitude']);
    }

    public function testOnFormPreSubmit(): void
    {
        $address = 'Via XYZ 22';
        $data = [
            'address' => $address,
        ];

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
            ->expects($this->any())
            ->method('getCoordinates')
            ->will($this->returnValue(new Coordinates(41, 12)));

        $this->formEvent
            ->expects($this->once())
            ->method('setData')
            ->with(['address' => $address, 'latitude' => 41, 'longitude' => 12]);

        $this->listener->onFormPreSubmit($this->formEvent);
    }
}
