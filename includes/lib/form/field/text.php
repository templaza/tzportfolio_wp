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

namespace tp\lib\Form\Field;

// no direct access
defined('ABSPATH') or exit;

use tp\lib\Form\FormField;
use tp\lib\HTML\HTMLHelper;
use tp\lib\Language\TPLanguage;

if ( ! class_exists( 'tp\lib\Form\Field\TextField' ) ) {
    class TextField extends FormField{
        /**
         * The form field type.
         *
         * @var    string
         */
        protected $type = 'Text';

        /**
         * The allowable maxlength of the field.
         *
         * @var    integer
         */
        protected $maxLength;

        /**
         * The mode of input associated with the field.
         *
         * @var    mixed
         */
        protected $inputmode;

        /**
         * The name of the form field direction (ltr or rtl).
         *
         * @var    string
         */
        protected $dirname;

        /**
         * Name of the layout being used to render the field
         *
         * @var    string
         */
        protected $layout = 'text';

        /**
         * Method to get certain otherwise inaccessible properties from the form field object.
         *
         * @param   string  $name  The property name for which to get the value.
         *
         * @return  mixed  The property value or null.
         *
         */
        public function __get($name)
        {
            switch ($name)
            {
                case 'maxLength':
                case 'dirname':
                case 'inputmode':
                    return $this->$name;
            }

            return parent::__get($name);
        }

        /**
         * Method to set certain otherwise inaccessible properties of the form field object.
         *
         * @param   string  $name   The property name for which to set the value.
         * @param   mixed   $value  The value of the property.
         *
         * @return  void
         *
         */
        public function __set($name, $value)
        {
            switch ($name)
            {
                case 'maxLength':
                    $this->maxLength = (int) $value;
                    break;

                case 'dirname':
                    $value = (string) $value;
                    $this->dirname = ($value == $name || $value == 'true' || $value == '1');
                    break;

                case 'inputmode':
                    $this->inputmode = (string) $value;
                    break;

                default:
                    parent::__set($name, $value);
            }
        }

        /**
         * Method to attach a JForm object to the field.
         *
         * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
         * @param   mixed             $value    The form field value to validate.
         * @param   string            $group    The field name group control value. This acts as an array container for the field.
         *                                      For example if the field has name="foo" and the group value is set to "bar" then the
         *                                      full field name would end up being "bar[foo]".
         *
         * @return  boolean  True on success.
         *
         * @see     JFormField::setup()
         */
        public function setup(\SimpleXMLElement $element, $value, $group = null)
        {
            $result = parent::setup($element, $value, $group);

            if ($result == true)
            {
                $inputmode = (string) $this->element['inputmode'];
                $dirname = (string) $this->element['dirname'];

                $this->inputmode = '';
                $inputmode = preg_replace('/\s+/', ' ', trim($inputmode));
                $inputmode = explode(' ', $inputmode);

                if (!empty($inputmode))
                {
                    $defaultInputmode = in_array('default', $inputmode) ? TPLanguage::_('JLIB_FORM_INPUTMODE') . ' ' : '';

                    foreach (array_keys($inputmode, 'default') as $key)
                    {
                        unset($inputmode[$key]);
                    }

                    $this->inputmode = $defaultInputmode . implode(' ', $inputmode);
                }

                // Set the dirname.
                $dirname = ((string) $dirname == 'dirname' || $dirname == 'true' || $dirname == '1');
                $this->dirname = $dirname ? $this->getName($this->fieldname . '_dir') : false;

                $this->maxLength = (int) $this->element['maxlength'];
            }

            return $result;
        }

        /**
         * Method to get the field input markup.
         *
         * @return  string  The field input markup.
         *
         */
        protected function getInput()
        {
            return $this->getRenderer($this->layout)->render($this->getLayoutData());
        }

        /**
         * Method to get the field options.
         *
         * @return  array  The field option objects.
         *
         */
        protected function getOptions()
        {
            $options = array();

            foreach ($this->element->children() as $option)
            {
                // Only add <option /> elements.
                if ($option->getName() != 'option')
                {
                    continue;
                }


                // Create a new option object based on the <option /> element.
                $options[] = HTMLHelper::_(
                    'select.option', (string) $option['value'],
                    TPLanguage::alt(trim((string) $option), $this -> textDomain), 'value', 'text'
                );
            }

            return $options;
        }

        /**
         * Method to get the field suggestions.
         *
         * @return  array  The field option objects.
         *
         * @deprecated  4.0  Use getOptions instead
         */
        protected function getSuggestions()
        {
            return $this->getOptions();
        }

        /**
         * Method to get the data to be passed to the layout for rendering.
         *
         * @return  array
         *
         */
        protected function getLayoutData()
        {
            $data = parent::getLayoutData();

            // Initialize some field attributes.
            $maxLength    = !empty($this->maxLength) ? ' maxlength="' . $this->maxLength . '"' : '';
            $inputmode    = !empty($this->inputmode) ? ' inputmode="' . $this->inputmode . '"' : '';
            $dirname      = !empty($this->dirname) ? ' dirname="' . $this->dirname . '"' : '';

            /* Get the field options for the datalist.
                Note: getSuggestions() is deprecated and will be changed to getOptions() with 4.0. */
            $options  = (array) $this->getSuggestions();

            $extraData = array(
                'maxLength' => $maxLength,
                'pattern'   => $this->pattern,
                'inputmode' => $inputmode,
                'dirname'   => $dirname,
                'options'   => $options,
            );

            return array_merge($data, $extraData);
        }
    }
}