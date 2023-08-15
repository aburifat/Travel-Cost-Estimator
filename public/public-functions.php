<?php

function is_valid_image($image_url){
	if(empty($image_url))return false;
	$file_extension = pathinfo($image_url, PATHINFO_EXTENSION);
	$allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
	if (in_array(strtolower($file_extension), $allowed_extensions)) return true;
	return false;
}

function tce_cost_estimator($name, $email, $gender, $year){
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
	<style>
		.tce-form{
			border:1px solid #00000022;
			border-top:2px solid black;
			/*box-shadow: rgba(0, 0, 0, 0.25) 0px 0.0625em 0.0625em, rgba(0, 0, 0, 0.25) 0px 0.125em 0.5em, rgba(255, 255, 255, 0.1) 0px 0px 0px 1px inset;*/
			width: 100%;
			margin:auto;
			padding:20px;
		}
		.tce-form-button-div{
			display:flex;
			justify-content:center;
		}
		.tce-form input, .tce-form textarea, .tce-form select{
			width:100%;
			margin: 5px 0px 10px 0px;
		}
		.tce-form input[type="radio"], .tce-form input[type="submit"]{
			width:auto;
		}
		.tce-form-header{
			display:flex;
			justify-content:space-between;
		}
		.tce-form-header-text{
			line-height:1em;
			display:flex;
			flex-direction:column;
			justify-content:center;
		}

		@media (max-width:767px){
			.tce-form-header{
				display:block;
			}
			.tce-form-header-text{
				display:block;
				text-align:center;
			}
			.tce-form-header-image{
				text-align:center;
			}
		}
	</style>
	<?php
		$user_form_header_text = get_option('user_form_header_text');
		$user_form_header_image = get_option('user_form_header_image');
	?>
	<div class="tce-form-header">
		<div class="tce-form-header-text">
			<?php echo $user_form_header_text; ?>
		</div>
		<div class="tce-form-header-image">
			<img src="<?php echo $user_form_header_image; ?>">
		</div>
	</div>
	<form class="tce-form" action="" method="POST">
        <input type="text" id="tce_name" name="tce_name" placeholder="이름" required>
        <input type="email" id="tce_email" name="tce_email" placeholder="연락처" required>

		<label for="tce_gender">성별:</label>
		<span>남</span>
		<input type="radio" name="tce_gender" value="남">
		<span>여</span>
		<input type="radio" name="tce_gender" value="여"><br>
        
        <label for="tce_year">Year of Birth:</label>
		<select name="tce_year" required>
			<option value="">나이</option>
			<?php
			for($i=1950;$i<=2005;$i++){
				echo '<option value='. $i .'>' . $i . '</option>';
			}
			?>
		</select>
        <div class="tce-form-button-div">
			<input style="background-color:#c30000;color:white;" type="submit" name="user_info_submitted" value="견적문의신청하기">&nbsp;
			</form><form style="display:inline;">
			<input style="background-color:#3c3c3c;color:white;" type="submit" name="quote-list" value="신청목록보기"></form>
		</div>
        
    
	<?php
	return ob_get_clean();
}

function tce_quote_list(){
	return 1;
}

function tce_single_quote(){ //have to pass the $tce_user_id
	$tce_user_id = 1;
	ob_start();

	global $wpdb;
	$table_user_info = $wpdb->prefix . "eg_tce_user_info";
	$user_info = $wpdb->get_row("SELECT * FROM $table_user_info WHERE id = $tce_user_id");

	if($tce_user_allowed == false){
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (isset($_POST['check_user_password'])) {
				$user_password = sanitize_text_field($_POST['user_password']);
				$hashed_password = md5($user_password);
				if($hashed_password == $user_info->password){

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
	if(isset($_POST['user_info_submitted'])){
		$name = isset($_POST['tce_name']) ? sanitize_text_field($_POST['tce_name']) : '';
		$email = isset($_POST['tce_email']) ? sanitize_email($_POST['tce_email']) : '';
		$gender = isset($_POST['tce_gender'])? sanitize_text_field( $_POST['tce_gender'] ) : '';
		$year = isset($_POST['tce_year']) ? intval($_POST['tce_year']) : 0;
	}
	
	if(!empty($name)&&!empty($email)&&!empty($gender)&&!empty($year)){
		return tce_cost_estimator($name, $email, $gender, $year);
	}else{
		return tce_user_info_form();
	}
}

add_shortcode('tce_calculator', 'tce_calculator');

?>
