<?php
/**
 * Copyright 2006 - 2011 Eric D. Hough (http://ehough.com)
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

class_exists('org_tubepress_impl_classloader_ClassLoader') || require dirname(__FILE__) . '/../classloader/ClassLoader.class.php';
org_tubepress_impl_classloader_ClassLoader::loadClasses(array(
    'org_tubepress_api_template_TemplateBuilder',
    'org_tubepress_impl_template_SimpleTemplate',
));

/**
 * Very simple template builder.
 *
 */
class org_tubepress_impl_template_SimpleTemplateBuilder implements org_tubepress_api_template_TemplateBuilder
{
    /**
    * Get a new template instance.
    *
    * @param string $path The absolute path of the template.
    *
    * @return org_tubepress_api_template_Template The template instance.
    *
    * @throws Exception If the template cannot be built.
    */
    public function getNewTemplateInstance($path)
    {
        $template = new org_tubepress_impl_template_SimpleTemplate();
        $template->setPath($path);
        return $template;
    }
}
