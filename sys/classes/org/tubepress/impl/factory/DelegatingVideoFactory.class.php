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

class_exists('org_tubepress_impl_classloader_ClassLoader') || require(dirname(__FILE__) . '/../classloader/ClassLoader.class.php');
org_tubepress_impl_classloader_ClassLoader::loadClasses(array(
    'org_tubepress_api_factory_VideoFactory',
    'org_tubepress_api_patterns_StrategyManager',
    'org_tubepress_api_plugin_PluginManager',
    'org_tubepress_impl_ioc_IocContainer',
));

/**
 * Video factory that sends the feed to the right video factory based on the provider
 */
class org_tubepress_impl_factory_DelegatingVideoFactory implements org_tubepress_api_factory_VideoFactory
{
    /**
     * Converts raw video feeds to TubePress videos
     *
     * @param unknown $feed The raw feed result from the video provider
     * 
     * @return array an array of TubePress videos generated from the feed
     */
    public function feedToVideoArray($feed)
    {
        try {
            return $this->_wrappedFeedToVideoArray($feed);
        } catch (Exception $e) {
            org_tubepress_impl_log_Log::log('Delegating video factory', 'Caught exception building videos: ' . $e->getMessage());
            return array();
        }
    }
    
    protected function getArrayOfStrategyNames()
    {
        return array(
            'org_tubepress_impl_factory_strategies_YouTubeFactoryStrategy',
            'org_tubepress_impl_factory_strategies_VimeoFactoryStrategy'
        );
    }
    
    private function _wrappedFeedToVideoArray($feed)
    {
        $ioc = org_tubepress_impl_ioc_IocContainer::getInstance();
        $sm  = $ioc->get('org_tubepress_api_patterns_StrategyManager');
        $pm  = $ioc->get('org_tubepress_api_plugin_PluginManager');
        
        /* let the strategies do the heavy lifting */
        $videoArray = $sm->executeStrategy($this->getArrayOfStrategyNames(), $feed);

        if ($pm->hasFilters(org_tubepress_api_const_plugin_FilterPoint::VIDEO_ANY)) {
            for ($x = 0; $x < count($videoArray); $x++) {
                
                $videoArray[$x] = $pm->runFilters(org_tubepress_api_const_plugin_FilterPoint::VIDEO_ANY, $videoArray[$x]);
            }
        }
        
        return $videoArray;
    }
}
