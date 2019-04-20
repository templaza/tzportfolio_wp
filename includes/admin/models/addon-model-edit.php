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

namespace tp\admin\models;

use tp\lib\Form\Form;
use tp\lib\Language\TPLanguage;
use tp\lib\Utilities\ArrayHelper;
use tp\lib\Utilities\File;
//use tp\lib\Utilities\Path;
use \tp\admin\Admin_Forms;
use tp\lib\Utilities\AddOnHelper;
use tp\lib\Utilities\Path;
use tp\lib\Utilities\Registry;

defined( 'ABSPATH' ) or exit; // Exit if accessed directly

if ( ! class_exists( 'tp\admin\models\AddOn_Model_Edit' ) ) {
	class AddOn_Model_Edit extends Admin_Forms {
	    protected $option_prefix    = TP_PLUGIN_ADDON_OPTION_PREFIX;

	    public function get_form($data = array(), $loadData = true){

		    $id = $_GET['id'];

		    if(!$id){
		        return false;
            }


            $plugin_folder  = WP_PLUGIN_DIR.'/tz-portfolio/addons';
		    $addonName      = File::getName($id);
		    $addonName      = File::stripExt($addonName);
		    $textDomain     = AddOnHelper::getTextDomainById($id);

		    if(!count($data)){
		        $options   = get_option($this -> option_prefix.$addonName);
		        $data['params'] = $options;
            }

            Form::addFormPath($plugin_folder.'/'.$addonName);
            Form::addFieldPath(Path::clean(TP_PLUGIN_LIBRARY_PATH.'/form/field'));

            // Load text domain language to queue
            TPLanguage::loadTextDomain($textDomain, AddOnHelper::getTextDomainPathById($id));

		    $form   = Form::getInstance('tz-portfolio.addons.'.$addonName, $addonName,
                array('control' => 'tpform', 'load_data' => $loadData, 'textDomain' => $textDomain),
                true, '/extension/config');

		    if($loadData){
		        $form -> bind($data);
            }

	        return $form;
        }

        public function validate($form, $data, $group = null)
        {

            // Filter and validate the form data.
            $data = $form->filter($data);
            $return = $form->validate($data, $group);

            // Check for an error.
            if ($return instanceof \Exception)
            {
//                $this->setError($return->getMessage());

                return false;
            }

            // Check the validation results.
            if ($return === false)
            {
//                // Get the validation messages from the form.
//                foreach ($form->getErrors() as $message)
//                {
//                    $this->setError($message);
//                }

                return false;
            }

            return $data;
        }

        public function save($data){
	        $id = $_GET['id'];
	        $addon  = AddOnHelper::getAddOnById($id);

	        update_option($this -> option_prefix.$addon['Element'], $data['params']);

	        return true;
        }

        public function get_item($id){
	        return AddOnHelper::getAddOnById($id);
        }
	}
}
