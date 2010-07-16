<?php
/**
 * Copyright 2006 - 2010 Eric D. Hough (http://ehough.com)
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

function_exists('tubepress_load_classes')
    || require(dirname(__FILE__) . '/../../../tubepress_classloader.php');
tubepress_load_classes(array('org_tubepress_video_feed_retrieval_FeedRetrievalService',
    'org_tubepress_cache_CacheService',
    'org_tubepress_log_Log'));

/**
 * Base functionality for feed retrieval services
 *
 */
abstract class org_tubepress_video_feed_retrieval_AbstractFeedRetrievalService implements org_tubepress_video_feed_retrieval_FeedRetrievalService
{
    private $_cache;
    protected $_logPrefix;
    
    /**
     * Fetches the feed from the remote provider
     * 
     * @return unknown The raw feed from the provider
     */
    public function fetch($url, $useCache)
    {   
        global $tubepress_base_url;
        
        $testUrl = "$tubepress_base_url/classes/org/tubepress/video/feed/retrieval/ConnectionTest.php";
        org_tubepress_log_Log::log($this->_logPrefix, 'Connection test can be run at <a href="%s">%s</a>',
            $testUrl, $testUrl);
        
        $result = "";
        if ($useCache) {
            
            org_tubepress_log_Log::log($this->_logPrefix, 'First asking cache for %s', $url);
            
            if ($this->_cache->has($url)) {
                org_tubepress_log_Log::log($this->_logPrefix, 'Cache has %s. Sweet.', $url);
                $result = $this->_cache->get($url);
            } else {
                org_tubepress_log_Log::log($this->_logPrefix, 'Cache does not have %s. We\'ll have to get it from the network.', $url);
                $result = $this->_getFromNetwork($url);
                $this->_cache->save($url, $result);
            }
        } else {
            org_tubepress_log_Log::log($this->_logPrefix, 'Skip cache check for %s', $url);
            $result = $this->_getFromNetwork($url);
        }
        return $result;
    }
    
    public function setCacheService(org_tubepress_cache_CacheService $cache) { $this->_cache = $cache; }
    
    private function _getFromNetwork($url)
    {
        $data = $this->_fetchFromNetwork($url);

        /* trim it just in case */
        $data = trim($data);
        
	return $data;
    }
    
    protected abstract function _fetchFromNetwork($request);
}
