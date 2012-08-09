<?php
	
	$dn_show_categories = get_option('dn_show_categories');
	$dn_category_sorting_effect = get_option('dn_category_sorting_effect');
	
	$dn_pagination = get_option('dn_pagination');
	$dn_albums_per_page = get_option('dn_albums_per_page');
	
	$default_video_width = get_option('default_video_width');
	$default_video_height = get_option('default_video_height');

	if( isset($_POST['dn_save_config']) )
	{
		$dn_show_categories = $_POST['dn_show_categories'] ? 1 : 0;
		$dn_category_sorting_effect = $_POST['dn_category_sorting_effect'];
		
		$dn_pagination = $_POST['dn_pagination'] ? 1 : 0;
		$dn_albums_per_page = $_POST['dn_albums_per_page'];
		
		$default_video_width = $_POST['default_video_width'];
		$default_video_height = $_POST['default_video_height'];
		
		$default_video_width = is_numeric($default_video_width) ? $default_video_width : 640;
		$default_video_height = is_numeric($default_video_height) ? $default_video_height : 360;
		
		update_option('dn_show_categories', $dn_show_categories);
		update_option('dn_category_sorting_effect', $dn_category_sorting_effect);
		
		update_option('dn_pagination', $dn_pagination);
		update_option('dn_albums_per_page', $dn_albums_per_page);
		
		update_option('default_video_width', $default_video_width);
		update_option('default_video_height', $default_video_height);
		
		?>
		<div class="success">Changes have been saved!</div>
		<?php
	}

?>
<h3>Categories</h3>

<table border="0" cellpadding="2" cellspacing="2">
	<tbody>
		<tr>
			<td width="140">
				<label for="dn_show_categories">Enable Categorizing:</label>
			</td>
			<td>
				<input type="checkbox" name="dn_show_categories" id="dn_show_categories" <?php echo $dn_show_categories ? ' checked="checked"' : ''; ?> />
			</td>
		</tr>
		<tr>
			<td>
				<label for="dn_show_categories">Sort Animation Type:</label>
			</td>
			<td>
				<select name="dn_category_sorting_effect" id="dn_category_sorting_effect">
					<option value="fade">Fade</option>
					<option value="absolute"<?php echo $dn_category_sorting_effect == 'absolute' ? ' selected' : ''; ?>>Absolute</option>
					<option value="plain"<?php echo $dn_category_sorting_effect == 'plain' ? ' selected' : ''; ?>>Plain</option>
				</select>
			</td>
		</tr>
	</tbody>
</table>

<br />

<h3>Pagination</h3>

<table border="0" cellpadding="2" cellspacing="2">
	<tbody>
		<tr>
			<td width="140">
				<label for="dn_pagination">Enable Pagination:</label>
			</td>
			<td>
				<input type="checkbox" name="dn_pagination" id="dn_pagination" <?php echo $dn_pagination ? ' checked="checked"' : ''; ?> />
			</td>
		</tr>
		<tr>
			<td>
				<label for="dn_albums_per_page">Albums per Page:</label>
			</td>
			<td>
				<select name="dn_albums_per_page" id="dn_albums_per_page">
				<?php
					for($i=3; $i<=3*10; $i+=3)
					{
						?>
						<option<?php echo $dn_albums_per_page == $i ? ' selected' : ''; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
						<?php
					}
				?>
				</select>
			</td>
		</tr>
	</tbody>
</table>

<br />

<h3>Video Settings</h3>

<table border="0" cellpadding="2" cellspacing="2">
	<tbody>
		<tr>
			<td width="140">
				<label for="default_video_width">Video Width:</label>
			</td>
			<td>
				<input type="text" name="default_video_width" id="default_video_width" class="input" size="5" value="<?php echo $default_video_width; ?>" />
			</td>
		</tr>
		<tr>
			<td width="140">
				<label for="default_video_height">Video Height:</label>
			</td>
			<td>
				<input type="text" name="default_video_height" id="default_video_height" class="input" size="5" value="<?php echo $default_video_height; ?>" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<br />
				<input type="submit" class="button" name="dn_save_config" id="dn_save_config" value="Save Changes" />
			</td>
		</tr>
	</tbody>
</table>
<br />