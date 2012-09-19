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
 * Performs WordPress initialization.
 */
class tubepress_plugins_core_listeners_WordPressBoot
{
    public function onBoot(ehough_tickertape_api_Event $bootEvent)
    {
        $ed = tubepress_impl_patterns_ioc_KernelServiceLocator::getEnvironmentDetector();

        if (!$ed->isWordPress()) {

            return;
        }

        global $tubepress_base_url;

        $baseName          = $ed->getTubePressInstallationDirectoryBaseName();
        $wpFunctionWrapper = tubepress_impl_wordpress_WordPressServiceLocator::getWordPressFunctionWrapper();

        /** http://code.google.com/p/tubepress/issues/detail?id=495#c2 */
        if ($this->_isWordPressMuDomainMapped()) {

            $prefix = $this->_getScheme($wpFunctionWrapper) . constant('COOKIE_DOMAIN');

        } else {

            $prefix = $wpFunctionWrapper->site_url();
        }

        $tubepress_base_url = $prefix . "/wp-content/plugins/$baseName";

        /* register the plugin's message bundles */
        $wpFunctionWrapper->load_plugin_textdomain('tubepress', false, "$baseName/src/main/resources/i18n");

        $contentFilter    = tubepress_impl_wordpress_WordPressServiceLocator::getContentFilter();
        $jsAndCssInjector = tubepress_impl_wordpress_WordPressServiceLocator::getFrontEndCssAndJsInjector();
        $wpAdminHandler   = tubepress_impl_wordpress_WordPressServiceLocator::getWpAdminHandler();
        $widgetHandler    = tubepress_impl_wordpress_WordPressServiceLocator::getWidgetHandler();

        $wpFunctionWrapper->add_filter('the_content', array($contentFilter, 'filterContent'));
        $wpFunctionWrapper->add_action('wp_head', array($jsAndCssInjector, 'printInHtmlHead'));
        $wpFunctionWrapper->add_action('init', array($jsAndCssInjector, 'registerStylesAndScripts'));

        $wpFunctionWrapper->add_action('admin_menu', array($wpAdminHandler, 'registerAdminMenuItem'));
        $wpFunctionWrapper->add_action('admin_enqueue_scripts', array($wpAdminHandler, 'registerStylesAndScripts'));

        $wpFunctionWrapper->add_action('widgets_init', array($widgetHandler, 'registerWidget'));
    }

    private function _getScheme(tubepress_spi_wordpress_WordPressFunctionWrapper $wpFunctionWrapper)
    {
        if ($wpFunctionWrapper->is_ssl()) {

            return 'https://';
        }

        return 'http://';
    }

    private function _isWordPressMuDomainMapped()
    {
        return defined('DOMAIN_MAPPING') && constant('DOMAIN_MAPPING') && defined('COOKIE_DOMAIN');
    }
}
