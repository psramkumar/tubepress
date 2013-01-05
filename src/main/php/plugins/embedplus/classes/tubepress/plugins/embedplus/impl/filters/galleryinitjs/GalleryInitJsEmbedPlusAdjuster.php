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
 * Adjusts the height for EmbedPlus.
 */
class tubepress_plugins_embedplus_impl_filters_galleryinitjs_GalleryInitJsEmbedPlusAdjuster
{
    private static $_PROPERTY_NVPMAP = 'nvpMap';

    public function onGalleryInitJs(tubepress_api_event_TubePressEvent $event)
    {
        $context   = tubepress_impl_patterns_sl_ServiceLocator::getExecutionContext();
        $args      = $event->getSubject();
        $height    = $context->get(tubepress_api_const_options_names_Embedded::EMBEDDED_HEIGHT);

        $args[self::$_PROPERTY_NVPMAP][tubepress_api_const_options_names_Embedded::EMBEDDED_HEIGHT] = ($height + 32);

        $event->setSubject($args);
    }
}