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

/*
 * WordPress stubbornly will not load except from the global scope.
 */
if (strpos(realpath(__FILE__), 'wp-content' . DIRECTORY_SEPARATOR . 'plugins') !== false ||true) {

    include dirname(__FILE__) . '/../../../../../../../wp-blog-header.php';
}

/**
 * Boot tubepress.
 */
require 'boot.php';

/**
 * Hand off the request to the Ajax handler.
 */
tubepress_impl_patterns_sl_ServiceLocator::getAjaxHandler()->handle();
