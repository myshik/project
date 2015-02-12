<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <h1><?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="middle">
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="cart">
      <table class="cart">
        <tr>
          <th align="center">&nbsp;</th>
          <th align="center"><?php echo $column_title; ?></th>
          <th align="right" width="160"><?php echo $column_post; ?></th>
        </tr>
        
        <?php if ($forums) { ?>
        <?php foreach ($forums as $forum) { ?>
        <?php if($forum['status']){ ?>
        <tr class="<?php echo $class; ?>">
          <td width="16" align="center"><img src="http://opencart.checkyoursites.com/modules/forum/images/<?php echo $forum['image']; ?>" /></td>
          <td align="left" valign="top"><strong>
          <?php foreach ($forum['action'] as $action) { ?><a href="<?php echo $action['href']; ?>"><?php echo $forum['name']; ?></a><?php } ?></strong><br />
          <span style="color:#ccc;"><?php echo $forum['preview']; ?></span><br />
          <span style="font-size:10px;"><strong>Create by:</strong> [ <?php echo $forum['author']; ?> ]</span></td>
          <td align="right" valign="top"><?php $forum['status']; ?>
            Read: <strong><?php echo $forum['read']; ?></strong> / Post: <strong><?php echo $forum['post']; ?></strong><br />
          <span style="font-size:9px; color:#666;"><?php echo $forum['date_added']; ?> <?php echo $forum['time_added']; ?></span></td>
        </tr>
        <tr class="<?php echo $class; ?>">
          <td colspan="3" align="center"><div id="forumline"></div></td>
        </tr>
        <?php } ?>
        <?php } ?>
        <?php } else { ?>
          
          <tr>
            <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
      </table>
	  
      
        <table>
          <tr>
            <td align="right"><?php if ($logged) { ?><a onclick="$('#cart').submit();" class="button"><span><?php echo $button_createtopic; ?></span></a><?php } ?></td>
          </tr>
      </table>
      
    </form>
  </div>
  <div class="bottom"><div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<?php echo $footer; ?> 