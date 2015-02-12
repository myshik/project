<div class="box">
  <div class="box-heading"><?php echo $testimonial_title; ?></div>
  <div class="box-content">
    <div class="box-product">

    <table cellpadding="2" cellspacing="0" style="width: 100%;">

      <?php foreach ($testimonials as $testimonial) { ?>
      <tr><td>

          <?php echo $testimonial['description']; ?><br><br>
          <div width=100% style="text-align:left; font-size:10px; margin-bottom:12px; padding-bottom:4px;border-bottom:dotted silver 1px;"><em>&bull;&nbsp;<?php echo $testimonial['title']; ?></em>
<br><br><div align="right"><a class="button" href="<?php echo $more.$testimonial['id']; ?>"><span><?php echo $text_more ; ?></span></a></div>
</div>

       </td>
      </tr>

      <?php } ?>

<tr><td>
	<div width=100% align="right" style="margin-top:5px;margin-left:8px;"><a class="button" href="<?php echo $isitesti; ?>"><span><?php echo $isi_testimonial; ?></span></a>  </div>

</td></tr>
    </table>

	

    </div>
  </div>
</div>

