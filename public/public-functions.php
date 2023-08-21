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
			color:black;
		}

		input::placeholder{
			color:#bababa;
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

		.estimate_table, .estimate_table td, .estimate_table th{
			text-align:center;
			border-color:#bfbfbf;
		}

		.estimate_table th{
			background-color:#F0F0F0;
		}

		.hidden{
			display: none !important;
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
			const tce_checkboxes = document.querySelectorAll('.tce_value_checkbox');
			const tce_total_price = document.querySelector('.estimate_total_price');
			function toggle_value_checkboxes(event){
				var checkbox = event.target;
				const row_id = "row_" + checkbox.name.match(/value_(\d+)/)[1];
				const estimate_row = document.getElementById(row_id);
				var total_price = parseInt(tce_total_price.textContent);
				
				if(checkbox.checked){
					estimate_row.classList.remove("hidden");
					total_price += parseInt(checkbox.value);
					tce_total_price.textContent = total_price;
				}else{
					estimate_row.classList.add("hidden");
					total_price -= parseInt(checkbox.value);
					tce_total_price.textContent = total_price;
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

function tce_fetch_data(){
    global $wpdb;

    $data = array();
    $data['page'] = isset($_GET['tce_page']) ? $_GET['tce_page'] : '';
    $data['quote_id'] = isset($_GET['quote_id']) ? $_GET['quote_id'] : '';
    $data['name'] = isset($_GET['tce_name']) ? $_GET['tce_name'] : '';
    $data['email'] = isset($_GET['tce_email']) ? $_GET['tce_email'] : '';
    $data['gender'] = isset($_GET['tce_gender'])? $_GET['tce_gender'] : '';
    $data['year'] = isset($_GET['tce_year']) ? $_GET['tce_year'] : 0;
    $data['password'] = isset($_GET['tce_password']) ? $_GET['tce_password'] : '';
    $data['contact_no'] = isset($_GET['tce_contant_no']) ? $_GET['tce_contant_no'] : '';

    $data['table_fields'] = $wpdb->prefix . "eg_tce_fields";
    $data['table_values'] = $wpdb->prefix . "eg_tce_values";
    $data['table_quote_info'] = $wpdb->prefix . "eg_tce_quote_info";
    $data['table_quote_values'] = $wpdb->prefix . "eg_tce_quote_values";
    $data['table_tokens'] = $wpdb->prefix . "eg_tce_tokens";

    $data['style'] = tce_get_style();
    $data['script'] = tce_get_script();

    return $data;
}

function tce_user_info_form($data){
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
			<button class="tce_submit_button" id="tce_submit_button_red" type="submit" name="tce_page" value="getquote">견적문의신청하기</button>&nbsp;
			</form><form style="display:inline;" action="" method="GET">
			<button class="tce_submit_button" id="tce_submit_button_gray" type="submit" name="tce_page" value="quotelist">신청목록보기</button></form>
		</div>
        
    
	<?php
	echo ob_get_clean();
}

function tce_cost_estimator($data){
	ob_start();

	global $wpdb;

	$disclaimer = get_option('tce_disclaimer_option');
	$fields = $wpdb->get_results("SELECT * FROM " . $data['table_fields']);

	$estimator_header_text = get_option('estimator_header_text');
	$estimator_header_image = get_option('estimator_header_image');
	$estimate_in_form_image = get_option('estimate_in_form_image');

	$estimator_header_text = str_replace("[name]", $data['name'], $estimator_header_text);
	$estimator_header_text = str_replace("[email]", $data['email'], $estimator_header_text);
	$estimator_header_text = str_replace("[gender]", $data['gender'], $estimator_header_text);
	$estimator_header_text = str_replace("[year]", $data['year'], $estimator_header_text);
	
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
	<form class="tce-form" action="" method="GET">
		<span style="padding:5px 0px 10px 0px;display:block;">개인정보취급방침에 동의하셔야 견적을 진행하실 수 있습니다. <input type="checkbox" class="tce_value_checkbox" required></span>
		<div class="tce-form-header">
			<div class="tce-in-form-image">
			<img src="<?php echo $estimate_in_form_image; ?>">
			</div>
			<div class="tce-login-form">
				<input type="text" id="tce_name" name="tce_name" placeholder="이름" value="<?php echo $data['name']; ?>" required>
				<input type="password" id="tce_password" name="tce_password" placeholder="비밀번호" required>
				<input type="email" id="tce_email" name="tce_email" placeholder="연락처" value="<?php echo $data['email']; ?>" required>

				<label for="tce_gender">성별:</label>
				<span>남</span>
				<input type="radio" name="tce_gender" value="남" required <?php if($data['gender'] == "남")echo "checked" ?>>
				<span>여</span>
				<input type="radio" name="tce_gender" value="여" required <?php if($data['gender'] == "여")echo "checked" ?>><br>
				
				<select name="tce_year" required>
					<option value="">나이</option>
					<?php
					for($i=1950;$i<=2005;$i++){
						if($data['year'] == $i)
							echo '<option value='. $i .' selected>' . $i . '</option>';
						else
						echo '<option value='. $i .'>' . $i . '</option>';
					}
					?>
				</select>
				<input type="text" id="tce_contant_no" name="tce_contant_no" placeholder="연락처(010-0000-0000)" value="<?php echo $data['contact_no']; ?>" required>
			</div>
		</div>
		<?php
		foreach($fields as $field){
			?>
			<div class="field_title" id="field_title_<?php echo $field->id; ?>">
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
					<?php
				}
				?>
				<div class="values">
					<?php
						$values = $wpdb->get_results("SELECT * FROM " . $data['table_values'] ." WHERE field_id = $field->id");
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
								<input type="checkbox" class="tce_value_checkbox" name="value_<?php echo $value->id; ?>" id="value_<?php echo $value->id; ?>" value="<?php echo $value->price; ?>">
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
		<br>
		<table class="estimate_table">
			<tr>
				<th>견적내용</th>
				<th>견적금액</th>
			</tr>

			<?php
				$values = $wpdb->get_results("SELECT * FROM " . $data['table_values']);
				foreach($values as $value){
					?>
						<tr class="estimate_row hidden" id="row_<?php echo $value->id; ?>">
							<td class="estimate_name" id="estimate_text_<?php echo $value->id; ?>"><?php echo $value->text; ?></td>
							<td class="estimate_price" id="estimate_price_<?php echo $value->id; ?>"><?php echo $value->price; ?></td>
						</tr>
					<?php
				}
			?>

			<tr>
				<td><b>총견적금액(+)</b></td>
				<td><span class="estimate_total_price" style="color:red;">0</span> $(달러)</td>
			</tr>
		</table>
		<div class="tce-form-button-div">
			<button class="tce_submit_button" id="tce_submit_button_red" type="submit" name="tce_page" value="savequote">견적저장</button>&nbsp;
			</form><form action="" method="GET" style="display:inline;" >
			<button class="tce_submit_button" id="tce_submit_button_gray" type="submit" name="tce_page" value="init">처음으로</button></form>
		</div>
	<?php
	echo ob_get_clean();
}

function tce_quote_list($data){
	
	ob_start();

	?>
	<table class="estimate_table">
		<tr>
			<th>번호</th>
			<th>제목</th>
			<th>등록날짜</th>
		</tr>

		<?php
			global $wpdb;
			$quotes = $wpdb->get_results("SELECT id, name FROM " . $data['table_quote_info']);
			$number_of_quotes = count($quotes);
			foreach($quotes as $quote){
				?>
					<tr>
						<td><?php echo $number_of_quotes--; ?></td>
						<td>
							<a href="?tce_page=singlequote&quote_id=<?php echo $quote->id;?>"><?php echo $quote->name; ?>**님의 여행견적서</a></td>
						<td>2023-08-15</td>
					</tr>
				<?php
			}
		?>
	</table>
	<form style="text-align:right;">
		<button class="tce_submit_button" id="tce_submit_button_gray" type="submit" name="tce_page" value="init">견적신청하기</button>
	</form>
	<?php

	echo ob_get_clean();
}

function tce_get_visitor_ip(){
	return $_SERVER['REMOTE_ADDR'];
}

function tce_is_authenticated($data){

    global $wpdb;

	if (current_user_can('administrator')) {
        return true;
    }

    $token = $wpdb->get_row("SELECT * FROM " . $data['table_tokens'] . " WHERE quote_id = " . $data['quote_id']);
    $visitor_ip = tce_get_visitor_ip();
    
    if(md5($visitor_ip) == $token->visitor_ip){
        return true;
    }
    
    return false;
}

function tce_authenticate($data){
	ob_start();
	global $wpdb;
	$quote = $wpdb->get_row("SELECT password FROM " . $data['table_quote_info'] . " WHERE id = " . $data['quote_id']);
	$quote_pass = $quote->password;

	if(isset($_GET['user_password'])){
		$user_pass = md5($_GET['user_password']);
		if($quote_pass == $user_pass){
			?>
			
			
			<?php
			$result = $wpdb->insert(
				$data['table_tokens'],
				array(
					'quote_id' => $data['quote_id'],
					'visitor_ip' => md5(tce_get_visitor_ip()),
				)
			);
		}
	}

	if(!tce_is_authenticated($data)){
		?>
		<div style="margin:30px auto;text-align:center;max-width:350px;width:100%;box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;">
			<?php
				$quote = $wpdb->get_row("SELECT name FROM " . $data['table_quote_info'] . " WHERE id = " . $data['quote_id']);
			?>
			<div style="padding:20px;">
				<h2><span style="color:red;"><?php echo $quote->name; ?></span>님 견적서</h2>
				<span style="color:#3CA1FF;">비밀글 기능으로 보호된 글입니다.</span><br>
				<span>작성자와 관리자만 열람하실 수 있습니다. 본인이라면 비밀번호를 입력하세요.</span>
			</div>
			<hr style="margin:0px;">
		
			<form class="tce-form" action="" method="GET" style="max-width:350px;width:100%;padding:30px 20px;">
				<input type="password" id="user_password" name="user_password" placeholder="비밀번호" required>
				<input type="text" id="quote_id" name="quote_id" value="<?php echo $data['quote_id']; ?>" hidden>
				<button class="tce_submit_button" id="tce_submit_button_gray" type="submit" name="tce_page" value="singlequote" style="width:100%;">확인</button>
			</form>
		</div>
		<?php
	}else{
		tce_single_quote($data);
	}
	echo ob_get_clean();
}

function tce_single_quote($data){
	ob_start();

	global $wpdb;

	?>
	<table class="estimate_table">
		<tr>
			<th>견적내용</th>
			<th>견적금액</th>
		</tr>

		<?php
			$value_ids = $wpdb->get_results("SELECT value_id FROM " . $data['table_quote_values'] . " WHERE quote_id = " . $data['quote_id']);
			$total_price = 0;
			foreach($value_ids as $value_id){
				$value = $wpdb->get_row("SELECT id, text, price FROM " . $data['table_values'] . " WHERE id = " . $value_id->value_id);
				$total_price += $value->price;
				?>
					<tr class="estimate_row" id="row_<?php echo $value->id; ?>">
						<td class="estimate_name" id="estimate_text_<?php echo $value->id; ?>"><?php echo $value->text; ?></td>
						<td class="estimate_price" id="estimate_price_<?php echo $value->id; ?>"><?php echo $value->price; ?></td>
					</tr>
				<?php
			}
		?>

		<tr>
			<td><b>총견적금액(+)</b></td>
			<td><span class="estimate_total_price" style="color:red;"><?php echo $total_price; ?></span> $(달러)</td>
		</tr>
	</table>
	<form style="text-align:right;">
		<input type="text" id="quote_id" name="quote_id" value="<?php echo $data['quote_id']; ?>" hidden>
		<button class="tce_submit_button" id="tce_submit_button_red" type="submit" name="tce_page" value="deletequote">삭제</button>
		<button class="tce_submit_button" id="tce_submit_button_gray" type="submit" name="tce_page" value="quotelist">목록</button>
		<button class="tce_submit_button" id="tce_submit_button_gray" type="submit" name="tce_page" value="init">다시 견적내기</button>
	</form>
	<?php

	echo ob_get_clean();
}


function tce_save_quote($data){
	ob_start();
    global $wpdb;

    $values = $wpdb->get_results("SELECT * FROM " . $data['table_values']);
    $value_ids = array();

    foreach($values as $value){
        $value_key = 'value_' . $value->id;
        if(isset($_GET[$value_key])){
            $value_ids[] = $value->id;
        }
    }

    $result = $wpdb->insert(
        $data['table_quote_info'],
        array(
            'name' => $data['name'],
            'password' => md5($data['password']),
            'email' => $data['email'],
            'gender' => $data['gender'],
            'year' => $data['year'],
            'contact_no' => $data['contact_no'],
        )
    );

    if($result){
        $quote_id = $wpdb->insert_id;
        foreach($value_ids as $value_id){
            $wpdb->insert(
                $data['table_quote_values'],
                array(
                    'quote_id' => $quote_id,
                    'value_id' => $value_id
                )
            );
        }
		?>
			<button id="redirectButton" style="display:none;">
				Redirect
			</button>
			<script>
        		const redirectButton = document.getElementById("redirectButton");
				redirectButton.addEventListener("click", function() {
					location.href = "<?php echo add_query_arg(array('tce_page' => 'quotelist'), ''); ?>";
				});
				redirectButton.click();
			</script>
		<?php

    }else{
        echo '<span style="color:red;">Error Saving Quote!</span>';
    }

	echo ob_get_clean();
}


function tce_delete_quote($data){
	ob_start();

    global $wpdb;

    $result_values = $wpdb->delete($data['table_quote_values'], array('quote_id' => $data['quote_id']), array('%d'));

	$result_tokens = $wpdb->delete($data['table_tokens'], array('quote_id' => $data['quote_id']), array('%d'));
	
    $result_info = $wpdb->delete($data['table_quote_info'], array('id' => $data['quote_id']), array('%d'));

    ?>
		<button id="redirectButton" style="display:none;">
			Redirect
		</button>
		<script>
			const redirectButton = document.getElementById("redirectButton");
			redirectButton.addEventListener("click", function() {
				location.href = "<?php echo add_query_arg(array('tce_page' => 'quotelist'), ''); ?>";
			});
			redirectButton.click();
		</script>
	<?php

	echo ob_get_clean();
}


function tce_calculator(){
    $data = tce_fetch_data();
    echo $data['style'];

    switch($data['page']){
        case 'init':
            tce_user_info_form($data);
            break;
        case 'getquote':
            tce_cost_estimator($data);
            break;
        case 'quotelist':
            tce_quote_list($data);
            break;
        case 'singlequote':
            if(tce_is_authenticated($data)){
                tce_single_quote($data);
            } else {
                tce_authenticate($data);
            }
            break;
        case 'savequote':
            tce_save_quote($data);
            break;
        case 'deletequote':
            tce_delete_quote($data);
            tce_quote_list($data);
            break;
        default:
            tce_user_info_form($data);
    }

    echo $data['script'];
}


add_shortcode('tce_calculator', 'tce_calculator');

?>
