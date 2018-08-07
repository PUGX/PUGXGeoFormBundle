<?php

namespace PUGX\GeoFormBundle\EventListener;

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

    /**
     * @param GeoCodeManager          $geoCode
     * @param GeoDataAdapterInterface $dataAdapter
     * @param array                   $names
     */
    public function __construct(GeoCodeManager $geoCode, GeoDataAdapterInterface $dataAdapter, array $names)
    {
        $this->geoCode = $geoCode;
        $this->dataAdapter = $dataAdapter;
        $this->names = $names;
        if (!isset($names['lat']) || !isset($names['lng'])) {
            throw new \InvalidArgumentException('Names array must be formed with lat/lng keys.');
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => 'onFormPreSubmit',
        ];
    }

    /**
     * set coordinates if null.
     *
     * @param \Symfony\Component\Form\FormEvent $event
     */
    public function onFormPreSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();
        try {
            $address = $this->dataAdapter->getFullAddress($data, $form);

            $this->geoCode->query($address);
            $location = $this->geoCode->getFirst();
            $data[$this->names['lat']] = $location->getLatitude();
            $data[$this->names['lng']] = $location->getLongitude();

            $event->setData($data);
        } catch (\Exception $e) {
            //silently fail
        }
    }
}
