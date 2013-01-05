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
 * Registers a few extensions to allow TubePress to work with EmbedPlus.
 */
class tubepress_plugins_embedplus_EmbedPlus
{
    public static function init()
    {return;
        $eventDispatcher = tubepress_impl_patterns_sl_ServiceLocator::getEventDispatcher();
        $callback        = array('tubepress_plugins_embedplus_EmbedPlus', '_callbackHandleEvent');
        $eventNames      = array(

            //tubepress_api_const_event_CoreEventNames::EMBEDDED_TEMPLATE_CONSTRUCTION,
            tubepress_api_const_event_CoreEventNames::GALLERY_INIT_JS_CONSTRUCTION,
        );

        foreach ($eventNames as $eventName) {

            $eventDispatcher->addListener($eventName, $callback);
        }
    }

    public static function _callbackHandleEvent(tubepress_api_event_TubePressEvent $event)
    {
        switch ($event->getName()) {

            case tubepress_api_const_event_CoreEventNames::EMBEDDED_TEMPLATE_CONSTRUCTION:

                self::_call(

                    $event,
                    'tubepress_plugins_embedplus_impl_filters_embeddedtemplate_EmbedPlusHeightAdjuster', 'onEmbeddedTemplate'
                );

                break;

            case tubepress_api_const_event_CoreEventNames::GALLERY_INIT_JS_CONSTRUCTION:

                self::_call(

                    $event,
                    'tubepress_plugins_embedplus_impl_filters_galleryinitjs_GalleryInitJsEmbedPlusAdjuster', 'onGalleryInitJs'
                );

                break;
        }
    }

    private static function _call(tubepress_api_event_TubePressEvent $event, $serviceId, $functionName)
    {
        $serviceInstance = tubepress_impl_patterns_sl_ServiceLocator::getService($serviceId);

        $serviceInstance->$functionName($event);
    }
}

tubepress_plugins_embedplus_EmbedPlus::init();