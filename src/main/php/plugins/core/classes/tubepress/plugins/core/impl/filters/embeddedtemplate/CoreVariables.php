<?php
/**
 * Copyright 2006 - 2012 Eric D. Hough (http://ehough.com)
 *
 * This file is part of TubePress (http://tubepress.org)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * Core variables for the embedded template.
 */
class tubepress_plugins_core_impl_filters_embeddedtemplate_CoreVariables
{
    public function onEmbeddedTemplate(tubepress_api_event_TubePressEvent $event)
    {
        global $tubepress_base_url;

        $context  = tubepress_impl_patterns_sl_ServiceLocator::getExecutionContext();
        $template = $event->getSubject();
        $dataUrl  = $event->getArgument('dataUrl');
        $videoId  = $event->getArgument('videoId');

        $autoPlay    = $context->get(tubepress_api_const_options_names_Embedded::AUTOPLAY);
        $embedWidth  = $context->get(tubepress_api_const_options_names_Embedded::EMBEDDED_WIDTH);
        $embedHeight = $context->get(tubepress_api_const_options_names_Embedded::EMBEDDED_HEIGHT);

        $vars = array(

            tubepress_api_const_template_Variable::EMBEDDED_DATA_URL  => $dataUrl->toString(true),
            tubepress_api_const_template_Variable::TUBEPRESS_BASE_URL => $tubepress_base_url,
            tubepress_api_const_template_Variable::EMBEDDED_AUTOSTART => tubepress_impl_embedded_EmbeddedPlayerUtils::booleanToString($autoPlay),
            tubepress_api_const_template_Variable::EMBEDDED_WIDTH     => $embedWidth,
            tubepress_api_const_template_Variable::EMBEDDED_HEIGHT    => $embedHeight,
            tubepress_api_const_template_Variable::VIDEO_ID           => $videoId,
        );

        foreach ($vars as $key => $value) {

            $template->setVariable($key, $value);
        }

        return $template;
    }
}