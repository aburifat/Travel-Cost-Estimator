<?php
/*
Plugin Name: Travel Cost Estimator(Custom Plugin)
Description: Estimate travel cost based on user choice
Version: 1.0.0
Author: Abu Rifat Muhammed Al Hasib
Author URI: https://aburifat.com
Plugin URI: https://aburifat.com
*/

include_once(plugin_dir_path(__FILE__) . 'db/db-check.php');
include_once(plugin_dir_path(__FILE__) . 'admin/menu-page.php');

register_activation_hook(__FILE__, 'eg_tce_activation');

function eg_tce_activation(){
	eg_tce_db_check();
}




?>