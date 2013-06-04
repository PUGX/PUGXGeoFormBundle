<?php

namespace PUGX\GeoFormBundle\Adapter;


use Symfony\Component\Form\FormInterface;

interface GeoDataAdapterInterface
{
    function getFullAddress($data, FormInterface $form);
}