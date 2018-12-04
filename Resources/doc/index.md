PUGXGeoFormBundle Documentation
===============================

## Prerequisites

This version of the bundle requires Symfony >= 3.4

PUGXGeoFormBundle uses [willdurand/geocoder](https://packagist.org/packages/willdurand/geocoder) as a backend geocoding service.

## Installation

1. Download PUGXGeoFormBundle
2. Enable the Bundle
3. Form

### 1. Download PUGXGeoFormBundle

**Using composer**

Run

``` bash
$ composer require php-http/guzzle6-adapter pugx/geo-form-bundle
```

>⚠️ for now, you need to require `php-http/guzzle6-adapter:^1.0`.
> Version `^2.0` will be usable only after a new stable release
> from https://github.com/geocoder-php/php-common-http

You can require a different HTTP client from Guzzle. Any PSR-7 compatible client should be OK.

### 2. Enable the bundle

If you don't use Flex, you need to enable the bundle in the kernel:

``` php
<?php
// e.g. app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new PUGX\GeoFormBundle\PUGXGeoFormBundle(),
    ];
}
```

### 3. config.yml

Add a `pugx_geo_form` entry in your config.yml, specifying if ssl should be used and the region
(note: this is just a bias, not a geographic constraint - see for example
[google maps api docs](https://developers.google.com/maps/documentation/geocoding/?hl=it-IT&csw=1#RegionCodes))
You can also customize the names for "latitude" and "longitude", or omit that options and get default ones.
If you installed an HTTP client different from Guzzle, you can specify it in the last two options.

``` yml
pugx_geo_form:
    region: IT
    useSsl: false
    # the following options are not mandatory (here are shown with their default value)
    names:
        lat: latitude
        lng: longitude
    http_adapter: Http\Adapter\Guzzle6\Client
```

Example of configuration with Buzz instead of Guzzle:

``` yml
pugx_geo_form:
    # [...]
    http_adapter: Http\Adapter\Buzz\Client
```


### 3. Form

In order to use geolocalization in a form, you should:

1. add two hidden "latitude" and "longitude" fields (feel free to use different names, see previous point);
2. add the `geo_code` option in the form default options;
3. specify which field has geocodable information with the `geo_code_field` option.

Here's an example:

``` php
<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('address', Type\TextType::class, ['required' => true, 'geo_code_field' => true]);
        $builder->add('longitude', Type\HiddenType::class, ['required' => false]);
        $builder->add('latitude', Type\HiddenType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'geo_code' => true,
        ]);
    }
}
```

Before this form is bound, latitude and longitude fields will be populated with a call to your preferred geolocalization
API (Google Maps by default).

If you want to concatenate more than one field (i.e. composing the full address of a venue through address, city, country),
you can specify the `geo_code_field` option for more than one field:

``` php
<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('address', TypeTextType::class, ['required' => true, 'geo_code_field' => true]);
        $builder->add('city', TypeTextType::class, ['required' => true, 'geo_code_field' => true]);
        $builder->add('country', TypeTextType::class, ['required' => true, 'geo_code_field' => true]);
        $builder->add('longitude', TypeHiddenType::class, ['required' => false]);
        $builder->add('latitude', TypeHiddenType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'geo_code' => true,
        ]);
    }
}

```

In addition, a little Javascript snippet is included in the bundle for integrating Google Places Autocomplete.
In order to use it, you have to add some classes to you form fields:

``` php
<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('address', TypeTextType::class, [
            'required' => true,
            'attr' => [
                'class' => 'pugx-geocode'
            )
        ]);
        $builder->add('longitude', TypeHiddenType::class, [
            'required' => false,
            'attr' => [
                'class' => 'pugx-geocode-longitude'
            )
        ]);
        $builder->add('latitude', TypeHiddenType::class, [
            'required' => false,
            'attr' => [
                'class' => 'pugx-geocode-latitude'
            )
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'geo_code' => true,
        ]);
    }
}

```

As you can see, `pugx-geocode` is used for the meaningful geocoding field (the address), while `pugx-geocode-latitude`
and `pugx-geocode-longitude` are used for easily identifying latitude and longitude fields.

Once you have the classes in place, you can include the snippet in your twig (jQuery is required):

``` html+jinja
<script src="//maps.googleapis.com/maps/api/js?key=YOURKEY&amp;libraries=places&amp;language={{ app.request.locale }}"></script>
<script src="{{ asset('/bundles/pugxgeoform/js/google_maps_autocomplete.js') }}"></script>

```

This time the latitude and longitude fields will be populated via javascript and the server will not make an additional
call the the geolocalization API.

> ⚠  You'll need to [get an API key](https://developers.google.com/maps/documentation/javascript/get-api-key)
> from Google and put it in the above template, replacing `YOURKEY` string.

