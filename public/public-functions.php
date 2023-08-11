<?php

function tce_calculator(){
	ob_start();
	global $wpdb;
	$table_fields = $wpdb->prefix . "eg_tce_fields";
	$table_values = $wpdb->prefix . "eg_tce_values";
	$disclaimer = get_option('tce_disclaimer_option');
	$the_fields = $wpdb->get_results("SELECT * FROM $table_fields");
	
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
	<table class="tce-fields-table">
		<tr>
			<textarea name="disclaimer" rows="10" readonly><?php echo $disclaimer; ?></textarea>
		</tr>
		<tr>
			<input type="checkbox" class="tce_value_checkbox" name="myCheckbox" value="1">
			<input type="checkbox" class="tce_value_checkbox" name="myCheckbox" value="2">
			<input type="checkbox" class="tce_value_checkbox" name="myCheckbox" value="3">
			<input type="checkbox" class="tce_value_checkbox" name="myCheckbox" value="4">
			<input type="checkbox" class="tce_value_checkbox" name="myCheckbox" value="5">
		</tr>
	</table>
	<?php
	return ob_get_clean();
}

add_shortcode('tce_calculator', 'tce_calculator');

?>
