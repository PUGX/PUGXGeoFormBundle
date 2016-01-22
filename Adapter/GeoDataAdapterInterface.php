<?php

namespace PUGX\GeoFormBundle\Adapter;

use Symfony\Component\Form\FormInterface;

interface GeoDataAdapterInterface
{
    public function getFullAddress($data, FormInterface $form);
}
