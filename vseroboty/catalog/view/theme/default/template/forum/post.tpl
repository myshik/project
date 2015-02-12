<?php if ($posts) { ?>
	<div class="subject1">
    <h3 style="padding:0; margin:5px 0 0 0;"><?php echo $forums['title']; ?></h3>
    <div class="createby">by <strong><?php echo $forums['author']; ?></strong> >> <?php echo $forums['date_added']; ?> <?php echo $forums['time_added']; ?></div>
    <?php echo $forums['description']; ?>
    </div>
        
<?php foreach ($posts as $post) { ?>
	<?php if(($int/2)!= intval($int / 2)){
        	$subclass = "subject1";
        }else{
        	$subclass = "subject2";
        } ?>
    <div class="<?php echo $subclass; ?>">
    <h3 style="padding:0; margin:5px 0 0 0;">RE: <?php echo $forums['title']; ?></h3>
    <div class="createby">by <strong><?php echo $post['author']; ?></strong> >> <?php echo $post['date_added']; ?><?php echo $post['time_added']; ?></div>
    <p><?php echo $post['text']; ?></p>
    <p style="margin:0; padding:0; float:right;">IP: <strong><?php echo $post['ip']; ?></strong></p>
    <div class="clear"></div>
    </div>
	<?php $int++; ?>
<?php } ?>
<?php } ?>
