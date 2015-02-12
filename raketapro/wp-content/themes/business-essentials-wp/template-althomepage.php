<?php  
/* 
Template Name: Homepage-Alternate 
*/  
?>

<?php get_header(); ?>

<!-- Start of slider wrapper -->
<section class="slider_wrapper">
<div style="
    text-align: center;
    padding-top: 10px;
">

<div style="
    font-size: 45px;
    color: white;
">Виртуализация и удалённый доступ.</div>

<div style="
    margin-top: 35px;
    text-align: center;
">
<span style="
    color: white;  font-weight: 600;  font-size: 19px;  line-height: 40px;  background-color: #3083a7;
    display: inline-block;
    padding-left: 30px;
    padding-right: 30px;
">Рабочий стол и приложения Windows по требованию с любого устройства, всегда и везде!</span>
</div>

<div style="
    font-size: 18px;
    color: white;
    margin-top: 10px;
">Экономим время и деньги. Пользуйтесь расширенными возможностями компьютера через Интернет.<br>Простой и эффективный способ организации рабочих мест. Быстро. Надёжно.<br>Поехали!</div>

</div>

<!-- <iframe width="100%" height="551" src="http://www.youtube.com/embed/7hoqO36CVRM?&amp;rel=0&amp;theme=light&amp;showinfo=0&amp;hd=1&amp;autohide=1&amp;color=white" frameborder="0" allowfullscreen="" style="
    bottom: 0;
    margin-bottom: -8px;
    width: 100%;
    padding-top: 10px;
"></iframe> -->

    <img class="demovideo" src="http://raketa.pro/wp-content/uploads/2013/03/image_header.png">
    <div id="player"></div>
<!-- <?php 
if ( has_post_thumbnail() ) {  ?>

<?php the_post_thumbnail('slide'); ?>

<?php } ?> -->

<!-- Start of clear fix --><div class="clear"></div>

</section><!-- End of slider wrapper -->

</div><!-- End of content wrapper -->

<!-- Clear Fix --><div class="clear"></div>

</div><!-- End of header wrapper -->

<!-- Start of message wrapper -->
<div id="message_wrapper">

<!-- Start of content wrapper -->
<div class="content_wrapper">

<?php 
if ( function_exists( 'get_option_tree' ) ) {
$homepagemessageleft = get_option_tree( 'vn_homepagemessageleft' );
} ?>

<?php if ($homepagemessageleft != ('')){ ?>

<!-- Start of contentleft -->
<div class="contentleft">
<p><?php echo stripslashes($homepagemessageleft); ?></p>

</div><!-- End of contentleft -->

<?php } else { } ?>

<?php 
if ( function_exists( 'get_option_tree' ) ) {
$homepagemessageright = get_option_tree( 'vn_homepagemessageright' );
} ?>

<?php if ($homepagemessageright != ('')){ ?>

<!-- Start of contentright -->
<div class="contentright">
<?php echo $homepagemessageright; ?>

</div><!-- End of contentright -->

<?php } else { } ?>

</div><!-- End of content wrapper -->

<!-- Clear Fix --><div class="clear"></div>

</div><!-- End of message wrapper -->

<!-- Start of content wrapper -->
<div id="contentwrapper">

<!-- Start of content wrapper -->
<div class="content_wrapper">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php the_content('        '); ?> 

<?php endwhile; endif; ?>

<?php wp_reset_query(); ?>

</div><!-- End of content wrapper -->

<!-- Clear Fix --><div class="clear"></div>

</div><!-- End of content wrapper -->

<?php get_footer(); ?>