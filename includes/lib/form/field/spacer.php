<?php

namespace tp\lib\Form\Field;

// no direct access
defined('ABSPATH') or exit;

use tp\lib\Form\FormField;
use tp\lib\HTML\HTMLHelper;
use tp\lib\Language\TPLanguage;

//use Joomla\CMS\Form\FormField;
//use Joomla\CMS\HTML\HTMLHelper;
//use Joomla\CMS\Language\Text;

if ( ! class_exists( 'tp\lib\Form\Field\SpacerField' ) ) {
    /**
     * Form Field class for the Joomla Platform.
     * Provides spacer markup to be used in form layouts.
     *
     */
    class SpacerField extends FormField
    {
        /**
         * The form field type.
         *
         * @var    string
         */
        protected $type = 'Spacer';

        /**
         * Method to get the field input markup for a spacer.
         * The spacer does not have accept input.
         *
         * @return  string  The field input markup.
         *
         */
        protected function getInput()
        {
            return ' ';
        }

        /**
         * Method to get the field label markup for a spacer.
         * Use the label text or name from the XML element as the spacer or
         * Use a hr="true" to automatically generate plain hr markup
         *
         * @return  string  The field label markup.
         *
         */
        protected function getLabel()
        {
            $html = array();
            $class = !empty($this->class) ? ' class="' . $this->class . '"' : '';

//            $html[] = '<span class="spacer">';
//            $html[] = '<span class="before"></span>';
//            $html[] = '<span' . $class . '>';

            if ((string)$this->element['hr'] == 'true') {
                $html[] = '<hr' . $class . '>';
            } else {

//                $html[] = '<h2 class="spacer">';
//                $html[] = '<span class="before"></span>';
//                $html[] = '<span' . $class . '>';

                $label = '';

                // Get the label text from the XML element, defaulting to the element name.
                $text = $this->element['label'] ? (string)$this->element['label'] : (string)$this->element['name'];
                $text = $this->translateLabel ? TPLanguage::_($text) : $text;

                // Build the class for the label.
                $class = !empty($this->description) ? 'hasTooltip' : '';
                $class = $this->required == true ? $class . ' required' : $class;

                // Add the opening label tag and main attributes attributes.
                $label .= '<h2 id="' . $this->id . '-lbl" class="' . $class . '"';

                // If a description is specified, use it to build a tooltip.
                if (!empty($this->description)) {
                    $label .= ' title="' . HTMLHelper::_('tooltipText', trim($text, ':'), TPLanguage::_($this->description), 0) . '"';
                }

                // Add the label text and closing tag.
                $label .= '>' . $text . '</h2>';
                $html[] = $label;

//                $html[] = '</span>';
//                $html[] = '<span class="after"></span>';
//                $html[] = '</h2>';
            }

//            $html[] = '</span>';
//            $html[] = '<span class="after"></span>';
//            $html[] = '</h2>';

            return implode('', $html);
        }

        /**
         * Method to get the field title.
         *
         * @return  string  The field title.
         *
         */
        protected function getTitle()
        {
            return $this->getLabel();
        }

        /**
         * Method to get a control group with label and input.
         *
         * @param   array $options Options to be passed into the rendering of the field
         *
         * @return  string  A string containing the html for the control group
         *
         */
        public function renderField($options = array())
        {
            $options['class'] = empty($options['class']) ? 'field-spacer' : $options['class'] . ' field-spacer';

            return parent::renderField($options);
        }
    }
}
