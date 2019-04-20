<?php

namespace tp\lib\Form\Field;

// no direct access
defined('ABSPATH') or exit;

use tp\lib\Form\FormField;
use tp\lib\Language\TPLanguage;

if ( ! class_exists( 'tp\lib\Form\Field\NumberField' ) ) {

    /**
     * Form Field class for the Joomla Platform.
     * Provides a one line text box with up-down handles to set a number in the field.
     *
     * @link   http://www.w3.org/TR/html-markup/input.text.html#input.text
     */
    class NumberField extends FormField
    {
        /**
         * The form field type.
         *
         * @var    string
         */
        protected $type = 'Number';

        /**
         * The allowable maximum value of the field.
         *
         * @var    float
         */
        protected $max = null;

        /**
         * The allowable minimum value of the field.
         *
         * @var    float
         */
        protected $min = null;

        /**
         * The step by which value of the field increased or decreased.
         *
         * @var    float
         */
        protected $step = 0;

        /**
         * Name of the layout being used to render the field
         *
         * @var    string
         */
        protected $layout = 'joomla.form.field.number';

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
                case 'max':
                case 'min':
                case 'step':
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
                case 'step':
                case 'min':
                case 'max':
                    $this->$name = (float) $value;
                    break;

                default:
                    parent::__set($name, $value);
            }
        }

        /**
         * Method to attach a Form object to the field.
         *
         * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
         * @param   mixed              $value    The form field value to validate.
         * @param   string             $group    The field name group control value. This acts as an array container for the field.
         *                                       For example if the field has name="foo" and the group value is set to "bar" then the
         *                                       full field name would end up being "bar[foo]".
         *
         * @return  boolean  True on success.
         *
         * @see     FormField::setup()
         */
        public function setup(\SimpleXMLElement $element, $value, $group = null)
        {
            $return = parent::setup($element, $value, $group);

            if ($return)
            {
                // It is better not to force any default limits if none is specified
                $this->max  = isset($this->element['max']) ? (float) $this->element['max'] : null;
                $this->min  = isset($this->element['min']) ? (float) $this->element['min'] : null;
                $this->step = isset($this->element['step']) ? (float) $this->element['step'] : 1;
            }

            return $return;
        }

        /**
         * Method to get the field input markup.
         *
         * @return  string  The field input markup.
         *
         */
        protected function getInput()
        {
            // Trim the trailing line in the layout file
            return rtrim($this->getRenderer($this->layout)->render($this->getLayoutData()), PHP_EOL);
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
            $extraData = array(
                'max'   => $this->max,
                'min'   => $this->min,
                'step'  => $this->step,
                'value' => $this->value,
            );

            return array_merge($data, $extraData);
        }
    }
}
