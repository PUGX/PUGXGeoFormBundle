PUGXGeoFormBundle Documentation
==================================

## Prerequisites

This version of the bundle requires Symfony >= 2.3.

PUGXGeoFormBundle uses [ideato/geo-adapter](https://packagist.org/packages/ideato/geo-adapter) as a backend geocoding service.

## Installation

1. Download PUGXGeoFormBundle
2. Enable the Bundle
3. Form

### 1. Download PUGXGeoFormBundle

**Using composer**

Add the following lines in your composer.json:

```
{
    "require": {
        "ideato/geo-adapter": "*@dev",
        "pugx/geo-form-bundle": "*@dev"
    }
}

```

Now, run the composer to download the bundle:

``` bash
$ php composer.phar update pugx/geo-form-bundle
```

### 2. Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new PUGX\GeoFormBundle\PUGXGeoFormBundle(),
    );
}
```

### 3. Form

In order to use geolocalization in a form, you should:

1. add two hidden latitude and longitude fields;
2. add the geo_code option in the form default options;
3. specify which field has geocodable information with the geo_code_field option.

Here's an example:

``` php

<?php

namespace Acme\MyBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('address', 'text', array('required' => true, 'geo_code_field' => true));
        $builder->add('longitude', 'hidden', array('required' => false));
        $builder->add('latitude', 'hidden', array('required' => false));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'geo_code' => true,
        ));
    }

    public function getName()
    {
        return 'search';
    }
}

```

Before this form is bound latitude and longitude fields will be populated with a call to your preferred geolocalization
API (Google Maps by default).

If you want to concatenate more than one field (I.e. composing the full address of a venue through address, city, country),
you can specify the geo_code_field option for more than one field:

``` php

<?php

namespace Acme\MyBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('address', 'text', array('required' => true, 'geo_code_field' => true));
        $builder->add('city', 'text', array('required' => true, 'geo_code_field' => true));
        $builder->add('country', 'text', array('required' => true, 'geo_code_field' => true));
        $builder->add('longitude', 'hidden', array('required' => false));
        $builder->add('latitude', 'hidden', array('required' => false));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'geo_code' => true,
        ));
    }

    public function getName()
    {
        return 'search';
    }
}

```

In addition, a little javascript snippet is included in the bundle for integrating Google Places Autocomplete.
In order to use it, you have to add some classes to you form fields:

``` php

<?php

namespace Acme\MyBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('address', 'text', array(
            'required' => true,
            'attr'   =>  array(
                'class'   => 'pugx-geocode'
            )
        ));
        $builder->add('longitude', 'hidden', array(
            'required' => false,
            'attr'   =>  array(
                'class'   => 'pugx-geocode-longitude'
            )
        ));

        $builder->add('latitude', 'hidden', array(
            'required' => false,
            'attr'   =>  array(
                'class'   => 'pugx-geocode-latitude'
            )
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'geo_code' => true,
        ));
    }

    public function getName()
    {
        return 'search';
    }
}

```

As you can see, pugx-geocode is used for the meaningful geocoding field (the address), while pugx-geocode-latitude and
 pugx-geocode-longitude ar used for easily identifying latitude and longitude fields.

Once you have the classes in place, you can include the snippet in your twig (jQuery is required):

``` html

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=true&language={{ app.request.locale }}"></script>
<script type="text/javascript" src="{{ asset('/bundles/pugxgeoform/js/google_maps_autocomplete.js') }}"></script>

```

This time the latitude and longitude fields will be populated via javascript and the server will not make an additional
call the the geolocalization API.
