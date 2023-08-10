<?php
function travel_cost_estimator_menu_page() {
    add_menu_page(
        'Travel Cost Estimator',
        'Travel Cost Estimator',
        'manage_options',
        'travel-cost-estimator-menu',
        'render_travel_cost_estimator'
    );
}

function get_field_list(){
	global $wpdb;
    $table_name = $wpdb->prefix . "eg_tce_fields";

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_field'])) {
            $name = sanitize_text_field($_POST['name']);
            $serial_no = intval($_POST['serial_no']);
            $feature_image = sanitize_text_field($_POST['feature_image']);
            $notice = sanitize_text_field($_POST['notice']);
            $notice_type = intval($_POST['notice_type']);
            $value_type = intval($_POST['value_type']);

            $wpdb->insert(
                $table_name,
                array(
                    'name' => $name,
                    'serial_no' => $serial_no,
                    'feature_image' => $feature_image,
                    'notice' => $notice,
                    'notice_type' => $notice_type,
                    'value_type' => $value_type,
                )
            );
        }
    }
	?>
	<div class="wrap">
        <h2>Estimator Field List</h2>
        <button class="tce-button tce-button-success" onclick="toggle_add_field()">Add New</button>
        <form class="tce-form add-field-form tce-hidden" method="POST">
			<h3 style="text-align:center;">Add New Field</h3>
            <label>Name: <input type="text" name="name"></label><br>
			<label>Serial Number: <input type="number" name="serial_no"></label><br>
			<label>Feature Image: <input type="text" name="feature_image"></label><br>
			<label>Notice: <input type="text" name="notice"></label><br>
			<label>Notice Type: 
				<select name="notice_type">
					<option value="1">Info</option>
					<option value="2">Alert</option>
				</select>
			</label>
			<label>Value Type:
				<select name="value_type">
					<option value="1">Text</option>
					<option value="2">Text with Image</option>
				</select>
			</label>
            <input class="tce-button tce-button-info" type="submit" name="add_field" value="Add Field">
        </form>

        <h3>Fields</h3>
        <table class="wp-list-table widefat striped tce-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
					<th>Serial No.</th>
					<th>Feature Image</th>
					<th>Notice</th>
					<th>Notice Type</th>
					<th>Value Type</th>
					<th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $results = $wpdb->get_results("SELECT * FROM $table_name");
                foreach ($results as $result) {
					$action_link_base = admin_url('admin.php?page=travel-cost-estimator-menu&field_id=' . $result->id . '&action=');
                    echo '<tr>';
                    echo '<td>' . $result->id . '</td>';
                    echo '<td>' . esc_html($result->name) . '</td>';
					echo '<td>' . $result->serial_no . '</td>';
					echo '<td>' . $result->feature_image . '</td>';
					echo '<td>' . $result->notice . '</td>';
					if($result->notice_type == 1){
						echo '<td>Info</td>';
					}else{
						echo '<td>Alert</td>';
					}
					if($result->value_type == 1){
						echo '<td>Text</td>';
					}else{
						echo '<td>Text with Image</td>';
					}
					echo '<td><a href="' . $action_link_base . "1" . '"><button class="tce-button tce-button-info">Edit Field</button></a>  <a href="' . $action_link_base . "2" . '"><button class="tce-button tce-button-info">Edit Values</button></a>  <a href="' . $action_link_base . '3" onclick="return confirm(\'Are you sure you want to delete this field?\')"><button class="tce-button tce-button-danger">Delete</button></a>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
	<?php
}


function get_value_list($field_id){
	global $wpdb;
    $table_name = $wpdb->prefix . "eg_tce_values";
	$table_field = $wpdb->prefix . "eg_tce_fields";
	$field = $wpdb->get_row("SELECT * FROM $table_field WHERE id = $field_id");

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_value'])) {
            $text = sanitize_text_field($_POST['text']);
			if($field->value_type == 2){
            	$image = sanitize_text_field($_POST['image']);
			}
            $price = intval($_POST['price']);
			if($field->value_type == 2){
				$wpdb->insert(
					$table_name,
					array(
						'field_id' => $field_id,
						'text' => $text,
						'image' => $image,
						'price' => $price,
					)
				);
			}else{
				$wpdb->insert(
					$table_name,
					array(
						'field_id' => $field_id,
						'text' => $text,
						'price' => $price,
					)
				);
			}
        }
    }
	?>
	<div class="wrap">
        <h2>Estimator Field Values [Field ID: <?php echo $field_id; ?>]</h2>
		<a href="<?php echo admin_url('admin.php?page=travel-cost-estimator-menu'); ?>"><button class="tce-button tce-button-info"><span class="dashicons dashicons-admin-home"></span></button></a>
		<a href="<?php echo admin_url('admin.php?page=travel-cost-estimator-menu'); ?>"><button class="tce-button tce-button-info"><span class="dashicons dashicons-arrow-left-alt"></span></button></a>
		<button class="tce-button tce-button-success" onclick="toggle_add_value()">Add New</button>
        <form class="tce-form add-value-form tce-hidden" method="POST">
			<h3 style="text-align:center;">Add New Value</h3>
            <label>Text: <input type="text" name="text"></label><br>
			<?php if($field->value_type == 2){ ?>
				<label>Image: <input type="text" name="image"></label><br>
			<?php } ?>
			<label>price: <input type="number" name="price"></label><br>
            <input class="tce-button tce-button-info" type="submit" name="add_value" value="Add Value">
        </form>

        <h3>Values</h3>
        <table class="wp-list-table widefat striped tce-table">
            <thead>
                <tr>
                    <th>ID</th>
					<th>Field ID</th>
                    <th>Text</th>
					<?php if($field->value_type == 2){ ?>
						<th>Image</th>
					<?php } ?>
					<th>Price</th>
					<th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $results = $wpdb->get_results("SELECT * FROM $table_name WHERE field_id = $field_id");
                foreach ($results as $result) {
					$action_link_base = admin_url('admin.php?page=travel-cost-estimator-menu&field_id=' . $field_id . '&value_id=' . $result->id . '&action=');
                    echo '<tr>';
                    echo '<td>' . $result->id . '</td>';
					echo '<td>' . $result->field_id . '</td>';
                    echo '<td>' . esc_html($result->text) . '</td>';
					if($field->value_type == 2){
						echo '<td>' . $result->image . '</td>';
					}
					echo '<td>' . $result->price . '</td>';
					echo '<td><a href="' . $action_link_base . "1" . '"><button class="tce-button tce-button-info">Edit Value</button></a>  <a href="' . $action_link_base . '2" onclick="return confirm(\'Are you sure you want to delete this value?\')"><button class="tce-button tce-button-danger">Delete</button></a>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
	<?php
}

function get_edit_field($field_id){
	global $wpdb;
    $table_name = $wpdb->prefix . "eg_tce_fields";

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['update_field'])) {
            $name = sanitize_text_field($_POST['name']);
            $serial_no = intval($_POST['serial_no']);
            $feature_image = sanitize_text_field($_POST['feature_image']);
            $notice = sanitize_text_field($_POST['notice']);
            $notice_type = intval($_POST['notice_type']);
            $value_type = intval($_POST['value_type']);

            $wpdb->update(
                $table_name,
                array(
                    'name' => $name,
                    'serial_no' => $serial_no,
                    'feature_image' => $feature_image,
                    'notice' => $notice,
                    'notice_type' => $notice_type,
                    'value_type' => $value_type,
				),
				array('id' => $field_id)
            );
        }
    }else{
		$field = $wpdb->get_row(
			$wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $field_id)
		);
		if($field){
			$name = $field->name;
			$serial_no = $field->serial_no;
			$feature_image = $field->feature_image;
			$notice = $field->notice;
			$notice_type = $field->notice_type;
			$value_type = $field->value_type;
		}
		
	}
	?>
	<div class="wrap">
        <h2>Estimator Field List</h2>
		<a href="<?php echo admin_url('admin.php?page=travel-cost-estimator-menu'); ?>"><button class="tce-button tce-button-info"><span class="dashicons dashicons-admin-home"></span></button></a>
		<a href="<?php echo admin_url('admin.php?page=travel-cost-estimator-menu'); ?>"><button class="tce-button tce-button-info"><span class="dashicons dashicons-arrow-left-alt"></span></button></a>
        <form class="tce-form" method="POST">
			<h3 style="text-align:center;">Update Field</h3>
			<label>ID: <input type="number" name="id" value="<?php echo $field_id; ?>" readonly></label><br>
            <label>Name: <input type="text" name="name" value="<?php echo $name; ?>"></label><br>
            <label>Serial Number: <input type="number" name="serial_no" value="<?php echo $serial_no; ?>"></label><br>
			<label>Feature Image: <input type="text" name="feature_image" value="<?php echo $feature_image; ?>"></label><br>
			<label>Notice: <input type="text" name="notice" value="<?php echo $notice; ?>"></label><br>
			<label>Notice Type: 
				<select name="notice_type">
					<option value="1" <?php if ($notice_type == 1) echo 'selected'; ?>>Info</option>
					<option value="2" <?php if ($notice_type == 2) echo 'selected'; ?>>Alert</option>
				</select>
			</label>
			<label>Value Type:
				<select name="value_type">
					<option value="1" <?php if ($notice_type == 1) echo 'selected'; ?>>Text</option>
					<option value="2" <?php if ($notice_type == 2) echo 'selected'; ?>>Text with Image</option>
				</select>
			</label>
            <input class="tce-button tce-button-info" type="submit" name="update_field" value="Update Field">
        </form>
    </div>
	<?php
}


function get_edit_value($value_id,$field_id){
	global $wpdb;
    $table_name = $wpdb->prefix . "eg_tce_values";
	$table_field = $wpdb->prefix . "eg_tce_fields";
	$the_field = $wpdb->get_row("SELECT * FROM $table_field WHERE id = $field_id");

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['update_value'])) {

			$text = sanitize_text_field($_POST['text']);
			if($the_field->value_type == 2){
            	$image = sanitize_text_field($_POST['image']);
			}
            $price = intval($_POST['price']);
			if($the_field->value_type == 2){
				$wpdb->update(
					$table_name,
					array(
						'text' => $text,
						'image' => $image,
						'price' => $price,
					),
					array('id' => $value_id)
				);
			}else{
				$wpdb->update(
					$table_name,
					array(
						'text' => $text,
						'price' => $price,
					),
					array('id' => $value_id)
				);
			}
        }
    }else{
		$field = $wpdb->get_row(
			$wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $value_id)
		);
		if($field){
			$text = $field->text;
			if($the_field->value_type == 2){
				$image = $field->image;
			}
			$price = $field->price;
		}
		
	}
	?>
	<div class="wrap">
		<h2>Estimator Field Values [Field ID: <?php echo $field_id; ?>]</h2>
		<a href="<?php echo admin_url('admin.php?page=travel-cost-estimator-menu'); ?>"><button class="tce-button tce-button-info"><span class="dashicons dashicons-admin-home"></span></button></a>
		<a href="<?php echo admin_url('admin.php?page=travel-cost-estimator-menu&field_id='. $field_id .'&action=2'); ?>"><button class="tce-button tce-button-info"><span class="dashicons dashicons-arrow-left-alt"></span></button></a>
        <form class="tce-form" method="POST">
			<h3 style="text-align:center;">Update Value</h3>
			<label>ID: <input type="number" name="id" value="<?php echo $value_id; ?>" readonly></label><br>
            <label>Field ID: <input type="number" name="field_id" value="<?php echo $field_id; ?>" readonly></label><br>
            <label>Text: <input type="text" name="text" value="<?php echo $text; ?>"></label><br>
			<?php if($the_field->value_type == 2){ ?>
				<label>Image: <input type="text" name="image" value="<?php echo $image; ?>"></label><br>
			<?php } ?>
			<label>Price: <input type="number" name="price" value="<?php echo $price; ?>"></label><br>
            <input class="tce-button tce-button-info" type="submit" name="update_value" value="Update Value">
        </form>
    </div>
	<?php
}



function delete_field($field_id){
	global $wpdb;
	$values_table = $wpdb->prefix . "eg_tce_values";
	$wpdb->delete($values_table, array('field_id' => $field_id));
	$fields_table = $wpdb->prefix . "eg_tce_fields";
	$wpdb->delete($fields_table, array('id' => $field_id));
}

function delete_value($value_id){
	global $wpdb;
	$values_table = $wpdb->prefix . "eg_tce_values";
	$wpdb->delete($values_table, array('id' => $value_id));
}



function render_travel_cost_estimator() {
	
	$field_id = isset($_GET['field_id']) ? intval($_GET['field_id']) : 0;
	$value_id = isset($_GET['value_id']) ? intval($_GET['value_id']) : 0;
	$action = isset($_GET['action']) ? intval($_GET['action']) : 0;

	if($value_id != 0 && $field_id != 0){
		if($action == 1){
			get_edit_value($value_id,$field_id);
		}else if($action == 2){
			delete_value($value_id);
			get_value_list($field_id);
		}else{
			get_value_list($field_id);
		}
	}else if($field_id != 0){
		if($action == 1){
			get_edit_field($field_id);
		}else if($action == 2){
			get_value_list($field_id);
		}else if($action == 3){
			delete_field($field_id);
			get_field_list();
		}else{
			get_field_list();
		}
	}else{
		get_field_list();
	}
}



add_action('admin_menu', 'travel_cost_estimator_menu_page');





?>