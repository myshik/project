<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <div class="record-info">

 <div style="font-size: 12px; color: #999; font-weight: normal">
 <?php echo $date_added; ?> <?php echo $text_comments; ?> <?php echo $text_viewed; ?> <?php echo $viewed; ?>
 </div>

  <div style="font-size: 16px; font-weight: normal">
  <?php echo $description; ?>
  </div>






    <div class="right">
      <div class="description">

        <!--
        <?php if ($manufacturer) { ?>
        <span><?php echo $text_manufacturer; ?></span> <a href="<?php echo $manufacturers; ?>"><?php echo $manufacturer; ?></a><br />
        <?php } ?>


        <span><?php echo $text_model; ?></span> <?php echo $model; ?><br />
        <span><?php echo $text_reward; ?></span> <?php echo $reward; ?><br />
        <span><?php echo $text_stock; ?></span> <?php echo $stock; ?></div>
            -->
      <?php if ($options) { ?>
      <div class="options">
        <h2><?php echo $text_option; ?></h2>
        <br />
        <?php foreach ($options as $option) { ?>
        <?php if ($option['type'] == 'select') { ?>
        <div id="option-<?php echo $option['record_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <select name="option[<?php echo $option['record_option_id']; ?>]">
            <option value=""><?php echo $text_select; ?></option>
            <?php foreach ($option['option_value'] as $option_value) { ?>
            <option value="<?php echo $option_value['record_option_value_id']; ?>"><?php echo $option_value['name']; ?>
            <?php if ($option_value['price']) { ?>
            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
            <?php } ?>
            </option>
            <?php } ?>
          </select>
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'radio') { ?>
        <div id="option-<?php echo $option['record_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <?php foreach ($option['option_value'] as $option_value) { ?>
          <input type="radio" name="option[<?php echo $option['record_option_id']; ?>]" value="<?php echo $option_value['record_option_value_id']; ?>" id="option-value-<?php echo $option_value['record_option_value_id']; ?>" />
          <label for="option-value-<?php echo $option_value['record_option_value_id']; ?>"><?php echo $option_value['name']; ?>
            <?php if ($option_value['price']) { ?>
            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
            <?php } ?>
          </label>
          <br />
          <?php } ?>
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'checkbox') { ?>
        <div id="option-<?php echo $option['record_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <?php foreach ($option['option_value'] as $option_value) { ?>
          <input type="checkbox" name="option[<?php echo $option['record_option_id']; ?>][]" value="<?php echo $option_value['record_option_value_id']; ?>" id="option-value-<?php echo $option_value['record_option_value_id']; ?>" />
          <label for="option-value-<?php echo $option_value['record_option_value_id']; ?>"><?php echo $option_value['name']; ?>
            <?php if ($option_value['price']) { ?>
            (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
            <?php } ?>
          </label>
          <br />
          <?php } ?>
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'image') { ?>
        <div id="option-<?php echo $option['record_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
            <table class="option-image">
              <?php foreach ($option['option_value'] as $option_value) { ?>
              <tr>
                <td style="width: 1px;">
                <input type="radio" name="option[<?php echo $option['record_option_id']; ?>]" value="<?php echo $option_value['record_option_value_id']; ?>" id="option-value-<?php echo $option_value['record_option_value_id']; ?>" /></td>
                <td><label for="option-value-<?php echo $option_value['record_option_value_id']; ?>"><img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" /></label></td>
                <td><label for="option-value-<?php echo $option_value['record_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                    <?php if ($option_value['price']) { ?>
                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                    <?php } ?>
                  </label></td>
              </tr>
              <?php } ?>
            </table>
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'text') { ?>
        <div id="option-<?php echo $option['record_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <input type="text" name="option[<?php echo $option['record_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" />
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'textarea') { ?>
        <div id="option-<?php echo $option['record_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <textarea name="option[<?php echo $option['record_option_id']; ?>]" cols="40" rows="5"><?php echo $option['option_value']; ?></textarea>
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'file') { ?>
        <div id="option-<?php echo $option['record_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <a id="button-option-<?php echo $option['record_option_id']; ?>" class="button"><span><?php echo $button_upload; ?></span></a>
          <input type="hidden" name="option[<?php echo $option['record_option_id']; ?>]" value="" />
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'date') { ?>
        <div id="option-<?php echo $option['record_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <input type="text" name="option[<?php echo $option['record_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="date" />
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'datetime') { ?>
        <div id="option-<?php echo $option['record_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <input type="text" name="option[<?php echo $option['record_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="datetime" />
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'time') { ?>
        <div id="option-<?php echo $option['record_option_id']; ?>" class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <input type="text" name="option[<?php echo $option['record_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="time" />
        </div>
        <br />
        <?php } ?>
        <?php } ?>
      </div>
      <?php } ?>

<div style="clear:both;">&nbsp;</div>
     <?php
      if ($comment_status)
      { ?>
      <div class="comment">

        <div><img src="/catalog/view/theme/default/image/stars-<?php echo $rating; ?>.png" alt="<?php echo $comments; ?>" />&nbsp;&nbsp;
        <br>
        <a onclick="$('a[href=\'#tab-comment\']').trigger('click');"><?php echo $text_comments; ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="$('a[href=\'#tab-comment\']').trigger('click');"><?php echo $text_write; ?></a></div>
        <div style="clear:both;">&nbsp;</div>

      </div>
      <?php } ?>
    </div>
  </div>
  <div id="tabs" class="htabs">

    <?php
    if ($comment_status){
    ?>
    <a href="#tab-comment"><?php echo $tab_comment; ?><ins style="text-decoration: none;" class="comment_count">(<?php echo $comment_count; ?>)</ins></a>
    <?php } ?>

   <?php if ($thumb || $images) { ?>
     <a href="#tab-images"><?php echo $tab_images; ?></a>
    <?php } ?>

    <?php if ($attribute_groups) { ?>
    <a href="#tab-attribute"><?php echo $tab_attribute; ?></a>
    <?php } ?>


    <?php if ($records) { ?>
    <a href="#tab-related"><?php echo $tab_related; ?> (<?php echo count($records); ?>)</a>
    <?php } ?>


    <style>
    .htabs a#tab_advertising div
    {
    }
    </style>

  </div>

  <div id="tab-images" class="tab-content">

   <?php if ($thumb || $images) { ?>
    <div class="left">
      <?php if ($thumb) { ?>
      <div class="image"><a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="fancybox" rel="fancybox"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" /></a></div>
      <?php } ?>
      <?php if ($images) { ?>
      <div class="image-additional">
        <?php foreach ($images as $image) { ?>
        <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="fancybox" rel="fancybox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
        <?php } ?>
      </div>
      <?php } ?>
    </div>
   <?php } ?>

  </div>



  <?php if ($attribute_groups) { ?>
  <div id="tab-attribute" class="tab-content">
    <table class="attribute">
      <?php foreach ($attribute_groups as $attribute_group) { ?>
      <thead>
        <tr>
          <td colspan="2"><?php echo $attribute_group['name']; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
        <tr>
          <td><?php echo $attribute['name']; ?></td>
          <td><?php echo $attribute['text']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
      <?php } ?>
    </table>
  </div>
  <?php } ?>
  <?php
  if ($comment_status)
  { ?>
  <div id="tab-comment" class="tab-content">
    <div id="comment"></div>
    <h2 id="comment-title"><?php echo $text_write; ?></h2>
    <b><?php echo $entry_name; ?></b><br />
    <input type="text" name="name" value="<?php echo $text_login; ?>">
    <br />
    <br />
    <b><?php echo $entry_comment; ?></b>
    <textarea name="text" cols="40" rows="8" style="width: 98%;"></textarea>
    <span style="font-size: 11px;"><?php echo $text_note; ?></span><br />
    <br />
    <b><?php echo $entry_rating; ?></b> <span><?php echo $entry_bad; ?></span>&nbsp;
    <input type="radio" name="rating" value="1" />
    &nbsp;
    <input type="radio" name="rating" value="2" />
    &nbsp;
    <input type="radio" name="rating" value="3" />
    &nbsp;
    <input type="radio" name="rating" value="4" />
    &nbsp;
    <input type="radio" name="rating" value="5" />
    &nbsp; <span><?php echo $entry_good; ?></span><br />
    <br />

    <?php
  if ($captcha_status)
  { ?>
    <b><?php echo $entry_captcha; ?></b><br />
    <input type="text" name="captcha" value="">
    <br />
    <img src="index.php?route=record/record/captcha" alt="" id="captcha" /><br />
     <?php
     }
    ?>



    <div class="buttons">
      <div class="right"><a id="button-comment" class="button"><span><?php echo $button_continue; ?></span></a></div>
    </div>
  </div>
  <?php } ?>
  <?php if ($records) { ?>
  <div id="tab-related" class="tab-content">
    <div class="box-record">
      <?php foreach ($records as $record) { ?>
      <div>
        <?php if ($record['thumb']) { ?>
        <div class="image"><a href="<?php echo $record['href']; ?>"><img src="<?php echo $record['thumb']; ?>" alt="<?php echo $record['name']; ?>" /></a></div>
        <?php } ?>
        <div class="name"><a href="<?php echo $record['href']; ?>"><?php echo $record['name']; ?></a></div>

        <?php if ($record['rating']) { ?>
        <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $record['rating']; ?>.png" alt="<?php echo $record['comments']; ?>" /></div>
        <?php } ?>
        </div>
      <?php } ?>
    </div>
  </div>
  <?php } ?>





  <!--<div class="share">
          <div class="addthis_default_style">
          <a class="addthis_button_compact"><?php echo $text_share; ?></a>
          <a class="addthis_button_email"></a><a class="addthis_button_print"></a>
          <a class="addthis_button_facebook"></a> <a class="addthis_button_twitter"></a>
          </div>

          <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js"></script>


        </div>-->


 <!--<div>
 <a href="#tab-advertising" onclick="$('#tab-advertising').toggle(); return false;" style="font-size: 14px; padding-left: 2px; padding-right: 2px;">
 <?php echo $tab_advertising; ?>
 </a>
 </div>-->



<div id="tab-advertising" class="tab-content"><?php echo $text_advertising; ?>
 </div>

<script>
$('#tab-advertising').toggle();
</script>

   <div style="clear:both;">&nbsp;</div>

  <?php if ($tags) { ?>
  <div class="tags"><b><?php echo $text_tags; ?></b>
    <?php foreach ($tags as $tag) { ?>
    <a href="<?php echo $tag['href']; ?>"><?php echo $tag['tag']; ?></a>,
    <?php } ?>
  </div>
  <?php } ?>
  </div></div>
  <?php echo $content_bottom; ?>



<?php if ($options) { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
<?php foreach ($options as $option) { ?>
<?php if ($option['type'] == 'file') { ?>
<script type="text/javascript"><!--
new AjaxUpload('#button-option-<?php echo $option['record_option_id']; ?>', {
	action: 'index.php?route=record/record/upload',
	name: 'file',
	autoSubmit: true,
	responseType: 'json',
	onSubmit: function(file, extension) {
		$('#button-option-<?php echo $option['record_option_id']; ?>').after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
	},
	onComplete: function(file, json) {
		$('.error').remove();

		if (json.success) {
			alert(json.success);

			$('input[name=\'option[<?php echo $option['record_option_id']; ?>]\']').attr('value', json.file);
		}

		if (json.error) {
			$('#option-<?php echo $option['record_option_id']; ?>').after('<span class="error">' + json.error + '</span>');
		}

		$('.loading').remove();
	}
});
//--></script>
<?php } ?>
<?php } ?>
<?php } ?>
<script type="text/javascript"><!--
$('#comment .pagination a').live('click', function() {
	$('#comment').slideUp('slow');

	$('#comment').load(this.href);

	$('#comment').slideDown('slow');

	return false;
});

$('#comment').load('index.php?route=record/record/comment&record_id=<?php echo $record_id; ?>');

$('#button-comment').bind('click', function() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=record/record/write&record_id=<?php echo $record_id; ?>',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-comment').attr('disabled', true);
			$('#comment-title #comment').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-comment').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(data) {


			if (data.error) {
				$('#comment-title').after('<div class="warning">' + data.error + '</div>');
			}

			if (data.success) {
				$('#comment-title').after('<div class="success">' + data.success + '</div>');
                $('#comment').load('index.php?route=record/record/comment&record_id=<?php echo $record_id; ?>');

                $('.comment_count').html(data.comment_count);

				$('input[name=\'name\']').val(data.login);
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').attr('checked', '');
				$('input[name=\'captcha\']').val('');
			}
		}
	});
});
//--></script>
<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
if ($.browser.msie && $.browser.version == 6) {
	$('.date, .datetime, .time').bgIframe();
}

$('.date').datepicker({dateFormat: 'yy-mm-dd'});
$('.datetime').datetimepicker({
	dateFormat: 'yy-mm-dd',
	timeFormat: 'h:m'
});
$('.time').timepicker({timeFormat: 'h:m'});
//--></script>
<?php echo $footer; ?>