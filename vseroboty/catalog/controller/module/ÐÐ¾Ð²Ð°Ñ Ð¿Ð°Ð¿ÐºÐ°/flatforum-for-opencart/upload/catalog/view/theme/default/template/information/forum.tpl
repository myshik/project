<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
<div class="box">
<div class="box-heading"><?php echo $heading_title; ?></div>
<div class="box-content"> 
<div align="center"><iframe id="forum" frameborder="0" onLoad="getHeight();" height="1" width="98%" scrolling="auto" src="./forum/"></iframe></div>
</div> 
</div>
<script type="text/javascript">
function getHeight()
{
var frame_height=
document.getElementById('forum').contentWindow.
document.body.scrollHeight;
document.getElementById('forum').height=frame_height;
}
</script>
<?php echo $content_bottom; ?></div>
<?php echo $footer; ?>