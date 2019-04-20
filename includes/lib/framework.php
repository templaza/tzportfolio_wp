<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Extension

# ------------------------------------------------------------------------

# Author:    DuongTVTemPlaza

# Copyright: Copyright (C) 2011-2019 TZ Portfolio.com. All Rights Reserved.

# @License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Website: http://www.tzportfolio.com

# Technical Support:  Forum - https://www.tzportfolio.com/help/forum.html

# Family website: http://www.templaza.com

# Family Support: Forum - https://www.templaza.com/Forums.html

-------------------------------------------------------------------------*/

// Exit if accessed directly.
defined('ABSPATH') or exit;

if (!class_exists('tp\lib\Loader')) {
    require_once TP_PLUGIN_LIBRARY_PATH . '/loader.php';
}

use tp\lib\Loader;

Loader::import('html.htmlhelper');
Loader::import('utilities.path');
Loader::import('inputfilter');
Loader::import('language');

require_once TP_PLUGIN_LIBRARY_PATH.'/classmap.php';

Loader::setup();

use tp\lib\HTML\HTMLHelper;
HTMLHelper::addIncludePath(TP_PLUGIN_LIBRARY_PATH.'/html');