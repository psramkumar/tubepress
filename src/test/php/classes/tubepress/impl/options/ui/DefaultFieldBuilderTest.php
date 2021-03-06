<?php
/**
 * Copyright 2006 - 2013 TubePress LLC (http://tubepress.org)
 *
 * This file is part of TubePress (http://tubepress.org)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
class FakeThingy
{
    public $_arg;

    public function __construct($arg)
    {
        $this->_arg = $arg;
    }
}

class tubepress_impl_options_ui_DefaultFieldBuilderTest extends TubePressUnitTest
{
    private $_sut;

    private $_mockOptionDescriptorReference;

    private $_mockMessageService;

    private $_mockStorageManager;

    private $_mockHttpRequestParameterService;

    private $_mockTemplateBuilder;

    private $_mockEnvironmentDetector;

    public function onSetup()
    {
        $this->_mockOptionDescriptorReference   = $this->createMockSingletonService(tubepress_spi_options_OptionDescriptorReference::_);
        $this->_mockMessageService              = $this->createMockSingletonService(tubepress_spi_message_MessageService::_);
        $this->_mockStorageManager              = $this->createMockSingletonService(tubepress_spi_options_StorageManager::_);
        $this->_mockHttpRequestParameterService = $this->createMockSingletonService(tubepress_spi_http_HttpRequestParameterService::_);
        $this->_mockTemplateBuilder             = $this->createMockSingletonService('ehough_contemplate_api_TemplateBuilder');
        $this->_mockEnvironmentDetector         = $this->createMockSingletonService(tubepress_spi_environment_EnvironmentDetector::_);

        $this->_sut = new tubepress_impl_options_ui_DefaultFieldBuilder();
    }

    public function testBuild()
    {
        $result = $this->_sut->build('something awesome', 'FakeThingy');

        $this->assertTrue($result instanceof FakeThingy);
        $this->assertEquals('something awesome', $result->_arg);
    }

    public function testBuildMeta()
    {
        $this->_setupOptionDescriptorReferenceForMetaMultiSelect();

        $mockProvider1 = $this->createMockPluggableService(tubepress_spi_provider_PluggableVideoProviderService::_);
        $mockProvider1->shouldReceive('getAdditionalMetaNames')->once()->andReturn(array('xyz'));

        $mockProvider2 = $this->createMockPluggableService(tubepress_spi_provider_PluggableVideoProviderService::_);
        $mockProvider2->shouldReceive('getAdditionalMetaNames')->once()->andReturn(array('abc'));

        $mockProviders = array($mockProvider1, $mockProvider2);

        $result = $this->_sut->buildMetaDisplayMultiSelectField();

        $this->assertTrue($result instanceof tubepress_impl_options_ui_fields_MetaMultiSelectField);
    }

    private function _getOdNames()
    {
        return array(

            tubepress_api_const_options_names_Meta::AUTHOR,
            tubepress_api_const_options_names_Meta::CATEGORY,
            tubepress_api_const_options_names_Meta::DESCRIPTION,
            tubepress_api_const_options_names_Meta::ID,
            tubepress_api_const_options_names_Meta::LENGTH,
            'abc',
            'xyz',
            tubepress_api_const_options_names_Meta::KEYWORDS,
            tubepress_api_const_options_names_Meta::TITLE,
            tubepress_api_const_options_names_Meta::UPLOADED,
            tubepress_api_const_options_names_Meta::URL,
            tubepress_api_const_options_names_Meta::VIEWS,
        );
    }

    private function _setupOptionDescriptorReferenceForMetaMultiSelect()
    {
        $names = $this->_getOdNames();

        $ods = array();

        foreach ($names as $name) {

            $od = new tubepress_spi_options_OptionDescriptor($name);
            $od->setBoolean();

            $ods[] = $od;

            $this->_mockOptionDescriptorReference->shouldReceive('findOneByName')->with($name)->once()->andReturn($od);
        }

        return $ods;
    }
}
