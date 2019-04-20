<?php
/**
 * Joomla! Content Management System
 *
 * @copyright  Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace tp\lib\Layout;

// Exit if accessed directly.
defined('ABSPATH') or exit;

if ( ! class_exists( 'tp\lib\Layout\LayoutHelper' ) ) {
    /**
     * Helper to render a Layout object, storing a base path
     *
     * @link   https://docs.joomla.org/Special:MyLanguage/Sharing_layouts_across_views_or_extensions_with_JLayout
     */
    class LayoutHelper
    {
        /**
         * A default base path that will be used if none is provided when calling the render method.
         * Note that FileLayout itself will defaults to JPATH_ROOT . '/layouts' if no basePath is supplied at all
         *
         * @var    string
         */
        public static $defaultBasePath = '';

        /**
         * Method to render a layout with debug info
         *
         * @param   string $layoutFile Dot separated path to the layout file, relative to base path
         * @param   mixed $displayData Object which properties are used inside the layout file to build displayed output
         * @param   string $basePath Base path to use when loading layout files
         * @param   mixed $options Optional custom options to load. Registry or array format
         *
         * @return  string
         *
         */
        public static function debug($layoutFile, $displayData = null, $basePath = '', $options = null)
        {
            $basePath = empty($basePath) ? self::$defaultBasePath : $basePath;

            // Make sure we send null to FileLayout if no path set
            $basePath = empty($basePath) ? null : $basePath;
            $layout = new FileLayout($layoutFile, $basePath, $options);

            return $layout->debug($displayData);
        }

        /**
         * Method to render the layout.
         *
         * @param   string $layoutFile Dot separated path to the layout file, relative to base path
         * @param   mixed $displayData Object which properties are used inside the layout file to build displayed output
         * @param   string $basePath Base path to use when loading layout files
         * @param   mixed $options Optional custom options to load. Registry or array format
         *
         * @return  string
         *
         */
        public static function render($layoutFile, $displayData = null, $basePath = '', $options = null)
        {
            $basePath = empty($basePath) ? self::$defaultBasePath : $basePath;

            // Make sure we send null to FileLayout if no path set
            $basePath = empty($basePath) ? null : $basePath;
            $layout = new FileLayout($layoutFile, $basePath, $options);

            return $layout->render($displayData);
        }
    }
}