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

defined( 'ABSPATH' ) || exit;

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
$plugin_data = get_plugin_data( __FILE__ );

//if(!defined('_JEXEC')) {
//    define('_JEXEC', ABSPATH);
//}

define( 'tp_url', plugin_dir_url( __FILE__ ) );
define( 'tp_path', plugin_dir_path( __FILE__ ) );
define( 'tp_plugin', plugin_basename( __FILE__ ) );
define( 'tp_version', $plugin_data['Version'] );
define( 'tp_plugin_name', $plugin_data['Name'] );

if(!defined('TP_PLUGIN_ADDON_PATH')) {
    define( 'TP_PLUGIN_ADDON_PATH', tp_path.'addons' );
}
if(!defined('TP_PLUGIN_LIBRARY_PATH')) {
    define( 'TP_PLUGIN_LIBRARY_PATH', tp_path.'includes/lib' );
}
if(!defined('TP_PLUGIN_LANGUAGE_PATH')) {
    define( 'TP_PLUGIN_LANGUAGE_PATH', tp_path.'languages' );
}
if(!defined('TP_PLUGIN_TEXTDOMAIN')) {
    define( 'TP_PLUGIN_TEXTDOMAIN', dirname(tp_plugin));
}
if(!defined('TP_PLUGIN_ADDON_OPTION_PREFIX')) {
    define( 'TP_PLUGIN_ADDON_OPTION_PREFIX', '_tp_addon_');
}

require_once 'includes/lib/framework.php';
require_once 'includes/init.php';