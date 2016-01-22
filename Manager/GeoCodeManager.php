<?php

namespace PUGX\GeoFormBundle\Manager;

use Geocoder\Geocoder;
use Geocoder\Provider\ProviderInterface;

class GeoCodeManager
{
    protected $geoCoder;
    protected $results;

    public function __construct(Geocoder $geoCoder)
    {
        $this->geoCoder = $geoCoder;
    }

    /**
     * Get results.
     *
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Query the chain of geo searching services.
     *
     * @param string           $q
     * @param RuntimeException $e
     */
    public function query($q)
    {
        if (0 === count($this->geoCoder->getProviders())) {
            throw new \RuntimeException('Service is not set');
        }
        $this->results = array($this->geoCoder->geocode($q));
    }

    /**
     * Get a specific result.
     *
     * @param int $index
     *
     * @return Geocoder\Result\ResultInterface
     */
    public function getResult($index)
    {
        if (!isset($this->results[$index])) {
            return;
        }

        return $this->results[$index];
    }

    /**
     * Get the first result.
     *
     * @return Geocoder\Result\ResultInterface
     */
    public function getFirst()
    {
        return !isset($this->results[0]) ?: $this->results[0];
    }

    public function registerProvider(ProviderInterface $provider)
    {
        $this->geoCoder->registerProvider($provider);
    }
}
