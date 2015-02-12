<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <h1><?php echo $heading_title; ?><span style="padding:0; margin:5px 0 0 0;"></span></h1>
    </div>
  </div>
  <div class="middle">
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="cart">
      
	<div id="forum">
    	<div id="post">
        <div class="subject1">
        <h3 style="padding:0; margin:5px 0 0 0;"><?php echo $forums['title']; ?></h3>
        <div class="createby">by <strong><?php echo $forums['author']; ?></strong> >> <?php echo $forums['date_added']; ?> <?php echo $forums['time_added']; ?></div>
        <?php echo $forums['description']; ?>
        </div>
        
        
        <?php foreach ($posts as $post) { ?>
        <?php if(($int/2)!= intval($int / 2)){
        	$strclass = "subject1";
        }else{
        	$strclass = "subject2";
        } ?>
        <div class="<?php echo $strclass; ?>">
        <h3 style="padding:0; margin:5px 0 0 0;">RE: <?php echo $forums['title']; ?></h3>
        <div class="createby">by <strong><?php echo $post['author']; ?></strong> >> <?php echo $post['date_added']; ?><?php echo $post['time_added']; ?></div>
        <p><?php echo $post['text']; ?></p>
        <p style="margin:0; padding:0; float:right;">IP: <strong><?php echo $post['ip']; ?></strong></p>
        <div class="clear"></div>
        </div>
        <?php $int++; ?>
        <?php } ?>
        
        </div>
	</div>
      
    </form>
  </div>
  <div class="bottom">
    <div id="forum">
        <div class="subject1">
        <div id="tab_post" class="tab_page">
          
          <div class="heading" id="post_title"><?php echo $text_write; ?></div>
          <div class="content">
          	<b><?php echo $entry_name; ?></b><br />
            <input type="text" name="name" value="<?php echo $name; ?>" /><br />
            <b><?php echo $entry_post; ?></b>
            <textarea name="text" style="width: 98%;" rows="8"></textarea>
            <span style="font-size: 11px;"><?php echo $text_note; ?></span><br />
            <br />
            <img src="index.php?route=forum/board/captcha" id="captcha" /><br />
            <b><?php echo $entry_captcha; ?></b><br />
            <input type="text" name="captcha" value="" autocomplete="off" />
            
            <input type="hidden" name="myip" id="myip" value="<?php echo $myip; ?>" />
          </div>
          <div class="buttons">
            <table>
              <tr>
                <td align="right"><a onclick="createpost();" class="button"><span><?php echo $button_createpost; ?></span></a></td>
              </tr>
            </table>
          </div>
        </div>
        </div>
    </div>
  </div>
</div>

<script type="text/javascript"><!--		

function createpost() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=forum/board/write&forum_id=<?php echo $forum_id; ?>',
		dataType: 'json',
		data: 'text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&myip=' + encodeURIComponent($('input[name=\'myip\']').val()) + '&name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#post_button').attr('disabled', 'disabled');
			$('#post_title').after('<div class="wait"><img src="catalog/view/theme/default/image/loading_1.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#post_button').attr('disabled', '');
			$('.wait').remove();
		},
		success: function(data) {
			if (data.error) {
				$('#post_title').after('<div class="warning">' + data.error + '</div>');
			}
			
			if (data.success) {
				$('#post_title').after('<div class="success">' + data.success + '</div>');
								
				
				$('textarea[name=\'text\']').val('');	
				$('input[name=\'captcha\']').val('');
				//$('input[name=\'name\']').val('');
				
				
				$('#post').load('index.php?route=forum/board/review&forum_id=<?php echo $forum_id; ?>');
			}
		}
	});
}
//--></script>
<?php echo $footer; ?> 