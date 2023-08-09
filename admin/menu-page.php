<?php
function custom_menu_page() {
    add_menu_page(
        'Custom CRUD Page',
        'Custom CRUD',
        'manage_options',
        'custom-crud-menu',
        'render_custom_crud_page'
    );
}

function get_field_list(){
	global $wpdb;
    $table_name = $wpdb->prefix . "eg_tce_fields";

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_record'])) {
            $name = sanitize_text_field($_POST['name']);
            $serial_no = intval($_POST['serial_no']);
            $feature_image = sanitize_text_field($_POST['feature_image']);
            $notice = sanitize_text_field($_POST['notice']);
            $notice_type = intval($_POST['notice_type']);
            $value_type = intval($_POST['value_type']);
            $value_count = intval($_POST['value_count']);

            $wpdb->insert(
                $table_name,
                array(
                    'name' => $name,
                    'serial_no' => $serial_no,
                    'feature_image' => $feature_image,
                    'notice' => $notice,
                    'notice_type' => $notice_type,
                    'value_type' => $value_type,
                    'value_count' => $value_count,
                )
            );
        }
    }
	?>
	<div class="wrap">
        <h2>Estimator Field List</h2>

        <!-- Add Record Form -->
        <h3>Add New Record</h3>
        <form method="POST">
            <label>Name: <input type="text" name="name"></label><br>
            <label>Serial Number: <input type="number" name="serial_no"></label><br>
			<label>Feature Image: <input type="text" name="feature_image"></label><br>
			<label>Notice: <input type="text" name="notice"></label><br>
			<label>Notice Type: <input type="number" name="notice_type"></label><br>
			<label>Value Type: <input type="number" name="value_type"></label><br>
			<label>Value Count: <input type="number" name="value_count"></label><br>
            <!-- Add other fields here -->
            <input type="submit" name="add_record" value="Add Record">
        </form>

        <!-- Display Records -->
        <h3>Records</h3>
        <table class="wp-list-table widefat striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
					<th>S.No.</th>
					<th>V.Type</th>
					<th>V.Count</th>
					<th>Actions</th>
                    <!-- Add other headers here -->
                </tr>
            </thead>
            <tbody>
                <?php
                $results = $wpdb->get_results("SELECT * FROM $table_name");
                foreach ($results as $result) {
                    echo '<tr>';
                    echo '<td>' . $result->id . '</td>';
                    echo '<td>' . esc_html($result->name) . '</td>';
					echo '<td>' . $result->serial_no . '</td>';
					echo '<td>' . $result->value_type . '</td>';
					echo '<td>' . $result->value_count . '</td>';
					echo '<td><a href="' . admin_url('admin.php?page=custom-crud-menu&field_id=' . $result->id) . '">Edit</a> | <a href="#">Delete</a>';
                    // Add other columns here
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
	<?php
}

function get_single_field($field_id){
	?>
	<h2>Estimator Field Edit</h2>
	<?php
}



function render_custom_crud_page() {
	

	$field_id = isset($_GET['field_id']) ? intval($_GET['field_id']) : 0;

	if($field_id === 0){
		get_field_list();
	}else{
		get_single_field($field_id);
	}
}



add_action('admin_menu', 'custom_menu_page');





?>