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

namespace tp\lib\Model;

// no direct access
defined('ABSPATH') or exit;

use tp\lib\Form\Form;

if (!class_exists('tp\lib\Model\FormModel')) {
    abstract class FormModel
    {
        /**
         * Array of form objects.
         *
         * @var    \JForm[]
         */
        protected $_forms = array();

        /**
         * Maps events to plugin groups.
         *
         * @var    array
         */
        protected $events_map = null;

        /**
         * Constructor.
         *
         * @param   array  $config  An optional associative array of configuration settings.
         *
         * @see     \JModelLegacy
         */
        public function __construct($config = array())
        {
            $config['events_map'] = isset($config['events_map']) ? $config['events_map'] : array();

            $this->events_map = array_merge(
                array(
                    'validate' => 'content',
                ),
                $config['events_map']
            );

            parent::__construct($config);
        }

//        /**
//         * Method to checkin a row.
//         *
//         * @param   integer  $pk  The numeric id of the primary key.
//         *
//         * @return  boolean  False on failure or error, true otherwise.
//         *
//         * @since   1.6
//         */
//        public function checkin($pk = null)
//        {
//            // Only attempt to check the row in if it exists.
//            if ($pk)
//            {
//                $user = \JFactory::getUser();
//
//                // Get an instance of the row to checkin.
//                $table = $this->getTable();
//
//                if (!$table->load($pk))
//                {
//                    $this->setError($table->getError());
//
//                    return false;
//                }
//
//                $checkedOutField = $table->getColumnAlias('checked_out');
//                $checkedOutTimeField = $table->getColumnAlias('checked_out_time');
//
//                // If there is no checked_out or checked_out_time field, just return true.
//                if (!property_exists($table, $checkedOutField) || !property_exists($table, $checkedOutTimeField))
//                {
//                    return true;
//                }
//
//                // Check if this is the user having previously checked out the row.
//                if ($table->{$checkedOutField} > 0 && $table->{$checkedOutField} != $user->get('id') && !$user->authorise('core.admin', 'com_checkin'))
//                {
//                    $this->setError(\JText::_('JLIB_APPLICATION_ERROR_CHECKIN_USER_MISMATCH'));
//
//                    return false;
//                }
//
//                // Attempt to check the row in.
//                if (!$table->checkIn($pk))
//                {
//                    $this->setError($table->getError());
//
//                    return false;
//                }
//            }
//
//            return true;
//        }

//        /**
//         * Method to check-out a row for editing.
//         *
//         * @param   integer  $pk  The numeric id of the primary key.
//         *
//         * @return  boolean  False on failure or error, true otherwise.
//         *
//         * @since   1.6
//         */
//        public function checkout($pk = null)
//        {
//            // Only attempt to check the row in if it exists.
//            if ($pk)
//            {
//                // Get an instance of the row to checkout.
//                $table = $this->getTable();
//
//                if (!$table->load($pk))
//                {
//                    $this->setError($table->getError());
//
//                    return false;
//                }
//
//                $checkedOutField = $table->getColumnAlias('checked_out');
//                $checkedOutTimeField = $table->getColumnAlias('checked_out_time');
//
//                // If there is no checked_out or checked_out_time field, just return true.
//                if (!property_exists($table, $checkedOutField) || !property_exists($table, $checkedOutTimeField))
//                {
//                    return true;
//                }
//
//                $user = \JFactory::getUser();
//
//                // Check if this is the user having previously checked out the row.
//                if ($table->{$checkedOutField} > 0 && $table->{$checkedOutField} != $user->get('id'))
//                {
//                    $this->setError(\JText::_('JLIB_APPLICATION_ERROR_CHECKOUT_USER_MISMATCH'));
//
//                    return false;
//                }
//
//                // Attempt to check the row out.
//                if (!$table->checkOut($user->get('id'), $pk))
//                {
//                    $this->setError($table->getError());
//
//                    return false;
//                }
//            }
//
//            return true;
//        }

        /**
         * Abstract method for getting the form from the model.
         *
         * @param   array    $data      Data for the form.
         * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
         *
         * @return  \JForm|boolean  A \JForm object on success, false on failure
         *
         */
        abstract public function getForm($data = array(), $loadData = true);

        /**
         * Method to get a form object.
         *
         * @param   string   $name     The name of the form.
         * @param   string   $source   The form source. Can be XML string if file flag is set to false.
         * @param   array    $options  Optional array of options for the form creation.
         * @param   boolean  $clear    Optional argument to force load a new form.
         * @param   string   $xpath    An optional xpath to search for the fields.
         *
         * @return  \JForm|boolean  \JForm object on success, false on error.
         *
         * @see     \JForm
         */
        protected function loadForm($name, $source = null, $options = array(), $clear = false, $xpath = false)
        {
            // Handle the optional arguments.
            $options['control'] = ArrayHelper::getValue((array) $options, 'control', false);

            // Create a signature hash. But make sure, that loading the data does not create a new instance
            $sigoptions = $options;

            if (isset($sigoptions['load_data']))
            {
                unset($sigoptions['load_data']);
            }

            $hash = md5($source . serialize($sigoptions));

            // Check if we can use a previously loaded form.
            if (!$clear && isset($this->_forms[$hash]))
            {
                return $this->_forms[$hash];
            }

            // Get the form.
//            Form::addFormPath()
//            Form::addFormPath(JPATH_COMPONENT . '/models/forms');
//            Form::addFieldPath(JPATH_COMPONENT . '/models/fields');
//            Form::addFormPath(JPATH_COMPONENT . '/model/form');
//            Form::addFieldPath(JPATH_COMPONENT . '/model/field');

            try
            {
                $form = Form::getInstance($name, $source, $options, false, $xpath);

                if (isset($options['load_data']) && $options['load_data'])
                {
                    // Get the data for the form.
                    $data = $this->loadFormData();
                }
                else
                {
                    $data = array();
                }

                // Allow for additional modification of the form, and events to be triggered.
                // We pass the data because plugins may require it.
                $this->preprocessForm($form, $data);

                // Load the data into the form after the plugins have operated.
                $form->bind($data);
            }
            catch (\Exception $e)
            {
                $this->setError($e->getMessage());

                return false;
            }

            // Store the form for later.
            $this->_forms[$hash] = $form;

            return $form;
        }

        /**
         * Method to get the data that should be injected in the form.
         *
         * @return  array  The default data is an empty array.
         *
         */
        protected function loadFormData()
        {
            return array();
        }

        /**
         * Method to allow derived classes to preprocess the data.
         *
         * @param   string  $context  The context identifier.
         * @param   mixed   &$data    The data to be processed. It gets altered directly.
         * @param   string  $group    The name of the plugin group to import (defaults to "content").
         *
         * @return  void
         *
         */
        protected function preprocessData($context, &$data, $group = 'content')
        {
            // Get the dispatcher and load the users plugins.
            $dispatcher = \JEventDispatcher::getInstance();
            \JPluginHelper::importPlugin($group);

            // Trigger the data preparation event.
            $results = $dispatcher->trigger('onContentPrepareData', array($context, &$data));

            // Check for errors encountered while preparing the data.
            if (count($results) > 0 && in_array(false, $results, true))
            {
                $this->setError($dispatcher->getError());
            }
        }

        /**
         * Method to allow derived classes to preprocess the form.
         *
         * @param   \JForm  $form   A \JForm object.
         * @param   mixed   $data   The data expected for the form.
         * @param   string  $group  The name of the plugin group to import (defaults to "content").
         *
         * @return  void
         *
         * @see     \JFormField
         * @throws  \Exception if there is an error in the form event.
         */
        protected function preprocessForm(\JForm $form, $data, $group = 'content')
        {
            // Import the appropriate plugin group.
            \JPluginHelper::importPlugin($group);

            // Get the dispatcher.
            $dispatcher = \JEventDispatcher::getInstance();

            // Trigger the form preparation event.
            $results = $dispatcher->trigger('onContentPrepareForm', array($form, $data));

            // Check for errors encountered while preparing the form.
            if (count($results) && in_array(false, $results, true))
            {
                // Get the last error.
                $error = $dispatcher->getError();

                if (!($error instanceof \Exception))
                {
                    throw new \Exception($error);
                }
            }
        }

        /**
         * Method to validate the form data.
         *
         * @param   \JForm  $form   The form to validate against.
         * @param   array   $data   The data to validate.
         * @param   string  $group  The name of the field group to validate.
         *
         * @return  array|boolean  Array of filtered data if valid, false otherwise.
         *
         * @see     \JFormRule
         * @see     \JFilterInput
         */
        public function validate($form, $data, $group = null)
        {
            // Include the plugins for the delete events.
            \JPluginHelper::importPlugin($this->events_map['validate']);

            $dispatcher = \JEventDispatcher::getInstance();
            $dispatcher->trigger('onUserBeforeDataValidation', array($form, &$data));

            // Filter and validate the form data.
            $data = $form->filter($data);
            $return = $form->validate($data, $group);

            // Check for an error.
            if ($return instanceof \Exception)
            {
                $this->setError($return->getMessage());

                return false;
            }

            // Check the validation results.
            if ($return === false)
            {
                // Get the validation messages from the form.
                foreach ($form->getErrors() as $message)
                {
                    $this->setError($message);
                }

                return false;
            }

            // Tags B/C break at 3.1.2
            if (!isset($data['tags']) && isset($data['metadata']['tags']))
            {
                $data['tags'] = $data['metadata']['tags'];
            }

            return $data;
        }
    }

}