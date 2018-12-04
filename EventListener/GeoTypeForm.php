<?php

namespace PUGX\GeoFormBundle\EventListener;

use Geocoder\Exception\Exception;
use PUGX\GeoFormBundle\Adapter\GeoDataAdapterInterface;
use PUGX\GeoFormBundle\Manager\GeoCodeManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class GeoTypeForm implements EventSubscriberInterface
{
    /**
     * @var GeoCodeManager
     */
    private $geoCode;

    /**
     * @var GeoDataAdapterInterface
     */
    private $dataAdapter;

    /**
     * @var array
     */
    private $names;

    public function __construct(GeoCodeManager $geoCode, GeoDataAdapterInterface $dataAdapter, array $names)
    {
        $this->geoCode = $geoCode;
        $this->dataAdapter = $dataAdapter;
        $this->names = $names;
        if (!isset($names['lat'], $names['lng'])) {
            throw new \InvalidArgumentException('Names array must be formed with lat/lng keys.');
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'onFormPreSubmit',
        ];
    }

    /**
     * set coordinates if null.
     *
     * @param FormEvent $event
     */
    public function onFormPreSubmit(FormEvent $event): void
    {
        $data = $event->getData();
        $form = $event->getForm();
        try {
            $address = $this->dataAdapter->getFullAddress($data, $form);

            $this->geoCode->query($address);
            $location = $this->geoCode->getFirst();
            if (null !== $location && (null !== $coordinates = $location->getCoordinates())) {
                $data[$this->names['lat']] = $coordinates->getLatitude();
                $data[$this->names['lng']] = $coordinates->getLongitude();
                $event->setData($data);
            }
        } catch (Exception $e) {
            //silently fail
        }
    }
}
