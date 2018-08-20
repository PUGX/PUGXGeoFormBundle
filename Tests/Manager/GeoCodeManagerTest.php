<?php

namespace PUGX\GeoFormBundle\Tests\Manager;

use Geocoder\Collection;
use Geocoder\Location;
use Geocoder\Provider\Provider;
use Geocoder\ProviderAggregator;
use PUGX\GeoFormBundle\Manager\GeoCodeManager;

class GeoCodeManagerTest extends \PHPUnit\Framework\TestCase
{
    private $provider;
    private $geoCoder;
    private $manager;
    private $collection;
    private $result;

    protected function setUp()
    {
        $this->provider = $this->createMock(Provider::class);
        $this->geoCoder = $this->createMock(ProviderAggregator::class);
        $this->collection = $this->createMock(Collection::class);
        $this->result = $this->createMock(Location::class);
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
            ->will($this->returnValue([$this->provider]));

        $this->geoCoder
            ->expects($this->once())
            ->method('geocode')
            ->with('0, test street')
            ->will($this->returnValue($this->collection));

        $this->manager->registerProvider($this->provider);
        $this->manager->query('0, test street');
        $results = $this->manager->getResults();
        $this->assertEquals($this->collection, $results);
    }
}
