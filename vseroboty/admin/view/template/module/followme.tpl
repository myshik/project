<?php
// ------------------------------------------
// Follow Me Version 1.5.3
// For Opencart v1.5.1 / 1.5.2 / 1.5.3 
// Original by KangDJ
// Updated by Lamiaa Ahmed (1.5.0)
// Updated by villagedefrance (1.5.1, 1.5.1 V2)
// Updated by HelderIM (1.5.1 V3) 
// Updated by villagedefrance (1.5.3)
// Подготовлено специально для opencart.ws
// Перевод и адаптация модуля Marianna radiance.com.ua fatalemary@gmail.com 
// ------------------------------------------
?>

<?php echo $header; ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
	<?php if ($error_warning) { ?>
		<div class="warning"><?php echo $error_warning; ?></div>
	<?php } ?>
<div class="box">
	<div class="heading">
		<h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
		<div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a>
			<a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a>
		</div>
	</div>
	<div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
		<table class="form">
			<?php foreach ($languages as $language) { ?>
				<tr>
					<td><?php echo $entry_title; ?></td>
					<td colspan="4"> 
						<input type="text" name="followme_title<?php echo $language['language_id']; ?>" id="followme_title<?php echo $language['language_id']; ?>" size="30" value="<?php echo ${'followme_title' . $language['language_id']}; ?>" />
						<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" /><br />
					</td>
				</tr>
			<?php } ?>
				<tr>
					<td><?php echo $entry_header; ?></td>
					<td colspan="4"> 
						<?php if($followme_header) {
						$checked1 = ' checked="checked"';
						$checked0 = '';
						} else {
						$checked1 = '';
						$checked0 = ' checked="checked"';
						} ?>
						<label for="followme_header_1"><?php echo $entry_yes; ?></label>
						<input type="radio"<?php echo $checked1; ?> id="followme_header_1" name="followme_header" value="1" />
						<label for="followme_header_0"><?php echo $entry_no; ?></label>
						<input type="radio"<?php echo $checked0; ?> id="followme_header_0" name="followme_header" value="0" />
					</td>
				</tr>
				<tr> 
					<td><?php echo $entry_icon; ?></td> 
					<td colspan="4">  
						<?php if($followme_icon) { 
						$checked1 = ' checked="checked"'; 
						$checked0 = ''; 
						} else { 
						$checked1 = ''; 
						$checked0 = ' checked="checked"'; 
						} ?> 
					<label for="followme_icon_1"><?php echo $entry_yes; ?></label> 
					<input type="radio"<?php echo $checked1; ?> id="followme_icon_1" name="followme_icon" value="1" /> 
					<label for="followme_icon_0"><?php echo $entry_no; ?></label> 
					<input type="radio"<?php echo $checked0; ?> id="followme_icon_0" name="followme_icon" value="0" /> 
					</td> 
				</tr>
				<tr> 
					<td><?php echo $entry_box; ?></td> 
					<td colspan="4"> 
						<?php if($followme_box) { 
						$checked1 = ' checked="checked"'; 
						$checked0 = ''; 
						} else { 
						$checked1 = ''; 
						$checked0 = ' checked="checked"'; 
						} ?> 
					<label for="followme_box_1"><?php echo $entry_yes; ?></label> 
					<input type="radio"<?php echo $checked1; ?> id="followme_box_1" name="followme_box" value="1" /> 
					<label for="followme_box_0"><?php echo $entry_no; ?></label> 
					<input type="radio"<?php echo $checked0; ?> id="followme_box_0" name="followme_box" value="0" /> 
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_template; ?></td>
					<td colspan="4"> 
						<?php foreach ($templates as $template) { ?>
							<?php if ($template == $config_template) { ?>
								<span style='color: #336600; padding: 0 5px;'><b><?php echo $template; ?></b></span> 
							<?php } ?>
						<?php } ?>	
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_facebook; ?></td>
					<td><input name="followme_facebook" type="text" size="50" value="<?php echo $followme_facebook; ?>"> 
					</td>
					<td><img src="../image/followme/logo_facebook.gif" alt="" /></td>
					<td><?php echo $entry_facebook_usage; ?></td> 
					<td> 
						<?php if($followme_facebook_usage) { 
						$checked1 = ' checked="checked"'; 
						$checked0 = ''; 
						} else { 
						$checked1 = ''; 
						$checked0 = ' checked="checked"'; 
						} ?> 
					<label for="followme_facebook_usage_1"><?php echo $entry_yes; ?></label> 
					<input type="radio"<?php echo $checked1; ?> id="followme_facebook_usage_1" name="followme_facebook_usage" value="1" /> 
					<label for="followme_facebook_usage_0"><?php echo $entry_no; ?></label> 
					<input type="radio"<?php echo $checked0; ?> id="followme_facebook_usage_0" name="followme_facebook_usage" value="0" /> 
					</td> 
				</tr>
				<tr>
					<td><?php echo $entry_twitter; ?></td>
					<td><input name="followme_twitter" type="text" size="50" value="<?php echo $followme_twitter; ?>"> 
					</td>
					<td><img src="../image/followme/logo_twitter.gif" alt="" /></td>
					<td><?php echo $entry_twitter_usage; ?></td> 
					<td> 
						<?php if($followme_twitter_usage) { 
						$checked1 = ' checked="checked"'; 
						$checked0 = ''; 
						} else { 
						$checked1 = ''; 
						$checked0 = ' checked="checked"'; 
						} ?> 
					<label for="followme_twitter_usage_1"><?php echo $entry_yes; ?></label> 
					<input type="radio"<?php echo $checked1; ?> id="followme_twitter_usage_1" name="followme_twitter_usage" value="1" /> 
					<label for="followme_twitter_usage_0"><?php echo $entry_no; ?></label> 
					<input type="radio"<?php echo $checked0; ?> id="followme_twitter_usage_0" name="followme_twitter_usage" value="0" /> 
					</td> 
				<tr>
				<tr>
					<td><?php echo $entry_google; ?></td>
					<td><input name="followme_google" type="text" size="50" value="<?php echo $followme_google; ?>"> 
					</td>
					<td><img src="../image/followme/logo_google.gif" alt="" /></td>
					<td><?php echo $entry_gplus_usage; ?></td> 
					<td> 
						<?php if($followme_gplus_usage) { 
						$checked1 = ' checked="checked"'; 
						$checked0 = ''; 
						} else { 
						$checked1 = ''; 
						$checked0 = ' checked="checked"'; 
						} ?> 
					<label for="followme_gplus_usage_1"><?php echo $entry_yes; ?></label> 
					<input type="radio"<?php echo $checked1; ?> id="followme_gplus_usage_1" name="followme_gplus_usage" value="1" /> 
					<label for="followme_gplus_usage_0"><?php echo $entry_no; ?></label> 
					<input type="radio"<?php echo $checked0; ?> id="followme_gplus_usage_0" name="followme_gplus_usage" value="0" /> 
					</td> 
				<tr>
				<tr>
					<td><?php echo $entry_odnoklassniki; ?></td>
					<td><input name="followme_odnoklassniki" type="text" size="50" value="<?php echo $followme_odnoklassniki; ?>"> 
					</td>
					<td><img src="../image/followme/logo_odnoklassniki.png" alt="" /></td>
					<td><?php echo $entry_odnoklassniki_usage; ?></td> 
					<td> 
						<?php if($followme_odnoklassniki_usage) { 
						$checked1 = ' checked="checked"'; 
						$checked0 = ''; 
						} else { 
						$checked1 = ''; 
						$checked0 = ' checked="checked"'; 
						} ?> 
					<label for="followme_odnoklassniki_usage_1"><?php echo $entry_yes; ?></label> 
					<input type="radio"<?php echo $checked1; ?> id="followme_odnoklassniki_usage_1" name="followme_odnoklassniki_usage" value="1" /> 
					<label for="followme_odnoklassniki_usage_0"><?php echo $entry_no; ?></label> 
					<input type="radio"<?php echo $checked0; ?> id="followme_odnoklassniki_usage_0" name="followme_odnoklassniki_usage" value="0" /> 
					</td> 
				<tr>
				<tr>
					<td><?php echo $entry_vkontakte; ?></td>
					<td><input name="followme_vkontakte" type="text" size="50" value="<?php echo $followme_vkontakte; ?>"> 
					</td>
					<td><img src="../image/followme/logo_vkontakte.gif" alt="" /></td>
					<td><?php echo $entry_vkontakte_usage; ?></td> 
					<td> 
						<?php if($followme_vkontakte_usage) { 
						$checked1 = ' checked="checked"'; 
						$checked0 = ''; 
						} else { 
						$checked1 = ''; 
						$checked0 = ' checked="checked"'; 
						} ?> 
					<label for="followme_vkontakte_usage_1"><?php echo $entry_yes; ?></label> 
					<input type="radio"<?php echo $checked1; ?> id="followme_vkontakte_usage_1" name="followme_vkontakte_usage" value="1" /> 
					<label for="followme_vkontakte_usage_0"><?php echo $entry_no; ?></label> 
					<input type="radio"<?php echo $checked0; ?> id="followme_vkontakte_usage_0" name="followme_vkontakte_usage" value="0" /> 
					</td> 
				<tr>
				<tr>
					<td><?php echo $entry_youtube; ?></td>
					<td><input name="followme_youtube" type="text" size="50" value="<?php echo $followme_youtube; ?>"> 
					</td>
					<td><img src="../image/followme/logo_youtube.png" alt="" /></td>
					<td><?php echo $entry_youtube_usage; ?></td> 
					<td> 
						<?php if($followme_youtube_usage) { 
						$checked1 = ' checked="checked"'; 
						$checked0 = ''; 
						} else { 
						$checked1 = ''; 
						$checked0 = ' checked="checked"'; 
						} ?> 
					<label for="followme_youtube_usage_1"><?php echo $entry_yes; ?></label> 
					<input type="radio"<?php echo $checked1; ?> id="followme_youtube_usage_1" name="followme_youtube_usage" value="1" /> 
					<label for="followme_youtube_usage_0"><?php echo $entry_no; ?></label> 
					<input type="radio"<?php echo $checked0; ?> id="followme_youtube_usage_0" name="followme_youtube_usage" value="0" /> 
					</td> 
				<tr>
				<tr>
					<td><img src="view/image/valid-xhtml10-blue.png" alt="" /></td>
					<td colspan="4"><b><?php echo $text_module_settings; ?></b></td>
				</tr>
		</table>
        
		<table id="module" class="list">
			<thead>
				<tr>
					<td class="left"><?php echo $entry_layout; ?></td>
					<td class="left"><?php echo $entry_position; ?></td>
					<td class="left"><?php echo $entry_status; ?></td>
					<td class="left"><?php echo $entry_sort_order; ?></td>
					<td></td>
				</tr>
			</thead>
        <?php $module_row = 0; ?>
        <?php foreach ($modules as $module) { ?>
			<tbody id="module-row<?php echo $module_row; ?>">
				<tr>
					<td class="left"><select name="followme_module[<?php echo $module_row; ?>][layout_id]">
					<?php foreach ($layouts as $layout) { ?>
						<?php if ($layout['layout_id'] == $module['layout_id']) { ?>
							<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
						<?php } else { ?>
							<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
						<?php } ?>
					<?php } ?>
					</select></td>
					<td class="left"><select name="followme_module[<?php echo $module_row; ?>][position]">
					<?php if ($module['position'] == 'content_top') { ?>
						<option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
					<?php } else { ?>
						<option value="content_top"><?php echo $text_content_top; ?></option>
					<?php } ?>
					<?php if ($module['position'] == 'content_bottom') { ?>
						<option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
					<?php } else { ?>
						<option value="content_bottom"><?php echo $text_content_bottom; ?></option>
					<?php } ?>
					<?php if ($module['position'] == 'column_left') { ?>
						<option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
					<?php } else { ?>
						<option value="column_left"><?php echo $text_column_left; ?></option>
					<?php } ?>
					<?php if ($module['position'] == 'column_right') { ?>
						<option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
					<?php } else { ?>
						<option value="column_right"><?php echo $text_column_right; ?></option>
					<?php } ?>
					</select></td>
					<td class="left"><select name="followme_module[<?php echo $module_row; ?>][status]">
					<?php if ($module['status']) { ?>
						<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
						<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
						<option value="1"><?php echo $text_enabled; ?></option>
						<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
					</select></td>
					<td class="center">
						<input type="text" name="followme_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" />
					</td>
					<td class="center">
						<a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a>
					</td>
				</tr>
			</tbody>
        <?php $module_row++; ?>
        <?php } ?>
			<tfoot>
				<tr>
					<td colspan="4"></td>
					<td class="center"><a onclick="addModule();" class="button"><span><?php echo $button_add_module; ?></span></a></td>
				</tr>
			</tfoot>
		</table>
    </form>
	</div>
		<br>
		<div style="text-align:center; color:#555;">Follow Me v<?php echo $followme_version; ?></div>
		<br>
		<div style="text-align:center; color:#666;">Credits to KangDJ (Original), Lamiaa Ahmed (1.5.0), Villagedefrance (1.5.1, 1.5.1 V2) and HelderIM (1.5.1 V3). Перевод и адаптация Marianna radiance.com.ua | fatalemary@gmail.com подготовлено специально для сайта opencart.ws</div>
</div>

<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="followme_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="followme_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="followme_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="center"><input type="text" name="followme_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="center"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}
//--></script>
<?php echo $footer; ?>