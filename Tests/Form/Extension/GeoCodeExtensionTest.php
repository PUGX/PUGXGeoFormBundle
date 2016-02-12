<?php

namespace PUGX\GeoFormBundle\Tests\Form\Extension;

use PUGX\GeoFormBundle\Form\Extension\GeoCodeExtension;

class GeoCodeExtensionTest extends \PHPUnit_Framework_TestCase
{
    protected $resolver;
    protected $listener;

    /**
     * @var GeoCodeExtension
     */
    protected $extension;
    protected $formBuilder;

    public function setUp()
    {
        $this->resolver = $this->getMockbuilder('Symfony\Component\OptionsResolver\OptionsResolver')->disableOriginalConstructor()->getMock();
        $this->listener = $this->getMockbuilder('Symfony\Component\EventDispatcher\EventSubscriberInterface')->getMock();
        $this->formBuilder = $this->getMockbuilder('Symfony\Component\Form\FormBuilder')->disableOriginalConstructor()->getMock();
        $this->extension = new GeoCodeExtension($this->listener);
    }

    /**
     * @group legacy
     */
    public function testSetDefaultOptions()
    {
        $this->resolver
            ->expects($this->once())
            ->method('setDefaults')
            ->with(array(
                'geo_code' => false,
                'geo_code_field' => false,
            ));

        $this->extension->configureOptions($this->resolver);
    }

    public function testConfigureOptions()
    {
        $this->resolver
            ->expects($this->once())
            ->method('setDefaults')
            ->with(array(
                'geo_code' => false,
                'geo_code_field' => false,
            ));

        $this->extension->configureOptions($this->resolver);
    }

    public function testBuildFormWithGeoCode()
    {
        $this->formBuilder
            ->expects($this->once())
            ->method('addEventSubscriber')
            ->with($this->listener);

        $this->extension->buildForm($this->formBuilder, array('geo_code' => true));
    }

    public function testBuildFormWithoutGeoCode()
    {
        $this->formBuilder
            ->expects($this->never())
            ->method('addEventSubscriber');

        $this->extension->buildForm($this->formBuilder, array());
    }
}
