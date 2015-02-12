<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/forum.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons">
    <!--<?php if($checkTable==0){ ?>
    <a onclick="location = '<?php echo $gen_table; ?>'" class="button"><span><?php echo $button_table; ?></span></a>
    <?php } ?>-->
    <a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a><a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="list">
        <thead>
          <tr>
            <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
            <td class="left"><?php if ($sort == 'pd.name') { ?>
              <a href="<?php echo $sort_product; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_subject; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_product; ?>"><?php echo $column_subject; ?></a>
              <?php } ?></td>
            
            <td class="right"><?php echo $column_readwrite; ?></td>
            <td class="right"><?php if ($sort == 'r.status') { ?>
              <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
              <?php } ?></td>
            <td class="right"><?php if ($sort == 'r.date_added') { ?>
              <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
              <?php } ?></td>
            <td class="right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($forums) { ?>
          <?php foreach ($forums as $forum) { ?>
          <tr>
            <td style="text-align: center;"><?php if ($forum['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $forum['forum_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $forum['forum_id']; ?>" />
              <?php } ?></td>
            <td class="left"><?php echo $forum['name']; ?><br /><strong><?php echo $column_createdby; ?></strong> <span class="right">[ <?php echo $forum['author']; ?> ]</span></td>
            
            <td class="right"><?php echo $column_read; ?>: <strong><?php echo $forum['read']; ?></strong> / <?php echo $column_post; ?>: <strong><?php echo $forum['post']; ?></strong></td>
            <td class="right"><?php echo $forum['status']; ?></td>
            <td class="right"><?php echo $forum['date_added']; ?> <?php echo $forum['time_added']; ?></td>
            <td class="right"><?php foreach ($forum['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<?php echo $footer; ?>