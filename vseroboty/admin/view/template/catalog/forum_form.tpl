<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background: url('view/image/forum.png') 2px 9px no-repeat;"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div class="htabs">
        <?php foreach ($languages as $language) { ?>
        <a tab="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
		<?php } ?>
      </div>
      <?php foreach ($languages as $language) { ?>
      <div id="language<?php echo $language['language_id']; ?>">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_title; ?></td>
            <td><input name="forum_description[<?php echo $language['language_id']; ?>][title]" size="100" value="<?php echo isset($forum_description[$language['language_id']]) ? $forum_description[$language['language_id']]['title'] : ''; ?>" />
              <?php if (isset($error_title[$language['language_id']])) { ?>
              <span class="error"><?php echo $error_title[$language['language_id']]; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_preview; ?></td>
            <td><textarea name="forum_description[<?php echo $language['language_id']; ?>][preview]" cols="103" rows="4" id="preview<?php echo $language['language_id']; ?>"><?php echo isset($forum_description[$language['language_id']]) ? $forum_description[$language['language_id']]['preview'] : ''; ?></textarea>
              <?php if (isset($error_preview[$language['language_id']])) { ?>
              <span class="error"><?php echo $error_preview[$language['language_id']]; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_description; ?></td>
            <td><textarea name="forum_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($forum_description[$language['language_id']]) ? $forum_description[$language['language_id']]['description'] : ''; ?></textarea>
              <?php if (isset($error_description[$language['language_id']])) { ?>
              <span class="error"><?php echo $error_description[$language['language_id']]; ?></span>
              <?php } ?></td>
          </tr>
        </table>
      </div>
	  <?php } ?>
      <table class="form">
        <tr>
          <td><?php echo $entry_image; ?><input name="hdimage" id="hdimage" type="hidden" value="<?php echo $image; ?>" /><input id="hdHTTPS" name="hdHTTPS" type="hidden" value="<?php echo HTTP_CATALOG; ?>" /></td>
          <td><div id="postimage"></div></td>
        </tr>
        <tr>
          <td><?php echo $entry_sticky; ?></td>
          <td><select name="sticky">
                <?php if ($sticky) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_author; ?></td>
          <td><input name="author" value="<?php echo $author; ?>" />
          </td>
        </tr>
		<tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="status">
                <?php if ($status) { ?>
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
          <td><input name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
	$(function(){
		var id = $('#hdimage').val();
		var https = $('#hdHTTPS').val();
		if(!id){
			id = 'forum_new.png';
		}
		
		$('#postimage').load(https + '/forum_images.php', {id: id});
	});
	
//--></script>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
//--></script>
<script type="text/javascript"><!--
$.tabs('.htabs a'); 
//--></script>
<?php echo $footer; ?>