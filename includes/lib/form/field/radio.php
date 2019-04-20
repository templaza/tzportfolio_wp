<?php

namespace tp\lib\Form\Field;

// no direct access
defined('ABSPATH') or exit;

use tp\lib\Form\FormHelper;

FormHelper::loadFieldClass('list');

if ( ! class_exists( 'tp\lib\Form\Field\RadioField' ) ) {
    /**
     * Form Field class for the Joomla Platform.
     * Provides radio button inputs
     *
     * @link   http://www.w3.org/TR/html-markup/command.radio.html#command.radio
     */
    class RadioField extends ListField
    {
        /**
         * The form field type.
         *
         * @var    string
         */
        protected $type = 'Radio';

        /**
         * Name of the layout being used to render the field
         *
         * @var    string
         */
        protected $layout = 'radio';

        /**
         * Method to get the radio button field input markup.
         *
         * @return  string  The field input markup.
         *
         */
        protected function getInput()
        {
            if (empty($this->layout))
            {
                throw new \UnexpectedValueException(sprintf('%s has no layout assigned.', $this->name));
            }

            return $this->getRenderer($this->layout)->render($this->getLayoutData());
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

            $extraData = array(
                'options' => $this->getOptions(),
                'value'   => (string) $this->value,
            );

            return array_merge($data, $extraData);
        }
    }
}
