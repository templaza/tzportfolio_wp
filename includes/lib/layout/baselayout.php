<?php
/*
Plugin Name: TZ Portfolio
Plugin URI: https://www.tzportfolio.com/
Description: All you need for a Portfolio here. TZ Portfolio+ is an open source advanced portfolio plugin for WordPress
Version: 1.0.0
Author: TemPlaza, Sonny
Author URI: https://www.tzportfolio.com/
Text Domain: tz-portfolio
Domain Path: /languages
*/
namespace tp\lib\Layout;

// no direct access
defined('ABSPATH') or exit;

use tp\lib\utilities\Registry;

if ( ! class_exists( 'tp\lib\Layout\BaseLayout' ) ) {


    class BaseLayout
    {
        /**
         * Options object
         *
         * @var    Registry
         */
        protected $options = null;

        /**
         * Data for the layout
         *
         * @var    array
         */
        protected $data = array();

        /**
         * Debug information messages
         *
         * @var    array
         */
        protected $debugMessages = array();

        /**
         * Set the options
         *
         * @param   array|Registry  $options  Array / Registry object with the options to load
         *
         * @return  BaseLayout  Instance of $this to allow chaining.
         *
         */
        public function setOptions($options = null)
        {
            // Received Registry
            if ($options instanceof Registry)
            {
                $this->options = $options;
            }
            // Received array
            elseif (is_array($options))
            {
                $this->options = new Registry($options);
            }
            else
            {
                $this->options = new Registry;
            }

            return $this;
        }

        /**
         * Get the options
         *
         * @return  Registry  Object with the options
         *
         */
        public function getOptions()
        {
            // Always return a Registry instance
            if (!($this->options instanceof Registry))
            {
                $this->resetOptions();
            }

            return $this->options;
        }

        /**
         * Function to empty all the options
         *
         * @return  BaseLayout  Instance of $this to allow chaining.
         *
         */
        public function resetOptions()
        {
            return $this->setOptions(null);
        }

        /**
         * Method to escape output.
         *
         * @param   string  $output  The output to escape.
         *
         * @return  string  The escaped output.
         *
         * @note the ENT_COMPAT flag will be replaced by ENT_QUOTES in Joomla 4.0 to also escape single quotes
         *
         */
        public function escape($output)
        {
            return htmlspecialchars($output, ENT_COMPAT, 'UTF-8');
        }

        /**
         * Get the debug messages array
         *
         * @return  array
         *
         */
        public function getDebugMessages()
        {
            return $this->debugMessages;
        }

        /**
         * Method to render the layout.
         *
         * @param   array  $displayData  Array of properties available for use inside the layout file to build the displayed output
         *
         * @return  string  The necessary HTML to display the layout
         *
         */
        public function render($displayData)
        {
            // Automatically merge any previously data set if $displayData is an array
            if (is_array($displayData))
            {
                $displayData = array_merge($this->data, $displayData);
            }

            return '';
        }

        /**
         * Render the list of debug messages
         *
         * @return  string  Output text/HTML code
         *
         */
        public function renderDebugMessages()
        {
            return implode($this->debugMessages, "\n");
        }

        /**
         * Add a debug message to the debug messages array
         *
         * @param   string  $message  Message to save
         *
         * @return  self
         *
         */
        public function addDebugMessage($message)
        {
            $this->debugMessages[] = $message;

            return $this;
        }

        /**
         * Clear the debug messages array
         *
         * @return  self
         *
         */
        public function clearDebugMessages()
        {
            $this->debugMessages = array();

            return $this;
        }

        /**
         * Render a layout with debug info
         *
         * @param   mixed  $data  Data passed to the layout
         *
         * @return  string
         *
         */
        public function debug($data = array())
        {
            $this->setDebug(true);

            $output = $this->render($data);

            $this->setDebug(false);

            return $output;
        }

        /**
         * Method to get the value from the data array
         *
         * @param   string  $key           Key to search for in the data array
         * @param   mixed   $defaultValue  Default value to return if the key is not set
         *
         * @return  mixed   Value from the data array | defaultValue if doesn't exist
         *
         */
        public function get($key, $defaultValue = null)
        {
            return isset($this->data[$key]) ? $this->data[$key] : $defaultValue;
        }

        /**
         * Get the data being rendered
         *
         * @return  array
         *
         */
        public function getData()
        {
            return $this->data;
        }

        /**
         * Check if debug mode is enabled
         *
         * @return  boolean
         *
         */
        public function isDebugEnabled()
        {
            return $this->getOptions()->get('debug', false) === true;
        }

        /**
         * Method to set a value in the data array. Example: $layout->set('items', $items);
         *
         * @param   string  $key    Key for the data array
         * @param   mixed   $value  Value to assign to the key
         *
         * @return  self
         *
         */
        public function set($key, $value)
        {
            $this->data[(string) $key] = $value;

            return $this;
        }

        /**
         * Set the the data passed the layout
         *
         * @param   array  $data  Array with the data for the layout
         *
         * @return  self
         *
         */
        public function setData(array $data)
        {
            $this->data = $data;

            return $this;
        }

        /**
         * Change the debug mode
         *
         * @param   boolean  $debug  Enable / Disable debug
         *
         * @return  self
         *
         */
        public function setDebug($debug)
        {
            $this->options->set('debug', (boolean) $debug);

            return $this;
        }
    }

}