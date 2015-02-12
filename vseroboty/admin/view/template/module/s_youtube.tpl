<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/module.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
    <table class="form">
    <tr>
        <td><?php echo $entry_position; ?></td>
        <td><select name="s_youtube_position">
            <?php if ($s_youtube_position == 'left') { ?>
            <option value="left" selected="selected"><?php echo $text_left; ?></option>
            <?php } else { ?>
            <option value="left"><?php echo $text_left; ?></option>
            <?php } ?>
            <?php if ($s_youtube_position == 'right') { ?>
            <option value="right" selected="selected"><?php echo $text_right; ?></option>
            <?php } else { ?>
            <option value="right"><?php echo $text_right; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><?php echo $entry_status; ?></td>
        <td><select name="s_youtube_status">
            <?php if ($s_youtube_status) { ?>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <option value="0"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } ?>
          </select></td>
      </tr>


      <tr>
        <td><?php echo $entry_sort_order; ?></td>
        <td><input type="text" name="s_youtube_sort_order" value="<?php echo $s_youtube_sort_order; ?>" size="1" />
	<?php if ($error_sort_order) { ?>
           <div class="error"><?php echo $error_sort_order; ?></div>
        <?php } ?>
	</td>
      </tr>      
      
      <tr>
        <td><?php echo $entry_code; ?></td>
        <td><input type="text" name="s_youtube_code" value="<?php echo $s_youtube_code; ?>" size="10" />
	<?php echo $entry_help; ?>
	<?php if ($error_code) { ?>
           <div class="error"><?php echo $error_code; ?></div>
        <?php } ?>
	</td>
      </tr>
      <tr>
        <td><?php echo $entry_author; ?></td>
        <td>LegionStudio<br /><br />
 	    Email: <a href="mailto:info@legionstudio.net">info@LegionStudio.net</a><br />
	    Web: <a href="http://www.legionstudio.net">www.LegionStudio.net</a><br />
	</td>
    </tr>
    <tr>
        <td style="vertical-align: middle;"><?php echo $entry_version_status ?></td>
	    <td style="vertical-align: middle;">Web: <a href="http://www.legionstudio.net">www.LegionStudio.net</a></td>
      </tr>
    </table>
  </div>
</form>
  </div>
  </div>
<?php echo $footer; ?>