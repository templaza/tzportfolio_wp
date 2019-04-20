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

namespace tp\lib\Form;

// no direct access
defined('ABSPATH') or exit;

use tp\lib\Utilities\Path;
use tp\lib\Form\Field;
use tp\lib\Utilities\String\Normalise;
use tp\lib\Utilities\String\StringHelper;

if (!class_exists('tp\lib\Form\FormHelper')) {

    /**
     * Form's helper class.
     * Provides a storage for filesystem's paths where Form's entities reside and methods for creating those entities.
     * Also stores objects with entities' prototypes for further reusing.
     *
     */
    class FormHelper
    {
        /**
         * Array with paths where entities(field, rule, form) can be found.
         *
         * Array's structure:
         *
         * paths:
         * {ENTITY_NAME}:
         * - /path/1
         * - /path/2
         *
         * @var    array
         */
        protected static $paths;

        /**
         * The class namespaces.
         *
         * @var   string
         */
        protected static $prefixes = array('field' => array(), 'form' => array(), 'rule' => array(), 'filter' => array());

        /**
         * Static array of Form's entity objects for re-use.
         * Prototypes for all fields and rules are here.
         *
         * Array's structure:
         * entities:
         * {ENTITY_NAME}:
         * {KEY}: {OBJECT}
         *
         * @var    array
         */
        protected static $entities = array('field' => array(), 'form' => array(), 'rule' => array(), 'filter' => array());

        /**
         * Method to load a form field object given a type.
         *
         * @param   string   $type  The field type.
         * @param   boolean  $new   Flag to toggle whether we should get a new instance of the object.
         *
         * @return  FormField|boolean  FormField object on success, false otherwise.
         *
         *
         */
        public static function loadFieldType($type, $new = true)
        {
            return self::loadType('field', $type, $new);
        }

        /**
         * Method to load a form rule object given a type.
         *
         * @param   string   $type  The rule type.
         * @param   boolean  $new   Flag to toggle whether we should get a new instance of the object.
         *
         * @return  FormRule|boolean  FormRule object on success, false otherwise.
         *
         */
        public static function loadRuleType($type, $new = true)
        {
            return self::loadType('rule', $type, $new);
        }

        /**
         * Method to load a form filter object given a type.
         *
         * @param   string   $type  The rule type.
         * @param   boolean  $new   Flag to toggle whether we should get a new instance of the object.
         *
         * @return  FormFilter|boolean  FormRule object on success, false otherwise.
         *
         */
        public static function loadFilterType($type, $new = true)
        {
            return self::loadType('filter', $type, $new);
        }

        /**
         * Method to load a form entity object given a type.
         * Each type is loaded only once and then used as a prototype for other objects of same type.
         * Please, use this method only with those entities which support types (forms don't support them).
         *
         * @param   string   $entity  The entity.
         * @param   string   $type    The entity type.
         * @param   boolean  $new     Flag to toggle whether we should get a new instance of the object.
         *
         * @return  mixed  Entity object on success, false otherwise.
         *
         */
        protected static function loadType($entity, $type, $new = true)
        {
            // Reference to an array with current entity's type instances
            $types = &self::$entities[$entity];

            $key = md5($type);

            // Return an entity object if it already exists and we don't need a new one.
            if (isset($types[$key]) && $new === false)
            {
                return $types[$key];
            }

            $class = self::loadClass($entity, $type);

            if ($class === false)
            {
                return false;
            }

            // Instantiate a new type object.
            $types[$key] = new $class;

            return $types[$key];
        }

        /**
         * Attempt to import the FormField class file if it isn't already imported.
         * You can use this method outside of Form for loading a field for inheritance or composition.
         *
         * @param   string  $type  Type of a field whose class should be loaded.
         *
         * @return  string|boolean  Class name on success or false otherwise.
         *
         */
        public static function loadFieldClass($type)
        {
            return self::loadClass('field', $type);
        }

        /**
         * Attempt to import the FormRule class file if it isn't already imported.
         * You can use this method outside of Form for loading a rule for inheritance or composition.
         *
         * @param   string  $type  Type of a rule whose class should be loaded.
         *
         * @return  string|boolean  Class name on success or false otherwise.
         *
         */
        public static function loadRuleClass($type)
        {
            return self::loadClass('rule', $type);
        }

        /**
         * Attempt to import the FormFilter class file if it isn't already imported.
         * You can use this method outside of Form for loading a filter for inheritance or composition.
         *
         * @param   string  $type  Type of a filter whose class should be loaded.
         *
         * @return  string|boolean  Class name on success or false otherwise.
         *
         */
        public static function loadFilterClass($type)
        {
            return self::loadClass('filter', $type);
        }

        /**
         * Load a class for one of the form's entities of a particular type.
         * Currently, it makes sense to use this method for the "field" and "rule" entities
         * (but you can support more entities in your subclass).
         *
         * @param   string  $entity  One of the form entities (field or rule).
         * @param   string  $type    Type of an entity.
         *
         * @return  string|boolean  Class name on success or false otherwise.
         *
         */
        protected static function loadClass($entity, $type)
        {
            $_type  = $type;

            // Get the field search path array.
            $paths = self::addPath($entity);

            // If the type is complex, add the base type to the paths.
            if ($pos = strpos($_type, '_'))
            {
                // Add the complex type prefix to the paths.
                for ($i = 0, $n = count($paths); $i < $n; $i++)
                {
                    // Derive the new path.
                    $path = $paths[$i] . '/' . strtolower(substr($_type, 0, $pos));

                    // If the path does not exist, add it.
                    if (!in_array($path, $paths))
                    {
                        $paths[] = $path;
                    }
                }

                // Break off the end of the complex type.
                $_type = substr($_type, $pos + 1);
            }

            // Try to find the class file.
            $_type = strtolower($_type) . '.php';

            foreach ($paths as $path)
            {
                $file = Path::find($path, $_type);

                if (!$file)
                {
                    continue;
                }

                require_once $file;

            }

            // Check if there is a class in the registered namespaces
            foreach (self::addPrefix($entity) as $prefix)
            {
                // Treat underscores as namespace
                $name = Normalise::toSpaceSeparated($type);
                $name = str_ireplace(' ', '\\', ucwords($name));

                $subPrefix = '';

                if (strpos($name, '.'))
                {
                    list($subPrefix, $name) = explode('.', $name);
                    $subPrefix = ucfirst($subPrefix) . '\\';
                }

                // Compile the classname
                $class = rtrim($prefix, '\\') . '\\' . $subPrefix . ucfirst($name) . ucfirst($entity);

                // Check if the class exists
                if (class_exists($class))
                {
                    return $class;
                }
            }

            $prefix = 'J';

            if (strpos($type, '.'))
            {
                list($prefix, $type) = explode('.', $type);
            }

            $class = StringHelper::ucfirst($prefix, '_') . 'Form' . StringHelper::ucfirst($entity, '_') . StringHelper::ucfirst($type, '_');

            if (class_exists($class))
            {
                return $class;
            }

            // Get the field search path array.
            $paths = self::addPath($entity);

            // If the type is complex, add the base type to the paths.
            if ($pos = strpos($type, '_'))
            {
                // Add the complex type prefix to the paths.
                for ($i = 0, $n = count($paths); $i < $n; $i++)
                {
                    // Derive the new path.
                    $path = $paths[$i] . '/' . strtolower(substr($type, 0, $pos));

                    // If the path does not exist, add it.
                    if (!in_array($path, $paths))
                    {
                        $paths[] = $path;
                    }
                }

                // Break off the end of the complex type.
                $type = substr($type, $pos + 1);
            }

            // Try to find the class file.
            $type = strtolower($type) . '.php';

            foreach ($paths as $path)
            {
                $file = Path::find($path, $type);

                if (!$file)
                {
                    continue;
                }

                require_once $file;

                if (class_exists($class))
                {
                    break;
                }
            }

            // Check for all if the class exists.
            return class_exists($class) ? $class : false;
        }

        /**
         * Method to add a path to the list of field include paths.
         *
         * @param   mixed  $new  A path or array of paths to add.
         *
         * @return  array  The list of paths that have been added.
         *
         */
        public static function addFieldPath($new = null)
        {
            return self::addPath('field', $new);
        }

        /**
         * Method to add a path to the list of form include paths.
         *
         * @param   mixed  $new  A path or array of paths to add.
         *
         * @return  array  The list of paths that have been added.
         *
         */
        public static function addFormPath($new = null)
        {
            return self::addPath('form', $new);
        }

        /**
         * Method to add a path to the list of rule include paths.
         *
         * @param   mixed  $new  A path or array of paths to add.
         *
         * @return  array  The list of paths that have been added.
         *
         */
        public static function addRulePath($new = null)
        {
            return self::addPath('rule', $new);
        }

        /**
         * Method to add a path to the list of filter include paths.
         *
         * @param   mixed  $new  A path or array of paths to add.
         *
         * @return  array  The list of paths that have been added.
         *
         */
        public static function addFilterPath($new = null)
        {
            return self::addPath('filter', $new);
        }

        /**
         * Method to add a path to the list of include paths for one of the form's entities.
         * Currently supported entities: field, rule and form. You are free to support your own in a subclass.
         *
         * @param   string  $entity  Form's entity name for which paths will be added.
         * @param   mixed   $new     A path or array of paths to add.
         *
         * @return  array  The list of paths that have been added.
         *
         */
        protected static function addPath($entity, $new = null)
        {
            if (!isset(self::$paths[$entity]))
            {
                self::$paths[$entity] = [];
            }

            // Reference to an array with paths for current entity
            $paths = &self::$paths[$entity];

            // Force the new path(s) to an array.
            settype($new, 'array');

            // Add the new paths to the stack if not already there.
            foreach ($new as $path)
            {
                if (!in_array($path, $paths))
                {
                    array_unshift($paths, trim($path));
                }

                if (!is_dir($path))
                {
                    array_unshift($paths, trim($path));
                }
            }

            return $paths;
        }

        /**
         * Method to add a namespace prefix to the list of field lookups.
         *
         * @param   mixed  $new  A namespaces or array of namespaces to add.
         *
         * @return  array  The list of namespaces that have been added.
         *
         */
        public static function addFieldPrefix($new = null)
        {
            return self::addPrefix('field', $new);
        }

        /**
         * Method to add a namespace to the list of form lookups.
         *
         * @param   mixed  $new  A namespace or array of namespaces to add.
         *
         * @return  array  The list of namespaces that have been added.
         *
         */
        public static function addFormPrefix($new = null)
        {
            return self::addPrefix('form', $new);
        }

        /**
         * Method to add a namespace to the list of rule lookups.
         *
         * @param   mixed  $new  A namespace or array of namespaces to add.
         *
         * @return  array  The list of namespaces that have been added.
         *
         */
        public static function addRulePrefix($new = null)
        {
            return self::addPrefix('rule', $new);
        }

        /**
         * Method to add a namespace to the list of filter lookups.
         *
         * @param   mixed  $new  A namespace or array of namespaces to add.
         *
         * @return  array  The list of namespaces that have been added.
         *
         */
        public static function addFilterPrefix($new = null)
        {
            return self::addPrefix('filter', $new);
        }

        /**
         * Method to add a namespace to the list of namespaces for one of the form's entities.
         * Currently supported entities: field, rule and form. You are free to support your own in a subclass.
         *
         * @param   string  $entity  Form's entity name for which paths will be added.
         * @param   mixed   $new     A namespace or array of namespaces to add.
         *
         * @return  array  The list of namespaces that have been added.
         *
         */
        protected static function addPrefix($entity, $new = null)
        {
            // Reference to an array with namespaces for current entity
            $prefixes = &self::$prefixes[$entity];

            // Add the default entity's search namespace if not set.
            if (empty($prefixes))
            {
                $prefixes[] = __NAMESPACE__ . '\\' . ucfirst($entity);
            }

            // Force the new namespace(s) to an array.
            settype($new, 'array');

            // Add the new paths to the stack if not already there.
            foreach ($new as $prefix)
            {
                $prefix = trim($prefix);

                if (in_array($prefix, $prefixes))
                {
                    continue;
                }

                array_unshift($prefixes, $prefix);
            }

            return $prefixes;
        }

        /**
         * Parse the show on conditions
         *
         * @param   string  $showOn       Show on conditions.
         * @param   string  $formControl  Form name.
         * @param   string  $group        The dot-separated form group path.
         *
         * @return  array   Array with show on conditions.
         *
         */
        public static function parseShowOnConditions($showOn, $formControl = null, $group = null)
        {
            // Process the showon data.
            if (!$showOn)
            {
                return array();
            }

            $formPath = $formControl ?: '';

            if ($group)
            {
                $groups = explode('.', $group);

                // An empty formControl leads to invalid shown property
                // Use the 1st part of the group instead to avoid.
                if (empty($formPath) && isset($groups[0]))
                {
                    $formPath = $groups[0];
                    array_shift($groups);
                }

                foreach ($groups as $group)
                {
                    $formPath .= '[' . $group . ']';
                }
            }

            $showOnData  = array();
            $showOnParts = preg_split('#(\[AND\]|\[OR\])#', $showOn, -1, PREG_SPLIT_DELIM_CAPTURE);
            $op          = '';

            foreach ($showOnParts as $showOnPart)
            {
                if (($showOnPart === '[AND]') || $showOnPart === '[OR]')
                {
                    $op = trim($showOnPart, '[]');
                    continue;
                }

                $compareEqual     = strpos($showOnPart, '!:') === false;
                $showOnPartBlocks = explode(($compareEqual ? ':' : '!:'), $showOnPart, 2);

                $showOnData[] = array(
                    'field'  => $formPath ? $formPath . '[' . $showOnPartBlocks[0] . ']' : $showOnPartBlocks[0],
                    'values' => explode(',', $showOnPartBlocks[1]),
                    'sign'   => $compareEqual === true ? '=' : '!=',
                    'op'     => $op,
                );

                if ($op !== '')
                {
                    $op = '';
                }
            }

            return $showOnData;
        }
    }

}