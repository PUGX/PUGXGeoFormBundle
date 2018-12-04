<?php

namespace PUGX\GeoFormBundle\Manager;

use Geocoder\Collection;
use Geocoder\Location;
use Geocoder\Provider\Provider;
use Geocoder\ProviderAggregator;

class GeoCodeManager
{
    /**
     * @var ProviderAggregator
     */
    protected $geoCoder;

    /**
     * @var Collection
     */
    protected $results;

    public function __construct(ProviderAggregator $geoCoder)
    {
        $this->geoCoder = $geoCoder;
    }

    /**
     * Get results.
     *
     * @return Collection|Location
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    /**
     * Query the chain of geo searching services.
     *
     * @param string $query
     *
     * @throws \Geocoder\Exception\Exception
     */
    public function query(string $query): void
    {
        if (0 === \count($this->geoCoder->getProviders())) {
            throw new \RuntimeException('Service is not set');
        }
        $this->results = $this->geoCoder->geocode($query);
    }

    /**
     * Get a specific result.
     *
     * @param int $index
     *
     * @return Location|null
     */
    public function getResult(int $index): ?Location
    {
        if (isset($this->results[$index])) {
            return $this->results[$index];
        }

        return null;
    }

    /**
     * Get the first result.
     *
     * @return Location|null
     */
    public function getFirst(): ?Location
    {
        return $this->getResult(0);
    }

    public function registerProvider(Provider $provider): void
    {
        $this->geoCoder->registerProvider($provider);
    }
}
