<?php
/*
 * The default template for displaying content
 */
?>

<!-- Start of blog wrapper -->
<div class="blog_wrapper">

<!-- Start of post class -->
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<h2 class="blue"><a href="<?php the_permalink (); ?>"><?php the_title (); ?></a></h2>

<?php 
if ( has_post_thumbnail() ) {  ?>

<!-- Clear Fix --><div class="clear"></div>

<?php the_post_thumbnail('slide'); ?>

<!-- Clear Fix --><div class="clearbig"></div>

<?php } ?>

<?php the_excerpt (); ?>

<!-- Start of post details -->
<div class="post_details">

<!-- Start of post date -->
<div class="post_date">
<?php the_time('d.m.Y') ?>

</div><!-- End of post date -->

<?php if ('open' == $post->comment_status) { ?>

<!-- Start of post comment -->
<div class="post_comment">
&nbsp; 
<?php
if (get_comments_number()==1) { ?>

<?php comments_popup_link( '0', '1', '%', 'comments-link', ''); ?>

<?php _e( 'COMMENT', 'essentials' ); ?>

<?php } else { ?>

<?php comments_popup_link( '0', '1', '%', 'comments-link', ''); ?>

<?php _e( 'COMMENTS', 'essentials' ); ?>

<?php } ?>

</div><!-- End of post comment -->

<?php } ?>

<!-- Start of post read more -->
<div class="post_read_more">
<a href="<?php the_permalink (); ?>"><?php _e( 'Подробнее', 'essentials' ); ?><img src="<?php echo get_template_directory_uri(); ?>/img/red-hoverarrow.png" width="15" height="15" alt="red arrow" /></a>

</div><!-- End of post read more -->

</div><!-- End of post details -->

</div><!-- End of post class -->

</div><!-- End of blog wrapper -->