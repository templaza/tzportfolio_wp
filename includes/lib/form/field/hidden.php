<?php

namespace tp\lib\Form\Field;

// no direct access
defined('ABSPATH') or exit;

use tp\lib\Form\FormField;
use tp\lib\HTML\HTMLHelper;
use tp\lib\Language\TPLanguage;

if ( ! class_exists( 'tp\lib\Form\Field\HiddenField' ) ) {
    /**
     * Form Field class for the Joomla Platform.
     * Provides a hidden field
     *
     * @link   http://www.w3.org/TR/html-markup/input.hidden.html#input.hidden
     */

    class HiddenField extends FormField
    {
        /**
         * The form field type.
         *
         * @var    string
         */
        protected $type = 'Hidden';

        /**
         * Name of the layout being used to render the field
         *
         * @var    string
         */
        protected $layout = 'hidden';

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
            return parent::getLayoutData();
        }
    }
}