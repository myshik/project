<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content" style="width: 690px; margin-left: 10px;  float:left; margin-bottom: 120px; "><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <b><?php echo $text_critea; ?></b>
  <div class="content">
    <p><?php echo $entry_search; ?>
      <?php if ($filter_name) { ?>
      <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" />
      <?php } else { ?>
      <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" onclick="this.value = '';" onkeydown="this.style.color = '000000'" style="color: #999;" />
      <?php } ?>
      <select name="filter_blog_id">
        <option value="0"><?php echo $text_blog; ?></option>
        <?php foreach ($categories as $blog_1) { ?>
        <?php if ($blog_1['blog_id'] == $filter_blog_id) { ?>
        <option value="<?php echo $blog_1['blog_id']; ?>" selected="selected"><?php echo $blog_1['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $blog_1['blog_id']; ?>"><?php echo $blog_1['name']; ?></option>
        <?php } ?>
        <?php foreach ($blog_1['children'] as $blog_2) { ?>
        <?php if ($blog_2['blog_id'] == $filter_blog_id) { ?>
        <option value="<?php echo $blog_2['blog_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $blog_2['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $blog_2['blog_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $blog_2['name']; ?></option>
        <?php } ?>
        <?php foreach ($blog_2['children'] as $blog_3) { ?>
        <?php if ($blog_3['blog_id'] == $filter_blog_id) { ?>
        <option value="<?php echo $blog_3['blog_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $blog_3['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $blog_3['blog_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $blog_3['name']; ?></option>
        <?php } ?>
        <?php } ?>
        <?php } ?>
        <?php } ?>
      </select>
      <?php if ($filter_sub_blog) { ?>
      <input type="checkbox" name="filter_sub_blog" value="1" id="sub_blog" checked="checked" />
      <?php } else { ?>
      <input type="checkbox" name="filter_sub_blog" value="1" id="sub_blog" />
      <?php } ?>
      <label for="sub_blog"><?php echo $text_sub_blog; ?></label>
    </p>
    <?php if ($filter_description) { ?>
    <input type="checkbox" name="filter_description" value="1" id="description" checked="checked" />
    <?php } else { ?>
    <input type="checkbox" name="filter_description" value="1" id="description" />
    <?php } ?>
    <label for="description"><?php echo $entry_description; ?></label>
  </div>
  <div class="buttons">
    <div class="right"><a id="button-search" class="button"><span><?php echo $button_search; ?></span></a></div>
  </div>
  <h2><?php echo $text_search; ?></h2>
  <?php if ($records) { ?>


  <div class="record-list">
    <?php foreach ($records as $record) { ?>
    <div>
      <?php if ($record['thumb']) { ?>
      <div class="image"><a href="<?php echo $record['href']; ?>"><img src="<?php echo $record['thumb']; ?>" title="<?php echo $record['name']; ?>" alt="<?php echo $record['name']; ?>" /></a></div>
      <?php } ?>
      <div class="name"><a href="<?php echo $record['href']; ?>"><?php echo $record['name']; ?></a></div>
      <div class="description"><?php echo $record['description']; ?></div>
      <?php if ($record['rating']) { ?>
      <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $record['rating']; ?>.png" alt="<?php echo $record['reviews']; ?>" /></div>
      <?php } ?>
 </div>
    <?php } ?>
  </div>

  <div class="record-filter" style="clear: both;">
    <div class="limit" style="float:left;"><?php echo $text_limit; ?>
      <select onchange="location = this.value;">
        <?php foreach ($limits as $limits) { ?>
        <?php if ($limits['value'] == $limit) { ?>
        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
    <div class="sort" style="margin-left: 5px; float:left;"><?php echo $text_sort; ?>
      <select onchange="location = this.value;">
        <?php foreach ($sorts as $sorts) { ?>
        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </div>
  </div>


  <div class="pagination"><?php echo $pagination; ?></div>
  <?php } else { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <?php }?>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript">
$('#content input[name=\'filter_name\']').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#button-search').trigger('click');
	}
});

$('#button-search').bind('click', function() {
	url = 'index.php?route=record/search';

	var filter_name = $('#content input[name=\'filter_name\']').attr('value');

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_category_id = $('#content select[name=\'filter_category_id\']').attr('value');

	if (filter_category_id > 0) {
		url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
	}

	var filter_sub_category = $('#content input[name=\'filter_sub_category\']:checked').attr('value');

	if (filter_sub_category) {
		url += '&filter_sub_category=true';
	}

	var filter_description = $('#content input[name=\'filter_description\']:checked').attr('value');

	if (filter_description) {
		url += '&filter_description=true';
	}

	location = url;
});

</script>

<?php echo $footer; ?>