<div class="box">
<div class="box-heading"><?php echo $news; ?></div>
<div class="box-content">
<div id="content">
    <?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>

  <?php if ($description)
  {
  ?>
  <div class="blog-info">
    <?php if ($thumb && $description) { ?>
    <div class="image" style="float:left; margin-right: 5px;"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" /></div>
    <?php } ?>
    <?php if ($description) { ?>
    <?php echo $description; ?>
    <?php } ?>
  </div>
    <div style="clear: both; line-height: 1px;">&nbsp;</div>
  <?php } ?>



  <?php if ($categories) { ?>
  <h2><?php echo $text_refine; ?></h2>
  <div class="blog-list">
    <?php if (count($categories) <= 5) { ?>
    <ul>
      <?php foreach ($categories as $blog) { ?>
      <li><a href="<?php echo $blog['href']; ?>"><?php echo $blog['name']; ?></a></li>
      <?php } ?>
    </ul>
    <?php } else { ?>
    <?php for ($i = 0; $i < count($categories);) { ?>
    <ul>
      <?php $j = $i + ceil(count($categories) / 4); ?>
      <?php for (; $i < $j; $i++) { ?>
      <?php if (isset($categories[$i])) { ?>
      <li><a href="<?php echo $categories[$i]['href']; ?>"><?php echo $categories[$i]['name']; ?></a></li>
      <?php } ?>
      <?php } ?>
    </ul>
    <?php } ?>
    <?php } ?>
  </div>
  <?php } ?>




  <?php if ($records) { ?>

  <div class="record-list">
    <?php foreach ($records as $record) { ?>
    <div>
      
      <?php if ($record['thumb']) { ?>
      <div class="image"  style="float:left; margin-right: 5px;"><a href="<?php echo $record['href']; ?>"><img src="<?php echo $record['thumb']; ?>" title="<?php echo $record['name']; ?>" alt="<?php echo $record['name']; ?>" /></a></div>
      <?php } ?>
      <div class="name"><a href="<?php echo $record['href']; ?>" style="font-size: 16px;"><?php echo $record['name']; ?></a></div>
      <div class="description"><?php echo $record['description']; ?></div>

      <?php if ($record['date_added']) { ?>
        <div style="font-size: 11px; color: #aaa;"><?php echo $record['date_added']; ?></div>
      <?php } ?>
      
      <?php if ($record['rating']) { ?>
      <div class="rating"><img src="catalog/view/theme/default/image/stars-<?php echo $record['rating']; ?>.png" alt="<?php echo $record['reviews']; ?>" /></div>
      <?php } ?>

  <div style="clear: both; line-height: 1px; margin-bottom: 5px;  border-bottom: 1px solid #BBB;">&nbsp;</div>

    </div>
    <?php } ?>
  </div>


  <?php } ?>
  <?php if (!$categories && !$records) { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><span><?php echo $button_continue; ?></span></a></div>
  </div>
  <?php } ?>
</div>
</div>
</div>