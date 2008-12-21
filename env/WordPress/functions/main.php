<?php
/**
 * Copyright 2006, 2007, 2008 Eric D. Hough (http://ehough.com)
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
 * Main filter hook. Looks for a tubepress tag
 * and, if found, replaces it with a gallery
*/
function tubepress_content_filter($content = '')
{
	try {
		return _tp_main($content);
	} catch (Exception $e) {
		return $e->getMessage();
	}
}

function _tp_main($content) {

	$wpsm             = new WordPressStorageManager();
    $trigger          = $wpsm->get(TubePressAdvancedOptions::KEYWORD);
	$shortcodeService = new SimpleTubePressShortcodeService();
	$messageService   = new WordPressMessageService();
    
    if (!$shortcodeService->somethingToParse($content, $trigger)) {
	    return $content;
	}

	/* Store everything we generate in the following string */
    $newcontent = $content;
    
    while ($shortcodeService->somethingToParse($newcontent, $trigger)) {
 
	    $tpom = new SimpleTubePressOptionsManager();
	    $tpom->setStorageManager($wpsm);
	    $shortcodeService->parse($newcontent, $tpom);

	    $gallery = new TubePressGallery();
	    _tp_setGalleryInterfaces($gallery, $tpom);
    	if (TubePressDebug::areWeDebugging($tpom)) {
	        TubePressDebug::execute($gallery, $tpom);
	    }

	    /* replace the tag with our new content */
	    $newcontent = TubePressStringUtils::replaceFirst($tpom->getShortcode(), 
	        $gallery->generate(), $newcontent);
    }
    
    return $newcontent;
}

/**
 * Spits out the CSS and JS files that we always need for TubePress
 */
function tubepress_head_filter()
{
	try {
	    _tp_insertCSSJS();
	} catch (Exception $e) {
		/* this is in the head, so just print an HTML comment and proceed */
        print "<!-- " . $e->getMessage() . " -->";
	}
}

function _tp_insertCSSJS() {
    global $tubepress_base_url;
    print<<<GBS
        <script type="text/javascript" src="$tubepress_base_url/common/js/tubepress.js"></script>
        <link rel="stylesheet" href="$tubepress_base_url/common/css/tubepress.css" 
            type="text/css" />
       <link rel="stylesheet" href="$tubepress_base_url/common/css/tubepress_widget.css" 
            type="text/css" />
        <link rel="stylesheet" href="$tubepress_base_url/common/css/pagination.css" 
            type="text/css" />
GBS;

    $wpsm = new WordPressStorageManager();
    
    if ($wpsm->get(TubePressAdvancedOptions::KEYWORD) === NULL) {
        return;
    }
    
    $playerName = $wpsm->get(TubePressDisplayOptions::CURRENT_PLAYER_NAME);
    $factory = new SimpleTubePressPlayerFactory();
    $player = $factory->getInstance($playerName);
    print $player->getHeadContents();
}

function _tp_setGalleryInterfaces(TubePressGallery $gallery, TubePressOptionsManager $tpom)
{
	$messageService = new WordPressMessageService();
	$playerFactory = new SimpleTubePressPlayerFactory();
	
	$thumbService = new SimpleTubePressThumbnailService();
    $thumbService->setOptionsManager($tpom);
    $thumbService->setMessageService($messageService);
    	
    $queryStringService = new SimpleTubePressQueryStringService();
    	
    $urlBuilderService = new SimpleTubePressUrlBuilder();
    $urlBuilderService->setOptionsManager($tpom);
    	
    $paginationService = new TubePressPaginationService_DiggStyle();
    $paginationService->setMessageService($messageService);
    $paginationService->setOptionsManager($tpom);
    $paginationService->setQueryStringService($queryStringService);

    $feedRetrievalService = new TubePressFeedRetrievalService_HTTP_Request2();
    $feedRetrievalService->setCacheService(new SimpleTubePressCacheService());
	$gallery->setFeedInspectionService( new SimpleTubePressFeedInspectionService());
	$gallery->setFeedRetrievalService(	 $feedRetrievalService);
	$gallery->setOptionsManager(		 $tpom);
	$gallery->setPaginationService(	 $paginationService);
	$gallery->setPlayerFactory($playerFactory);
	$gallery->setQueryStringService(new SimpleTubePressQueryStringService());
	$gallery->setEmbeddedPlayerService(new SimpleTubePressEmbeddedPlayerService());
	$gallery->setThumbnailService(		 $thumbService);
	$gallery->setUrlBuilderService(	 $urlBuilderService);
	$gallery->setVideoFactory(			 new SimpleTubePressVideoFactory());
}

?>