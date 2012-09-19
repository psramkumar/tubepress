<?php
/**
 * Copyright 2006 - 2012 Eric D. Hough (http://ehough.com)
 *
 * This file is part of TubePress (http://tubepress.org)
 *
 * TubePress is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * TubePress is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with TubePress.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
class tubepress_plugins_core_filters_gallerytemplate_EmbeddedPlayerNameTest extends PHPUnit_Framework_TestCase
{
	private $_sut;

    private $_mockExecutionContext;

	function setup()
	{
		$this->_sut = new tubepress_plugins_core_filters_gallerytemplate_EmbeddedPlayerName();

        $this->_mockExecutionContext = Mockery::mock(tubepress_spi_context_ExecutionContext::_);
        tubepress_impl_patterns_ioc_KernelServiceLocator::setExecutionContext($this->_mockExecutionContext);
	}

    function tearDown()
    {
        Mockery::close();
    }

    function testAlterTemplateLongtailYouTube()
    {
        $this->_testCustomYouTube(tubepress_api_const_options_values_PlayerImplementationValue::LONGTAIL);
    }

    function testAlterTemplateEmbedPlusYouTube()
    {
        $this->_testCustomYouTube(tubepress_api_const_options_values_PlayerImplementationValue::EMBEDPLUS);
    }

    function testAlterTemplateProviderDefault()
    {
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_api_const_options_names_Embedded::PLAYER_IMPL)->andReturn('player-impl');

        $providerResult = new tubepress_api_video_VideoGalleryPage();

        $mockTemplate = \Mockery::mock('ehough_contemplate_api_Template');
        $mockTemplate->shouldReceive('setVariable')->once()->with(org_tubepress_api_const_template_Variable::EMBEDDED_IMPL_NAME, 'provider-name');

        $event = new tubepress_api_event_ThumbnailGalleryTemplateConstruction($mockTemplate);
        $event->setArguments(array(

            tubepress_api_event_ThumbnailGalleryTemplateConstruction::ARGUMENT_PAGE => 1,
            tubepress_api_event_ThumbnailGalleryTemplateConstruction::ARGUMENT_VIDEO_GALLERY_PAGE => $providerResult,
            tubepress_api_event_ThumbnailGalleryTemplateConstruction::ARGUMENT_PROVIDER_NAME => 'provider-name'
        ));

        $this->_sut->onGalleryTemplate($event);

        $this->assertEquals($mockTemplate, $event->getSubject());
    }

    private function _testCustomYouTube($name)
    {
        $this->_mockExecutionContext->shouldReceive('get')->once()->with(tubepress_api_const_options_names_Embedded::PLAYER_IMPL)->andReturn($name);

        $providerResult = new tubepress_api_video_VideoGalleryPage();

        $mockTemplate = \Mockery::mock('ehough_contemplate_api_Template');
        $mockTemplate->shouldReceive('setVariable')->once()->with(org_tubepress_api_const_template_Variable::EMBEDDED_IMPL_NAME, $name);

        $event = new tubepress_api_event_ThumbnailGalleryTemplateConstruction($mockTemplate);
        $event->setArguments(array(

            tubepress_api_event_ThumbnailGalleryTemplateConstruction::ARGUMENT_PAGE => 1,
            tubepress_api_event_ThumbnailGalleryTemplateConstruction::ARGUMENT_VIDEO_GALLERY_PAGE => $providerResult,
            tubepress_api_event_ThumbnailGalleryTemplateConstruction::ARGUMENT_PROVIDER_NAME => tubepress_spi_provider_Provider::YOUTUBE
        ));

        $this->_sut->onGalleryTemplate($event);

        $this->assertEquals($mockTemplate, $event->getSubject());


    }
}

