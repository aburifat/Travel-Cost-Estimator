<?php

function is_valid_image($image_url){
	if(empty($image_url))return false;
	$file_extension = pathinfo($image_url, PATHINFO_EXTENSION);
	$allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
	if (in_array(strtolower($file_extension), $allowed_extensions)) return true;
	return false;
}

function tce_cost_estimator($name, $email, $year){
	ob_start();

	global $wpdb;
	$table_fields = $wpdb->prefix . "eg_tce_fields";
	$table_values = $wpdb->prefix . "eg_tce_values";
	$disclaimer = get_option('tce_disclaimer_option');
	$fields = $wpdb->get_results("SELECT * FROM $table_fields");
	
	?>
	<style>
		.field_title{
			text-align:center;
			padding:10px;
			margin: 10px 0px;
			background-color: #F8F8F8;
			box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
			font-weight:bold;
			color:#6F6F6F;
		}
		.field_values{
			padding: 20px;
			display:flex;
			font-weight:bold;
			color:#6F6F6F;
		}
		.text_alert{
			color:#E04E5F;
		}
		.text_info{
			color:#398AC5;
		}
		.tce_image_feature{
			height: 150px;
			width:auto;
			padding-right:20px;
		}

		.tce_image_value{
			height: 100px;
			width:auto;
		}
		.tce_notice{
			font-weight:bold;
		}

	</style>
	<script>
		

		document.addEventListener('DOMContentLoaded', function() {
			var tce_checkboxes = document.querySelectorAll('.tce_value_checkbox');
			function toggle_value_checkboxes(event){
				var checkbox = event.target;
				if(checkbox.checked){
					// Pass this checkbox.value to a php function called add_value
				}else{
					// Pass this checkbox.value to a php function called remove_value
				}
			}
			tce_checkboxes.forEach(function(checkbox) {
				checkbox.addEventListener('change', toggle_value_checkboxes);
			});
		});
	</script>
	<textarea name="disclaimer" rows="10" readonly><?php echo $disclaimer; ?></textarea>
	<?php foreach($fields as $field){ ?>
		<div class="field_title" id="field_<?php echo $field->id; ?>">
			<?php
				echo $field->name;
				if($field->is_required == 1){
					echo ' <span class="text_alert">(필수)</span>';
				}
			?>
		</div>
		<div class="field_values" id="field_values_<?php echo $field->id; ?>">
			<?php
			if(is_valid_image($field->feature_image)){
				?>
				<div class="field_feature_image"><img class="tce_image_feature" src="<?php echo $field->feature_image; ?>"></div>
				<style>
					.field_values{
						
					}
				</style>
				<?php
			}
			?>
			<div style="">
			<?php
			$values = $wpdb->get_results("SELECT * FROM $table_values WHERE field_id = $field->id");
			foreach($values as $value){
				?>
				<div style="display:inline-block;padding:0px 10px;">
					<?php
					if($field->value_type == 2){
						?>
						<img class="tce_image" src="<?php echo $value->image; ?>"><br>
						<?php
					}
					?>
					<div style="text-align:center;">
					<input type="checkbox" class="tce_value_checkbox" name="myCheckbox" id="value_<?php echo $value->id; ?>" value="<?php echo $value->price; ?>">
					<label for="value_<?php echo $value->id; ?>"><?php echo $value->text; ?></label>
					</div>
				</div>
				<?php
			}
			?>
			</div>
			
		</div>
		<div>
				<br><span class="tce_notice <?php echo ($field->notice_type==1)?'text_info':'text_alert' ?>"><?php echo $field->notice; ?></span>
			</div>
	<?php } ?>
	<?php
	return ob_get_clean();
}

function tce_user_info_form(){
	ob_start();
	?>
	<form action="" method="get">
        <label for="tce_name">Name:</label>
        <input type="text" id="tce_name" name="tce_name" required><br><br>
        
        <label for="tce_email">Email:</label>
        <input type="email" id="tce_email" name="tce_email" required><br><br>

		<label for="tce_gender">성별:</label>
		<select name="tce_gender">
			<option value="남">남</option>
			<option value="여">여</option>
		</select><br><br>
        
        <label for="tce_year">Year of Birth:</label>
        <input type="number" id="tce_year" name="tce_year" required><br><br>
        
        <input type="submit" value="Submit">
    </form>
	<?php
	return ob_get_clean();
}

function tce_quote_list(){
	return 1;
}

function tce_single_quote(){ //have to pass the $tce_user_id
	$tce_user_id = 1;
	ob_start();

	$tce_user_allowed = false;
	if(isset($_SESSION['authenticated'][$tce_user_id]) && $_SESSION['authenticated'][$tce_user_id] == true){
		$tce_user_allowed = true;
	}

	global $wpdb;
	$table_user_info = $wpdb->prefix . "eg_tce_user_info";
	$user_info = $wpdb->get_row("SELECT * FROM $table_user_info WHERE id = $tce_user_id");

	if($tce_user_allowed == false){
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($_POST['check_user_password'])) {
				$user_password = sanitize_text_field($_POST['user_password']);
				$hashed_password = md5($user_password);

				if($hashed_password == $user_info->password){
					$_SESSION['authenticated'][$tce_user_id] = true;
					$tce_user_allowed = true;

				}
			}
		}
	}

	if($tce_user_allowed == false){
		?>
		<form action="" method="POST">
			<label for="user_password">Password:</label>
			<input type="password" id="user_password" name="user_password" required><br><br>
			
			<input type="submit" name="check_user_password" value="Submit">
		</form>
		<?php
	}else{
		echo "Wow!";
	}

	return ob_get_clean();
}

function tce_calculator(){
	$name = isset($_GET['tce_name']) ? sanitize_text_field($_GET['tce_name']) : '';
    $email = isset($_GET['tce_email']) ? sanitize_email($_GET['tce_email']) : '';
    $year = isset($_GET['tce_year']) ? intval($_GET['tce_year']) : 0;
	if(!empty($name)&&!empty($email)&&!empty($year)){
		return tce_the_estimator($name, $email, $year);
	}else{
		return tce_personal_info_form();
	}
}

add_shortcode('tce_calculator', 'tce_single_quote');

?>
