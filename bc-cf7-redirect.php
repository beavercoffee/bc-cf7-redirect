<?php
/*
Author: Beaver Coffee
Author URI: https://beaver.coffee
Description: Redirect after wpcf7reset DOM event.
Domain Path:
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Network: true
Plugin Name: BC CF7 Redirect
Plugin URI: https://github.com/beavercoffee/bc-cf7-redirect
Requires at least: 5.7
Requires PHP: 5.6
Text Domain: bc-cf7-redirect
Version: 1.7.9
*/

if(defined('ABSPATH')){
    require_once(plugin_dir_path(__FILE__) . 'classes/class-bc-cf7-redirect.php');
    BC_CF7_Redirect::get_instance(__FILE__);
}
