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

/**
 * Applies the embedded service name to the template.
 */
class tubepress_plugins_core_filters_gallerytemplate_CoreVariables
{
    public function onGalleryTemplate(tubepress_api_event_ThumbnailGalleryTemplateConstruction $event)
    {
        $context          = tubepress_impl_patterns_ioc_KernelServiceLocator::getExecutionContext();
        $videoGalleryPage = $event->getArgument(tubepress_api_event_ThumbnailGalleryTemplateConstruction::ARGUMENT_VIDEO_GALLERY_PAGE);
        $template         = $event->getSubject();

        $videoArray  = $videoGalleryPage->getVideos();
        $thumbWidth  = $context->get(tubepress_api_const_options_names_Thumbs::THUMB_WIDTH);
        $thumbHeight = $context->get(tubepress_api_const_options_names_Thumbs::THUMB_HEIGHT);
        $galleryId   = $context->get(tubepress_api_const_options_names_Advanced::GALLERY_ID);

        /* add some core template variables */
        $template->setVariable(tubepress_api_const_template_Variable::VIDEO_ARRAY, $videoArray);
        $template->setVariable(tubepress_api_const_template_Variable::GALLERY_ID, $galleryId);
        $template->setVariable(tubepress_api_const_template_Variable::THUMBNAIL_WIDTH, $thumbWidth);
        $template->setVariable(tubepress_api_const_template_Variable::THUMBNAIL_HEIGHT, $thumbHeight);
    }
}