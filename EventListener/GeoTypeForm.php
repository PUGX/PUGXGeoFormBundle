<?php

namespace PUGX\GeoFormBundle\EventListener;

use PUGX\GeoFormBundle\Adapter\GeoDataAdapterInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use PUGX\GeoFormBundle\Manager\GeoCodeManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GeoTypeForm implements EventSubscriberInterface
{
    /**
     *
     * @var \PUGX\GeoFormBundle\Manager\GeoCodeManager
     */
    private $geoCode;

    /**
     *
     * @param \PUGX\GeoFormBundle\Manager\GeoCodeManager $geoCode
     */
    public function __construct(GeoCodeManager $geoCode, GeoDataAdapterInterface $dataAdapter)
    {
        $this->geoCode     = $geoCode;
        $this->dataAdapter = $dataAdapter;
    }

    /**
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SUBMIT => 'onFormPreSubmit'
        );
    }

    /**
     * set coordinates if null
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
            $data['latitude'] = $location->getLatitude();
            $data['longitude'] = $location->getLongitude();

            $event->setData($data);
        } catch (\Exception $e) {
            //silently fail
        }
    }
}
