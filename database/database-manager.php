<?php
function eg_tce_DB_check(){
	global $wpdb;
	if(eg_tce_table_not_exists("eg_tce_fields")){
		$table = $wpdb->prefix . "eg_tce_fields";
		$wpdb->query($wpdb->prepare("CREATE TABLE " . $table . "(
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name varchar(50),
			serial_no int,
			feature_image varchar(255),
			notice text,
			notice_type int,
			value_type int,
			is_required int,
			PRIMARY KEY (id)
		) "));
	}
	if(eg_tce_table_not_exists("eg_tce_values")){
		$table = $wpdb->prefix . "eg_tce_values";
		$table_fields = $wpdb->prefix . "eg_tce_fields";
		$wpdb->query($wpdb->prepare("CREATE TABLE " . $table . "(
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			field_id mediumint(9) NOT NULL,
			text text,
			image varchar(255),
			price int,
			PRIMARY KEY (id),
			FOREIGN KEY (field_id) REFERENCES " . $table_fields . "(id)
		) "));
	}
	if(eg_tce_table_not_exists("eg_tce_user_info")){
		$table = $wpdb->prefix . "eg_tce_user_info";
		$wpdb->query($wpdb->prepare("CREATE TABLE " . $table . "(
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name varchar(255),
			password varchar(50),
			email varchar(50),
			gender varchar(10),
			year int,
			contact_no varchar(20),
			PRIMARY KEY (id)
		) "));
	}
	if(eg_tce_table_not_exists("eg_tce_user_estimator")){
		$table = $wpdb->prefix . "eg_tce_user_estimator";
		$table_users = $wpdb->prefix . "eg_tce_user_info";
		$wpdb->query($wpdb->prepare("CREATE TABLE " . $table . "(
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			user_id mediumint(9) NOT NULL,
			text text,
			price int,
			PRIMARY KEY (id),
			FOREIGN KEY (user_id) REFERENCES " . $table_users . "(id)
		) "));
	}
}

function eg_tce_table_not_exists($table){
	global $wpdb;
	$table = $wpdb->prefix . $table;
	$query = "SHOW TABLE LIKE '".$table."'";
	$result = $wpdb->get_var($query);
	if($result == $table){
		return false;
	}else return true;
}

?>