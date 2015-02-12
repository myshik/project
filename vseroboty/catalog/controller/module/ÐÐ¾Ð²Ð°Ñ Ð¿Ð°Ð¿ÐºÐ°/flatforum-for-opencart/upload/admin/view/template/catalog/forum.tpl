<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading"><h1><?php echo $heading_title; ?></h1></div>
    <div class="content">
      <div align="center"><iframe id="forum" frameborder="0" onLoad="getHeight();" height="1" width="100%" scrolling="auto" src="../forum/index.php?action=admin"></iframe></div>
    </div>
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
<?php echo $footer; ?>