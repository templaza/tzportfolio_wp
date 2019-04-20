<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2015 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

namespace tp\lib\Form\Field;

// no direct access
defined('ABSPATH') or exit;

use tp\lib\Form\FormHelper;
use tp\lib\Utilities\Registry;
use tp\lib\Language\TPLanguage;
use tp\lib\Utilities\AddOnHelper;

FormHelper::loadFieldClass('list');

class TZImageSizeListField extends ListField
{

    protected $type     = 'TZImageSizeList';

//    public function setup(\SimpleXMLElement $element, $value, $group = null)
//    {
//        $setup  = parent::setup($element, $value, $group);
//
////        if($this -> multiple) {
////            JHtml::_('formbehavior.chosen', '#' . $this->id);
////        }
//
//        return $setup;
//    }

    protected function getOptions(){
        $element        = $this -> element;
        $options        = array();
        $_plugin        = $element['addon']?$element['addon']:null;

        $_plugin_group  = $element['addon_group']?$element['addon_group']:'mediatype';
        $param_filter   = $element['param_name']?$element['param_name']:null;

        if($_plugin && $param_filter) {

            // Get config of add-on
            $addonOption    = get_option(TP_PLUGIN_ADDON_OPTION_PREFIX.$_plugin);
            if(!empty($addonOption)) {
                $plg_params = new Registry;
                $plg_params -> loadArray($addonOption);
                if($image_size = $plg_params -> get($param_filter)){
                    if(!is_array($image_size) && preg_match_all('/(\{.*?\})/',$image_size,$match)) {
                        $image_size = $match[1];
                    }

                    foreach($image_size as $i => $size){
                        $_size   = str_replace('\\', '', $size);
                        $_size  = json_decode($_size);
                        $options[$i]            = new \stdClass();
                        $options[$i] -> text    = $_size -> {$element['param_text']};
                        $options[$i] -> value   = $_size -> {$element['param_value']};
                    }
                }
            }
        }
        return array_merge(parent::getOptions(),$options);
    }
}