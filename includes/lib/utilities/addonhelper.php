<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Plugin

# ------------------------------------------------------------------------

# Author:    DuongTVTemPlaza

# Copyright: Copyright (C) 2011-2019 TZ Portfolio.com. All Rights Reserved.

# @License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Website: http://www.tzportfolio.com

# Technical Support:  Forum - https://www.tzportfolio.com/help/forum.html

# Family website: http://www.templaza.com

# Family Support: Forum - https://www.templaza.com/Forums.html

-------------------------------------------------------------------------*/

namespace tp\lib\Utilities;

// no direct access
use tp\lib\Language\TPLanguage;

defined('ABSPATH') or exit;

if (!class_exists('tp\lib\Utilities\AddOnHelper')) {
    class AddOnHelper{

        protected static $cache             = array();
        protected static $textDomain_prefix = 'tp_addon_';

        public static function getAddOnById($id){

            $storeId    = __METHOD__;
            $storeId   .= ':'.$id;
            $storeId    = md5($storeId);

            if(isset(self::$cache[$storeId])){
                return self::$cache[$storeId];
            }

            $addon  = $id;
            if(strpos($id, '/') !== false) {
                $addon = dirname($id);
            }

            $manifest   = self::getManifest($addon);

            if($manifest){
                self::$cache[$storeId]  = $manifest;
                return $manifest;
            }

            return false;
        }

//        public static function getOptionById($id){
//
//            $storeId    = __METHOD__;
//            $storeId   .= ':'.$id;
//            $storeId    = md5($storeId);
//
//            if(isset(self::$cache[$storeId])){
//                return self::$cache[$storeId];
//            }
//
//            $addon  = $id;
//            if(strpos($id, '/') !== false) {
//                $addon = dirname($id);
//            }
//
//            return false;
//        }

        public static function getManifest($addon){

            $storeId    = __METHOD__;
            $storeId   .= ':'.$addon;
            $storeId    = md5($storeId);

            if(isset(self::$cache[$storeId])){
                return self::$cache[$storeId];
            }

            $xmlFile    = TP_PLUGIN_ADDON_PATH.'/'.$addon.'/'.$addon.'.xml';

            if(!File::exists($xmlFile)){
                return false;
            }

            $xml        = simplexml_load_file($xmlFile);
            $attribs    = $xml -> attributes();
            $element    = $addon;
            $type       = (string) $attribs -> group;

            $default_headers = array(
                'Name'        => 'Plugin Name',
                'PluginURI'   => 'Plugin URI',
                'Version'     => 'Version',
                'Description' => 'Description',
                'Author'      => 'Author',
                'AuthorURI'   => 'Author URI',
                'TextDomain'  => 'Text Domain',
                'DomainPath'  => 'Domain Path',
                'Network'     => 'Network',
                'Type'        => 'Type',
                'Element'     => 'Element',
                // Site Wide Only is deprecated in favor of Network.
                '_sitewide'   => 'Site Wide Only',
            );

            if ($xml->files && count($xml->files->children()))
            {
                foreach ($xml->files->children() as $oneFile)
                {
                    if ((string) $oneFile->attributes()->plugin)
                    {
                        $element = (string) $oneFile->attributes()->plugin;
                        break;
                    }
                }
            }

            $textDomain = self::$textDomain_prefix.$element;

            $textDomainPath =  plugin_basename( TP_PLUGIN_ADDON_PATH.'/'.$addon ) . '/language';


            if( !is_dir (Path::clean(WP_PLUGIN_DIR.'/'.$textDomainPath))){
                $textDomainPath .= 's';
            }

            if(!is_dir(Path::clean(WP_PLUGIN_DIR.'/'.$textDomainPath))){
                return false;
            }
            TPLanguage::loadTextDomain($textDomain, $textDomainPath);

            $extra_headers = array(
                'Name'        => TPLanguage::_(strtoupper((string) $xml -> name)),
                'PluginURI'   => 'Plugin URI',
                'Version'     => (string) $xml -> version,
                'Description' => TPLanguage::_(strtoupper((string) $xml -> description)),
                'Author'      => (string) $xml -> author,
                'AuthorURI'   => (string) $xml -> authorUrl,
                'TextDomain'  => 'Text Domain',
                'DomainPath'  => 'Domain Path',
                'Network'     => 'Network',
                'Type'        => $type,
                'Element'     => $element ,
                // Site Wide Only is deprecated in favor of Network.
                '_sitewide'   => 'Site Wide Only',
            );

            $manifest   = array_merge($default_headers, $extra_headers);

            self::$cache[$storeId]  = $manifest;

            return $manifest;
        }
        public static function getManifestByXMLFile($file){

            $storeId    = __METHOD__;
            $storeId   .= ':'.$file;
            $storeId    = md5($storeId);

            if(isset(self::$cache[$storeId])){
                return self::$cache[$storeId];
            }

            if(!File::exists($file)){
                return false;
            }

            $addon  = File::getName($file);
            $addon  = File::stripExt($addon);

            $textDomainPath = plugin_dir_path(plugin_basename($file)) . 'language';
            $textDomainPath = str_replace('/', '\\',$textDomainPath);

            if( !(is_dir (Path::clean(WP_PLUGIN_DIR.'/'.$textDomainPath)))){
                $textDomainPath .= 's';
            }

            if(!(is_dir(Path::clean(WP_PLUGIN_DIR.'/'.$textDomainPath)))) {
                return false;
            }

            $xmlFile    = $file;

            $xml        = simplexml_load_file($xmlFile);
            $attribs    = $xml -> attributes();
            $element    = $addon;
            $type       = (string) $attribs -> group;

            $default_headers = array(
                'Name'        => 'Plugin Name',
                'PluginURI'   => 'Plugin URI',
                'Version'     => 'Version',
                'Description' => 'Description',
                'Author'      => 'Author',
                'AuthorURI'   => 'Author URI',
                'TextDomain'  => 'Text Domain',
                'DomainPath'  => 'Domain Path',
                'Network'     => 'Network',
                'Type'        => 'Type',
                'Element'     => 'Element',
                // Site Wide Only is deprecated in favor of Network.
                '_sitewide'   => 'Site Wide Only',
            );

            if ($xml->files && count($xml->files->children()))
            {
                foreach ($xml->files->children() as $oneFile)
                {
                    if ((string) $oneFile->attributes()->plugin)
                    {
                        $element = (string) $oneFile->attributes()->plugin;
                        break;
                    }
                }
            }

            $textDomain = self::$textDomain_prefix."$element";

            load_plugin_textdomain($textDomain, false, $textDomainPath );

            $extra_headers = array(
                'Name'        => __( strtoupper((string) $xml -> name), $textDomain ),
                'PluginURI'   => 'Plugin URI',
                'Version'     => (string) $xml -> version,
                'Description' => __( strtoupper((string) $xml -> description), $textDomain ),
                'Author'      => (string) $xml -> author,
                'AuthorURI'   => (string) $xml -> authorUrl,
                'TextDomain'  => 'Text Domain',
                'DomainPath'  => 'Domain Path',
                'Network'     => 'Network',
                'Type'        => $type,
                'Element'     => $element ,
                // Site Wide Only is deprecated in favor of Network.
                '_sitewide'   => 'Site Wide Only',
            );

            $manifest   = array_merge($default_headers, $extra_headers);

            self::$cache[$storeId]  = $manifest;

            return $manifest;
        }

        public static function getTextDomainById($id, $group = false){

            $storeId    = __METHOD__;
            $storeId   .= ':'.$id;
            $storeId    = md5($storeId);

            if(isset(self::$cache[$storeId])){
                return self::$cache[$storeId];
            }

            $addon  = $id;
            if(strpos($id, '/') !== false) {
                $addon = dirname($id);
            }

            $manifest   = self::getManifest($addon);

            if(!$manifest){
                return false;
            }

            $textDomain = self::$textDomain_prefix.$manifest['Element'];

            if($group) {
                $textDomain = self::$textDomain_prefix.$manifest['Type'].'_'.$manifest['Element'];
            }

            self::$cache[$storeId]  = $textDomain;

            return $textDomain;
        }

        public static function getTextDomainPathById($id){

            $storeId    = __METHOD__;
            $storeId   .= ':'.$id;
            $storeId    = md5($storeId);

            if(isset(self::$cache[$storeId])){
                return self::$cache[$storeId];
            }

            $addon      = dirname($id);
            $manifest   = self::getManifest($addon);

            if(!$manifest){
                return false;
            }

            return plugin_basename(TP_PLUGIN_ADDON_PATH).'/'.$manifest['Element'].'/language';
        }
    }
}