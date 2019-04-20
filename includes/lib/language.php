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

namespace tp\lib\Language;

// Exit if accessed directly.
defined('ABSPATH') or exit;

if(!class_exists('tp\lib\Language\TPLanguage')){
    class TPLanguage{

        protected static $caches        = array();
        protected static $strings       = array();
        protected static $textDomains   = array();

        public static function _($text, $textDomain = null, $domainPath = null){

            if(isset(static::$strings[$text])){
                return static::$strings[$text];
            }

            if($textDomain){
                self::loadTextDomain($textDomain, $domainPath);
            }

            if($translated = static::_find($text)){
                static::$strings[$text] = $translated;
                return $translated;
            }

            return $text;
        }

        public static function sprintf($string)
        {
            $args = func_get_args();
            $count = count($args);

            if ($count < 1)
            {
                return '';
            }

            if (is_array($args[$count - 1]))
            {
                $args[0]    = self::_($string);

//                $args[0] = $lang->_(
//                    $string, array_key_exists('jsSafe', $args[$count - 1]) ? $args[$count - 1]['jsSafe'] : false,
//                    array_key_exists('interpretBackSlashes', $args[$count - 1]) ? $args[$count - 1]['interpretBackSlashes'] : true
//                );
//
                if (array_key_exists('script', $args[$count - 1]) && $args[$count - 1]['script'])
                {
                    static::$strings[$string] = call_user_func_array('sprintf', $args);

                    return $string;
                }
            }
            else
            {
//                $args[0] = $lang->_($string);
                $args[0]    = self::_($string);
            }


            // Replace custom named placeholders with sprintf style placeholders
            $args[0] = preg_replace('/\[\[%([0-9]+):[^\]]*\]\]/', '%\1$s', $args[0]);

            return call_user_func_array('sprintf', $args);
        }

        /* Add text domain with domain path */
        public static function loadTextDomain($domain, $domainPath = null){
            if(!$domain){
                return;
            }

            if(!isset(self::$textDomains[$domain])){
                self::$textDomains[$domain] = $domainPath;
                load_plugin_textdomain($domain, false, $domainPath );
            }
        }

        /* Determines is a key exists. */
        public static function hasKey($string){

//            $storeId    = __METHOD__;
//            $storeId   .= ':'.serialize(self::$textDomains);
//            $storeId   .= ':'.$string;
//            $storeId    = md5($storeId);
//
//            if(isset(self::$caches[$storeId])){
//                return self::$caches[$storeId];
//            }

            if(isset(static::$strings[$string])){
                return true;
            }

            if($translated = static::_find($string)){
                static::$strings[$string] = $translated;
                return true;
            }

//            if(count(self::$textDomains)){
//                foreach(self::$textDomains as $domain => $path){
//                    if($translations = get_translations_for_domain( $domain )){
//                        $entries    = $translations -> entries;
//                        if(count($entries)){
//                            foreach ($entries as $textKey => $translation){
//                                if($textKey == $string && count($translation -> translations)){
//                                    self::$caches[$storeId] = true;
//                                    return true;
//                                }
//                            }
//                        }
//                    }
//                }
//            }
            return false;
        }

        /* Translates a string into the current language. */
        public static function alt($string, $alt){
            if(self::hasKey($string . '_' . $alt)){
                $string .= '_' . $alt;
            }
            return self::_($string);
        }

        protected static function _find($string){

            if(isset(static::$strings[$string])){
                return static::$strings[$string];
            }

            foreach(self::$textDomains as $domain => $path){
                if($translations = get_translations_for_domain( $domain )){
                    $entries    = $translations -> entries;
                    if(count($entries)){
                        foreach ($entries as $textKey => $translation){
                            if($textKey == $string && count($translation -> translations)){
                                $textTranslated = array_shift($translation -> translations);
                                return $textTranslated;
                            }
                        }
                    }
                }
            }

            return false;
        }
    }
}