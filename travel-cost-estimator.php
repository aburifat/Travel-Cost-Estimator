<?php
/*
Plugin Name: Travel Cost Estimator(Custom Plugin)
Description: Estimate travel cost based on user choice
Version: 1.0.0
Author: Abu Rifat Muhammed Al Hasib
Author URI: https://aburifat.com
Plugin URI: https://aburifat.com
*/

include_once(plugin_dir_path(__FILE__) . 'database/database-manager.php');
include_once(plugin_dir_path(__FILE__) . 'admin/admin-functions.php');
include_once(plugin_dir_path(__FILE__) . 'public/public-functions.php');

register_activation_hook(__FILE__, 'eg_tce_activation');

function eg_tce_activation(){
	eg_tce_db_check();
}

?>