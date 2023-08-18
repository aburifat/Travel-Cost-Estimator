<?php
function eg_tce_DB_check(){
	global $wpdb;
	//Fields Table
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
	//Values Table
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
	//Quote Info Table
	if(eg_tce_table_not_exists("eg_tce_quote_info")){
		$table = $wpdb->prefix . "eg_tce_quote_info";
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
	//Quote Values Table
	if(eg_tce_table_not_exists("eg_tce_quote_values")){
		$table = $wpdb->prefix . "eg_tce_quote_values";
		$table_quote_info = $wpdb->prefix . "eg_tce_quote_info";
		$table_values = $wpdb->prefix . "eg_tce_values";
		$wpdb->query($wpdb->prepare("CREATE TABLE " . $table . "(
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			quote_id mediumint(9) NOT NULL,
			value_id mediumint(9) NOT NULL,
			PRIMARY KEY (id),
			FOREIGN KEY (quote_id) REFERENCES " . $table_quote_info . "(id),
			FOREIGN KEY (value_id) REFERENCES " . $table_values . "(id)
		) "));
	}
	//Authentication Table
	if(eg_tce_table_not_exists("eg_tce_tokens")){
		$table = $wpdb->prefix . "eg_tce_tokens";
		$table_quote_info = $wpdb->prefix . "eg_tce_quote_info";
		$wpdb->query($wpdb->prepare("CREATE TABLE " . $table . "(
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			quote_id mediumint(9) NOT NULL,
			visitor_ip varchar(50),
			PRIMARY KEY (id),
			FOREIGN KEY (quote_id) REFERENCES " . $table_quote_info . "(id)
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