<?php

namespace PUGX\GeoFormBundle\Tests\Manager;

use PUGX\GeoFormBundle\Manager\GeoCodeManager;

class GeoCodeManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $provider;
    protected $geoCoder;
    protected $manager;
    protected $result;

    public function setUp()
    {
        $this->provider = $this->getMockbuilder('Geocoder\Provider\ProviderInterface')->getMock();
        $this->geoCoder = $this->getMockbuilder('Geocoder\Geocoder')->disableOriginalConstructor()->getMock();
        $this->result = $this->getMockbuilder('Geocoder\Result\ResultInterface')->disableOriginalConstructor()->getMock();
        $this->manager = new GeoCodeManager($this->geoCoder);
    }

    public function testRegisterProvider()
    {
        $this->geoCoder
            ->expects($this->once())
            ->method('registerProvider')
            ->with($this->provider);

        $this->manager->registerProvider($this->provider);
    }

    public function testQuery()
    {
        $this->geoCoder
            ->expects($this->once())
            ->method('getProviders')
            ->will($this->returnValue(array($this->provider)));

        $this->geoCoder
            ->expects($this->once())
            ->method('geocode')
            ->with('0, test street')
            ->will($this->returnValue($this->result));

        $this->manager->registerProvider($this->provider);
        $this->manager->query('0, test street');
        $results = $this->manager->getResults();
        $this->assertEquals(array($this->result), $results);
        $this->assertEquals($this->result, $this->manager->getFirst());
    }
} 
