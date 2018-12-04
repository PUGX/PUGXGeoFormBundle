<?php

namespace PUGX\GeoFormBundle\Adapter;

use Symfony\Component\Form\FormInterface;

class GeoDataAdapter implements GeoDataAdapterInterface
{
    public function getFullAddress($data, FormInterface $form): string
    {
        $fields = [];
        foreach ($form->all() as $field) {
            $options = $field->getConfig()->getOptions();
            if (isset($options['geo_code_field']) && true === $options['geo_code_field']) {
                $fields[] = $data[$field->getName()];
            }
        }

        if (\count($fields) > 0) {
            return \implode(' ', $fields);
        }

        throw new \InvalidArgumentException('GeoDataAdapter address field mismatch');
    }
}
