<?php


function tce_get_style(){
	ob_start();
	?>
	<style>
		.tce_submit_button{
			color:white;
		}

		#tce_submit_button_red{
			background-color:#c30000!important;
		}

		#tce_submit_button_gray{
			background-color:#3c3c3c!important;
		}

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

		.tce-form{
			width: 100%;
			margin:auto;
			padding:0px 0px 20px 0px ;
			font-weight:bold;
		}
		.top-black-line{
			border-top:2px solid black;
		}
		.tce-form-button-div{
			display:flex;
			justify-content:center;
		}

		.tce-form input.active{
			border:1px solid blue!important;
		}

		.tce-form input, .tce-form textarea, .tce-form select{
			width:100%;
			margin: 5px 0px 10px 0px;
			border: 1px solid #a5a5a5;
		}

		.tce-form input[type="radio"]{
			width:auto;
			margin:20px 0px;
		}

		.tce-form input[type="checkbox"]{
			width:auto;
		}

		.tce-form input[type="submit"]{
			width:auto;
		}
		.tce-form input{
			outline:none;
		}

		.tce-form input:focus{
  			outline: 1px solid black;
			border: 1px solid black;
		}

		.tce-form textarea{
			width:100%;
			margin: 0px 0px 10px 0px;
		}

		.tce-form-header{
			display:flex;
			justify-content:space-between;
		}

		.tce-login-form{
			padding:0px 0px 0px 20px;
		}

		.tce-form-header-text{
			display:flex;
			flex-direction:column;
			justify-content:center;
		}

		.tce-in-form-image{
			width:50%;
			display:flex;
			justify-content:center;
		}
		.tce-in-form-image img{
			width:100%;
		}

		@media (max-width:767px){
			.tce-form-header{
				display:block;
			}
			.tce-form-header-text{
				display:block;
				text-align:center;
				padding:0px 20px;
			}
			.tce-form-header-image{
				text-align:center;
			}
		}

	</style>
	<?php
	return ob_get_clean();
}

function tce_get_script(){
	ob_start();
	?>
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
	<?php
	return ob_get_clean();
}

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

	$estimator_header_text = get_option('estimator_header_text');
	$estimator_header_image = get_option('estimator_header_image');
	$estimate_in_form_image = get_option('estimate_in_form_image');

	$estimator_header_text = str_replace("[name]", $name, $estimator_header_text);
	$estimator_header_text = str_replace("[email]", $email, $estimator_header_text);
	$estimator_header_text = str_replace("[gender]", $gender, $estimator_header_text);
	$estimator_header_text = str_replace("[year]", $year, $estimator_header_text);
	
	?>
	<div class="tce-form-header">
		<div class="tce-form-header-text">
			<?php echo $estimator_header_text; ?>
		</div>
		<div class="tce-form-header-image">
			<img src="<?php echo $estimator_header_image; ?>">
		</div>
	</div>
	<textarea class="top-black-line" name="disclaimer" rows="10" readonly><?php echo $disclaimer; ?></textarea>
	<form class="tce-form" action="" method="POST">
		<span style="padding:5px 0px 10px 0px;display:block;">개인정보취급방침에 동의하셔야 견적을 진행하실 수 있습니다. <input type="checkbox" class="tce_value_checkbox" name="tce_toc_agreement" id="pp_agreed" value="Agreed" required></span>
		<div class="tce-form-header">
			<div class="tce-in-form-image">
			<img src="<?php echo $estimate_in_form_image; ?>">
			</div>
			<div class="tce-login-form">
				<input type="text" id="tce_name" name="tce_name" placeholder="이름" value="<?php echo $name; ?>" required>
				<input type="password" id="tce_password" name="tce_password" placeholder="비밀번호" required>
				<input type="email" id="tce_email" name="tce_email" placeholder="연락처" value="<?php echo $email; ?>" required>

				<label for="tce_gender">성별:</label>
				<span>남</span>
				<input type="radio" name="tce_gender" value="남" required <?php if($gender == "남")echo "checked" ?>>
				<span>여</span>
				<input type="radio" name="tce_gender" value="여" required <?php if($gender == "여")echo "checked" ?>><br>
				
				<select name="tce_year" required>
					<option value="">나이</option>
					<?php
					for($i=1950;$i<=2005;$i++){
						if($year == $i)
							echo '<option value='. $i .' selected>' . $i . '</option>';
						else
						echo '<option value='. $i .'>' . $i . '</option>';
					}
					?>
				</select>
				<input type="text" id="tce_contant_no" name="tce_contant_no" placeholder="연락처(010-0000-0000)" required>
			</div>
		</div>
		<?php
		foreach($fields as $field){
			?>
			<div class="field_title" id="field_<?php echo $field->id; ?>">
				<?php
					echo $field->name;
					if($field->is_required == 1){
						echo ' <span class="text_alert">(필수)</span>';
					}
				?>
			</div>
			<div class="field_values" id="field_id_<?php echo $field->id; ?>">
				<?php
				if(is_valid_image($field->feature_image)){
					?>
					<div class="field_feature_image"><img class="tce_image_feature" src="<?php echo $field->feature_image; ?>"></div>
					<?php
				}
				?>
				<div>
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
								<input type="checkbox" class="tce_value_checkbox" name="value_id_<?php echo $value->id; ?>" id="value_id_<?php echo $value->id; ?>" value="<?php echo $value->price; ?>">
								<label for="value_id_<?php echo $value->id; ?>"><?php echo $value->text; ?></label>
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
		<div class="tce-form-button-div">
			<button class="tce_submit_button" id="tce_submit_button_red" type="submit" name="tcepage" value="savequote">견적저장</button>&nbsp;
			</form><form action="" method="POST" style="display:inline;" >
			<button class="tce_submit_button" id="tce_submit_button_gray" type="submit">처음으로</button></form>
		</div>
	<?php
	return ob_get_clean();
}

function tce_user_info_form(){
	ob_start();
	?>
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
	<form class="tce-form top-black-line" action="" method="GET">
        <input type="text" id="tce_name" name="tce_name" placeholder="이름" required>
        <input type="email" id="tce_email" name="tce_email" placeholder="연락처" required>

		<label for="tce_gender">성별:</label>
		<span>남</span>
		<input type="radio" name="tce_gender" value="남" required>
		<span>여</span>
		<input type="radio" name="tce_gender" value="여" required><br>
        
		<select name="tce_year" required>
			<option value="">나이</option>
			<?php
			for($i=1950;$i<=2005;$i++){
				echo '<option value='. $i .'>' . $i . '</option>';
			}
			?>
		</select>
        <div class="tce-form-button-div">
			<button class="tce_submit_button" id="tce_submit_button_red" type="submit" name="tcepage" value="getquote">견적문의신청하기</button>&nbsp;
			</form><form style="display:inline;">
			<button class="tce_submit_button" id="tce_submit_button_gray" type="submit" name="tcepage" value="quotelist">신청목록보기</button></form>
		</div>
        
    
	<?php
	return ob_get_clean();
}

function tce_quote_list(){
	return "Quote List Part";
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
	global $wpdb;

	$output = '';
	$output .= tce_get_style();
	$output .= tce_get_script();
	if(isset($_GET['tcepage'])){
		if($_GET['tcepage'] == 'getquote'){
			$name = isset($_GET['tce_name']) ? $_GET['tce_name'] : '';
			$email = isset($_GET['tce_email']) ? $_GET['tce_email'] : '';
			$gender = isset($_GET['tce_gender'])? $_GET['tce_gender'] : '';
			$year = isset($_GET['tce_year']) ? $_GET['tce_year'] : 0;
			if(!empty($name)&&!empty($email)&&!empty($gender)&&!empty($year)){
				return $output . tce_cost_estimator($name, $email, $gender, $year);
			}
			return $output . tce_user_info_form();
		}else if($_GET['tcepage'] == 'quotelist'){
			return $output . tce_quote_list();
		}
		return $output . tce_user_info_form();
	}else if(isset($_POST['tcepage'])){
		if($_POST['tcepage'] == 'savequote'){
			$name = isset($_POST['tce_name']) ? $_POST['tce_name'] : '';
			$password = isset($_POST['tce_password']) ? $_POST['tce_password'] : '';
			$email = isset($_POST['tce_email']) ? $_POST['tce_email'] : '';
			$gender = isset($_POST['tce_gender'])? $_POST['tce_gender'] : '';
			$year = isset($_POST['tce_year']) ? $_POST['tce_year'] : 0;
			$contact_no = isset($_POST['tce_contant_no']) ? $_POST['tce_contant_no'] : '';
			if(!empty($name)&&!empty($email)&&!empty($gender)&&!empty($year)){
				$tce_value_ids = [];
				$table_values = $wpdb->prefix . "eg_tce_values";
				$values = $wpdb->get_results("SELECT * FROM $table_values");

				foreach($values as $value){
					if(!isset($_POST['value_id_'.$value->id])){
						$tce_value_ids.push($value->id);
					}
				}
				$table_user_info = $wpdb->prefix . "eg_tce_user_info";
				$result = $wpdb->insert(
					$table_user_info,
					array(
						'name' => $name,
						'password' => md5($password),
						'email' => $email,
						'gender' => $gender,
						'year' => $year,
						'contact_no' => $contact_no,
					)
				);

				if($result){
					$qoute_id = $wpdb->insert_id;
					return "Data Inserted with ID: " . $qoute_id;
				}
			}
			return $output . tce_cost_estimator($name, $email, $gender, $year);
		}
	}
	return $output . tce_user_info_form();
}

add_shortcode('tce_calculator', 'tce_calculator');

?>
