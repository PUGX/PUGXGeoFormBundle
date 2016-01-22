<?php

namespace PUGX\GeoFormBundle\Adapter;

use Symfony\Component\Form\FormInterface;

class GeoDataAdapter implements GeoDataAdapterInterface
{
    /**
     * @param mixed         $data
     * @param FormInterface $form
     * 
     * @return mixed
     * 
     * @throws \InvalidArgumentException
     */
    public function getFullAddress($data, FormInterface $form)
    {
        $fields = array();
        foreach ($form->all() as $field) {
            $options = $field->getConfig()->getOptions();
            if (isset($options['geo_code_field']) && true == $options['geo_code_field']) {
                $fields[] = $data[$field->getName()];
            }
        }

        if (count($fields)) {
            return implode(' ', $fields);
        }

        throw new \InvalidArgumentException('GeoDataAdapter address field mismatch');
    }
}
