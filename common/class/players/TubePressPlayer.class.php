<?php
/**
 * TubePressPlayer.php
 * 
 * Copyright (C) 2007 Eric D. Hough (http://ehough.com)
 * 
 * This file is part of TubePress
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
 */

/**
 * A TubePress "player", such as lightWindow, GreyBox, popup window, etc
 */
abstract class TubePressPlayer implements TubePressHasTitle, TubePressHasName
{
	const newWindow = "new_window";
	const youTube = "youtube";
	const normal = "normal";
	const popup = "popup";
	const greyBox = "greybox";
	const lightWindow = "lightwindow";
	const shadowBox = "shadowbox";
	
	private $title;
	private $value;
	private $name;
	
	/*
	 * for each player, we want to know which CSS
	 * and JS libraries that it needs
	 */
	private $_cssLibs = array();
	private $_jsLibs = array();
	private $_preLoadHeaderJs = "";
	private $_postLoadHeaderJs = "";
	
	public final function getHeadContents() {
    	$content = "";
	    if ($this->_preLoadHeaderJs != "") {
        	$content .= "<script type=\"text/javascript\">" . $this->_preLoadHeaderJs . "</script>";
        }
    	
    	foreach ($this->_jsLibs as $jsLib) {
    		$content .= "<script type=\"text/javascript\" src=\"" . $jsLib . "\"></script>";
    	}
    	
		if ($this->_postLoadHeaderJs != "") {
        	$content .= "<script type=\"text/javascript\">" . $this->_postLoadHeaderJs . "</script>";
        }
    	
    	foreach ($this->_cssLibs as $cssLib) {
    		$content .= "<link rel=\"stylesheet\" href=\"" . $cssLib . "\"" .
            	" type=\"text/css\" />";
    	}
    	return $content;
	}
	
	protected final function setPostLoadJs($extraJS) {
	    if (!is_string($extraJS)) {
	        throw new Exception("Postload JS must be a string");
	    }
	    $this->_postLoadHeaderJs = $extraJS;
	}
	
	protected final function setPreLoadJs($extraJS) {
	    if (!is_string($extraJS)) {
	        throw new Exception("Preload JS must be a string");
	    }
	    $this->_preLoadHeaderJs = $extraJS;
	}
	
	protected final function setJSLibs($jsLibs) {
	    if (!is_array($jsLibs)) {
	        throw new Exception("JS libraries must be an array");
	    }
	    $this->_jsLibs = $jsLibs;
	}
	
	protected final function setCSSLibs($cssLibs) {
	    if (!is_array($cssLibs)) {
	        throw new Exception("CSS libraries must be an array");
	    }
	    $this->_cssLibs = $cssLibs;
	}
	
	protected final function setTitle($newTitle) {
	    $this->title = $newTitle;
	}
	
	protected final function setName($newName) {
	    $this->name = $newName;
	}
	
	public final function getTitle() { return $this->title; }
	public final function getName() { return $this->name; }
	
	public abstract function getPlayLink(TubePressVideo $vid, TubePressStorage_v157 $stored);
	
	public static function getInstance($name) {
	    switch ($name) {
	        case TubePressPlayer::normal:
	            return new TPNormalPlayer();
	            break;
	        case TubePressPlayer::greyBox:
	        	return new TPGreyBoxPlayer();
	        	break;
	        case TubePressPlayer::popup:
	        	return new TPPopupPlayer();
	        	break;
	        case TubePressPlayer::youTube:
	        	return new TPYouTubePlayer();
	        	break;
	        case TubePressPlayer::newWindow:
	        	return new TPNewWindowPlayer();
	        	break;
	        case TubePressPlayer::lightWindow:
	        	return new TPlightWindowPlayer();
	        case TubePressPlayer::greyBox:
	        	return new TPGreyBoxPlayer();
	        case TubePressPlayer::shadowBox:
	        	return new TPShadowBoxPlayer();
	        default:
	            throw new Exception("No such player with name '" . $name . "'");
	        
	    }
	}
}
?>
