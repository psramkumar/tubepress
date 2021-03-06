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
class tubepress_plugins_jwplayer_impl_embedded_JwPlayerTemplateVarsTest extends TubePressUnitTest
{
    /**
     * @var tubepress_plugins_jwplayer_impl_filters_embeddedtemplate_JwPlayerTemplateVars
     */
    private $_sut;

    private $_mockExecutionContext;

    public function onSetup() {

        $this->_sut = new tubepress_plugins_jwplayer_impl_filters_embeddedtemplate_JwPlayerTemplateVars();

        $this->_mockExecutionContext = $this->createMockSingletonService(tubepress_spi_context_ExecutionContext::_);
    }

    public function testLongtail()
    {
        $mockTemplate = Mockery::mock('ehough_contemplate_api_Template');

        $event = new tubepress_api_event_TubePressEvent($mockTemplate);

        $event->setArgument('embeddedImplementationName', 'longtail');

        $toSet = array(

            tubepress_plugins_jwplayer_api_const_template_Variable::COLOR_FRONT =>
            tubepress_plugins_jwplayer_api_const_options_names_Embedded::COLOR_FRONT,

            tubepress_plugins_jwplayer_api_const_template_Variable::COLOR_LIGHT =>
            tubepress_plugins_jwplayer_api_const_options_names_Embedded::COLOR_LIGHT,

            tubepress_plugins_jwplayer_api_const_template_Variable::COLOR_SCREEN =>
            tubepress_plugins_jwplayer_api_const_options_names_Embedded::COLOR_SCREEN,

            tubepress_plugins_jwplayer_api_const_template_Variable::COLOR_BACK =>
            tubepress_plugins_jwplayer_api_const_options_names_Embedded::COLOR_BACK,
        );

        foreach ($toSet as $variableName => $optionName) {

            $this->_mockExecutionContext->shouldReceive('get')->once()->with($optionName)->andReturnUsing(function ($arg) {

                return "<<$arg>>";
            });

            $mockTemplate->shouldReceive('setVariable')->once()->with($variableName, "<<$optionName>>");
        }

        $this->_sut->onEmbeddedTemplate($event);

        $this->assertTrue(true);
    }

    public function testNonLongtail()
    {
        $event = new tubepress_api_event_TubePressEvent();

        $event->setArgument('embeddedImplementationName', 'foobarr');

        $this->_sut->onEmbeddedTemplate($event);

        $this->assertTrue(true);
    }

}

