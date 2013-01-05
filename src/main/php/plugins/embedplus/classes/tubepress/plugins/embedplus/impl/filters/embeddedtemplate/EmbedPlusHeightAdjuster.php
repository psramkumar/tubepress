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
 * Makes the embedded player 30px taller when EmbedPlus is in use.
 */
class tubepress_plugins_embedplus_impl_filters_embeddedtemplate_EmbedPlusHeightAdjuster
{
    public function onEmbeddedTemplate(tubepress_api_event_TubePressEvent $event)
    {
        $context              = tubepress_impl_patterns_sl_ServiceLocator::getExecutionContext();
        $playerImplementation = $context->get(tubepress_api_const_options_names_Embedded::PLAYER_IMPL);

        if ($playerImplementation !== 'embedplus') {

            /*
             * Not using EmbedPlus.
             */
            return;
        }

        $template = $event->getSubject();
        $height   = $context->get(tubepress_api_const_options_names_Embedded::EMBEDDED_HEIGHT);

        $template->setVariable(tubepress_api_const_template_Variable::EMBEDDED_HEIGHT, ($height + 32));
    }
}