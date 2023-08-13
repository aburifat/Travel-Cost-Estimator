<?php

function is_valid_image($image_url){
	if(empty($image_url))return false;
	$file_extension = pathinfo($image_url, PATHINFO_EXTENSION);
	$allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
	if (in_array(strtolower($file_extension), $allowed_extensions)) return true;
	return false;
}

function tce_calculator(){
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
		}
		.field_values{
			padding: 20px;
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
					echo " <span style='color:red;'>(필수)</span>";
				}
			?>
		</div>
		<div class="field_values" id="field_values_<?php echo $field->id; ?>">
			<?php
			if(is_valid_image($field->feature_image)){
				?>
				<div class="field_feature_image"><img src="<?php echo $field->feature_image; ?>"></div>
				<?php
			}
			?>
			<div>
			<?php
			$values = $wpdb->get_results("SELECT * FROM $table_values WHERE field_id = $field->id");
			foreach($values as $value){
				?>
					<?php
					if($field->value_type == 2){
						?>
						<img src="<?php echo $value->image; ?>">
						<?php
					}
					?>
					<input type="checkbox" class="tce_value_checkbox" name="myCheckbox" id="value_<?php echo $value->id; ?>" value="<?php echo $value->price; ?>">
					<label for="value_<?php echo $value->id; ?>"><?php echo $value->text; ?></label>
				<?php
			}
			?>
			</div>
		</div>
	<?php } ?>
	<?php
	return ob_get_clean();
}

add_shortcode('tce_calculator', 'tce_calculator');

?>
