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
 * Plays videos at the top of a gallery
 */
class TPNormalPlayer extends TubePressPlayerAdapter
{
    /**
     * Tells the gallery how to play videos with the normal player
     *
     * @param TubePressVideo          $vid  The video to be played
     * @param TubePressOptionsManager $tpom The TubePress options manager
     * 
     * @return string The play link attributes
     */
    public function getPlayLink(TubePressVideo $vid, TubePressOptionsManager $tpom)
    {
    	
    	$embed = new TubePressEmbeddedPlayer();
        
        $title  = $vid->getTitle();
        $width  = $tpom->get(TubePressEmbeddedOptions::EMBEDDED_WIDTH);

        $optionsString = $embed->packOptionsToString($vid, $tpom);
		$embed->parseOptionsFromString($optionsString);
        
        return sprintf(<<<EOT
href="#" onclick="tubePress_normalPlayer('%s', '%d', '%s')"
EOT
			, rawurlencode($embed->toString(), $height, $title));
    }
    
    public function getPreGalleryHtml(TubePressVideo $vid, TubePressOptionsManager $tpom)
    {
    	$tpl = new HTML_Template_IT(dirname(__FILE__) . "/../../ui");
        if (!$tpl->loadTemplatefile("normal_mode_pre_gallery.tpl.html", true, true)) {
            throw new Exception("Couldn't load pre gallery template");
        }
    	
    	$embed = new TubePressEmbeddedPlayer();
    	$optionsString = $embed->packOptionsToString($vid, $tpom);
    	$embed->parseOptionsFromString($optionsString);
    	
        $tpl->setVariable("EMBEDSRC", $embed->toString());
        $tpl->setVariable("TITLE", $vid->getTitle());
        $tpl->setVariable("WIDTH", 
            $tpom->get(TubePressEmbeddedOptions::EMBEDDED_WIDTH));
        return $tpl->get();	
    }
}
?>
