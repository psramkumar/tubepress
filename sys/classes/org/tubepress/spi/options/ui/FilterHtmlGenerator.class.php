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

/**
 * Generates HTML for the drop-down option filter for the options page.
 */
interface org_tubepress_spi_options_ui_FilterHtmlGenerator
{
    const _ = 'org_tubepress_spi_options_ui_FilterHtmlGenerator';

    /**
     * Generates the HTML for the drop-down option filter.
     *
     * @return string The HTML for the drop-down filter.
    */
    function getHtml();
}
