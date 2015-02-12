<!DOCTYPE HTML>

<html <?php language_attributes(); ?>>

<!--[if IE 7 ]>    <html class= "ie7"> <![endif]-->
<!--[if IE 8 ]>    <html class= "ie8"> <![endif]-->
<!--[if IE 9 ]>    <html class= "ie9"> <![endif]-->

<!--[if lt IE 9]>
   <script>
      document.createElement('header');
      document.createElement('nav');
      document.createElement('section');
      document.createElement('article');
      document.createElement('aside');
      document.createElement('footer');
   </script>
<![endif]-->

<title><?php echo get_option('blogname'); ?><?php wp_title(); ?></title>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&subset=latin,cyrillic-ext,cyrillic' rel='stylesheet' type='text/css'>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.js"></script>
<script type="text/javascript" src="http://tanks.mail.ru/themes/default/js/jquery.rYouTube.min.js"></script>

<script type="text/javascript"> 
	$(window).load(function(){

		/* $('img.demovideo').css('cursor', 'pointer');

			 	$('body').delegate('img.demovideo','click', function(){
        				video = '<iframe class="demovideo" width="100%" height="551px" src="'+ $(this).attr('data-video') +'"></iframe>';
        				$(this).replaceWith(video);
				}); */
$('#video').rYouTube({
    video: {
        id: '2LIKdh8qRT0',
        width: 300
    }
});
	});	
</script>

<!-- <script type="text/javascript"> 
$(function() {
    $("img.demovideo")
        .mouseover(function() { 
            var src = $(this).attr("src").match('http://raketa.pro/wp-content/uploads/2013/03/image_header') + "_2.png";
            $(this).attr("src", src);
        })
        .mouseout(function() {
            var src = $(this).attr("src").replace("_2.png", ".png");
            $(this).attr("src", src);
        });
});
</script> -->


<?php 
if ( function_exists( 'get_option_tree') ) {
  $specstyle = get_option_tree( 'vn_specstyle' );
  }
?>
<?php if ($specstyle != ('')){ ?>

<link href="<?php echo ($specstyle); ?>" rel="stylesheet" type="text/css" media="screen" />

<?php } else { ?>

<link href="<?php bloginfo('stylesheet_url') ?>" rel="stylesheet" type="text/css" media="screen" />

<?php } ?>

<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

 <!-- *************************************************************************
*****************                FAVICON               ********************
************************************************************************** -->

<?php 
if ( function_exists( 'get_option_tree') ) {
  $favicon = get_option_tree( 'vn_favicon' );
}
?>
<link rel="shortcut icon" href="<?php echo ($favicon); ?>" />

<!--[if lt IE 9]>
<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- *************************************************************************
*****************              CUSTOM CSS              ********************
************************************************************************** -->


<style type="text/css">
<?php 
if ( function_exists( 'get_option_tree') ) {
  $css = get_option_tree( 'vn_customcss' );
}
?>
<?php echo ($css); ?>
	
</style>

<?php wp_head(); ?> 

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&language=ru"></script>

</head>

<?php $theme_options = get_option('option_tree'); ?>
<!-- onload="initialize();initialize2();" onunload="GUnload()" --> 
<body <?php body_class(); ?>>

<img src="http://raketa.pro/wp-content/uploads/2013/03/image_header_2.png" style="display:none;">

<!-- Start of top wrapper -->
<div id="top_wrapper">

<!-- Start of content wrapper -->
<div class="content_wrapper">

<!-- Start of topsubmenu -->
<div class="topsubmenu">

<?php wp_nav_menu(
array(
'theme_location'  => 'topsub',
)
);
?>

</div><!-- End of topsubmenu -->

<!-- Start of searchbox -->
<div id="searchbox">

<!-- Start of search box -->
<?php get_search_form(); ?>
<!-- End of searchbox -->

</div><!-- End of searchbox -->

</div><!-- End of content wrapper -->

<!-- Clear Fix --><div class="clear"></div>

</div><!-- End of top wrapper -->

<!-- Start of header wrapper -->
<div id="header_wrapper">

<!-- Start of content wrapper -->
<div class="content_wrapper">

<!-- Start of logo -->
<div id="logo">
<a href="<?php echo site_url(); ?>"><?php 
if ( function_exists( 'get_option_tree' ) ) {
$logopath = get_option_tree( 'vn_toplogo' );
} ?><img src="<?php echo $logopath; ?>" alt="logo" /></a><div style="
    float: right;
    margin-top: 10px;
    color: white;
    font-weight: 600;
    font-size: 22px;
"><span style="
    font-weight: 100 !important;
">+7 (812) </span>429-72-82</div>

</div><!-- End of logo -->

<!-- Start of top menu wrapper -->
<div class="topmenuwrapper">

<!-- Start of topmenu -->
<nav class="topmenu">
 
<?php wp_nav_menu(
array(
'menu_class' => 'sf-menu',
'theme_location'  => 'primary',
)
);
?>

</nav><!-- End of topmenu -->

<div class="button_red_image" style="margin: 0;"><a href="http://office.raketa.pro/lcab/?action=reg" style="float: right; line-height: 38px;" target="_blank" title="Бесплатный доступ">БЕСПЛАТНЫЙ</a></div>

<!-- <?php 
if ( function_exists( 'get_option_tree' ) ) {
$telephonenumber = get_option_tree( 'vn_telephonenumber' );
} ?>

<?php if ($telephonenumber != ('')){ ?> 

<div class="header_phone">
<?php echo stripslashes($telephonenumber); ?>

</div>

<?php } else { } ?> -->

<!-- Clear Fix --><div class="clear"></div>

</div><!-- End of top menu wrapper -->