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
			value_count int,
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
	//In future "users and records"
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