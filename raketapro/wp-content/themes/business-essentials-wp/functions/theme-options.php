<?php
/**
 * Initialize the options before anything else.
 */
add_action( 'admin_init', 'custom_theme_options', 1 );

/**
 * Build the custom settings & update OptionTree.
 */
function custom_theme_options() {
  /**
   * Get a copy of the saved settings array. 
   */
  $saved_settings = get_option( 'option_tree_settings', array() );
  
  /**
   * Custom settings array that will eventually be 
   * passes to the OptionTree Settings API Class.
   */
  $custom_settings = array( 
    'contextual_help' => array( 
      'sidebar'       => ''
    ),
    'sections'        => array( 
      array(
        'id'          => 'setup',
        'title'       => __( 'Setup', 'essentials' )
      )
    ),
    'settings'        => array( 
      array(
        'id'          => 'vn_toplogo',
        'label'       => __( 'Top Logo', 'essentials' ),
        'desc'        => __( 'Upload your logo.', 'essentials' ),
        'std'         => '',
        'type'        => 'upload',
        'section'     => 'setup',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'vn_favicon',
        'label'       => __( 'Favicon', 'essentials' ),
        'desc'        => __( 'Upload your favicon.  16px X 16px .png.', 'essentials' ),
        'std'         => '',
        'type'        => 'upload',
        'section'     => 'setup',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'vn_specstyle',
        'label'       => __( 'Upload Special Stylesheet', 'essentials' ),
        'desc'        => __( 'Upload the color selected stylesheet.', 'essentials' ),
        'std'         => '',
        'type'        => 'upload',
        'section'     => 'setup',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'vn_customcss',
        'label'       => __( 'Custom CSS', 'essentials' ),
        'desc'        => __( 'Use this area to over ride any CSS from the stylesheet with custom CSS.', 'essentials' ),
        'std'         => '',
        'type'        => 'css',
        'section'     => 'setup',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'vn_tracking',
        'label'       => __( 'Tracing Code', 'essentials' ),
        'desc'        => __( 'Enter your tracking code script here that will be injected into every page for better analytics.', 'essentials' ),
        'std'         => '',
        'type'        => 'textarea-simple',
        'section'     => 'setup',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'vn_copyright',
        'label'       => __( 'Copyright', 'essentials' ),
        'desc'        => __( 'Enter your copyright information here.  HTML such as links etc is acceptable.', 'essentials' ),
        'std'         => __( '2013 Jonathan Atkinson - Handcrafted in the U.S.A.', 'essentials' ),
        'type'        => 'textarea-simple',
        'section'     => 'setup',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'vn_homepagemessageleft',
        'label'       => __( 'Homepage Message Left Column', 'essentials' ),
        'desc'        => __( 'Enter your message area here that will appear under the slider.  HTML is acceptable here.  Leave this blank to disable.', 'essentials' ),
        'std'         => __( 'Sign-up today for a FREE instant account with no monthly commitment!', 'essentials' ),
        'type'        => 'textarea-simple',
        'section'     => 'setup',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'vn_homepagemessageright',
        'label'       => __( 'Homepage Message Right Column', 'essentials' ),
        'desc'        => __( 'Enter your message area here that will appear under the slider.  HTML is acceptable here.  Leave this blank to disable.', 'essentials' ),
        'std'         => __( '<div class="button_green_image"> <a href="#">No Credit Card Required</a> </div>', 'essentials' ),
        'type'        => 'textarea-simple',
        'section'     => 'setup',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'vn_telephonenumber',
        'label'       => __( 'Telephone Number', 'essentials' ),
        'desc'        => __( 'Enter your phone number here that will appear in the menu bar on every page.  Leave this blank to disable.', 'essentials' ),
        'std'         => '0800 123 456 7890',
        'type'        => 'text',
        'section'     => 'setup',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'vn_homepageleftcolumntitle',
        'label'       => __( 'Homepage Left Column Title', 'essentials' ),
        'desc'        => __( 'Enter the title for the left column title if you have chosen the Homepage-Dynamic page template for your homepage.', 'essentials' ),
        'std'         => __( 'Our clients', 'essentials' ),
        'type'        => 'text',
        'section'     => 'setup',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'vn_homepageleftcolumnlinktext',
        'label'       => __( 'Homepage Left Column Link Text', 'essentials' ),
        'desc'        => __( 'Enter some text here that will link through to wherever you would like.  Leaving this field blank will disable this feature.', 'essentials' ),
        'std'         => __( 'View all', 'essentials' ),
        'type'        => 'text',
        'section'     => 'setup',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'vn_homepageleftcolumnlink',
        'label'       => __( 'Homepage Left Column Link', 'essentials' ),
        'desc'        => __( 'Enter a url here for the above text to point to.  If you left the above field blank, the link feature will be disabled.', 'essentials' ),
        'std'         => '#',
        'type'        => 'text',
        'section'     => 'setup',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'vn_homepagerightcolumntitle',
        'label'       => __( 'Homepage Right Column Title', 'essentials' ),
        'desc'        => __( 'Enter the title for the left column title if you have chosen the Homepage-Dynamic page template for your homepage.', 'essentials' ),
        'std'         => __( 'Latest blog', 'essentials' ),
        'type'        => 'text',
        'section'     => 'setup',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'vn_homepagerightcolumnlinktext',
        'label'       => __( 'Homepage Right Column Link Text', 'essentials' ),
        'desc'        => __( 'Enter some text here that will link through to wherever you would like.  Leaving this field blank will disable this feature.', 'essentials' ),
        'std'         => __( 'View all', 'essentials' ),
        'type'        => 'text',
        'section'     => 'setup',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      ),
      array(
        'id'          => 'vn_homepagerightcolumnlink',
        'label'       => __( 'Homepage Right Column Link', 'essentials' ),
        'desc'        => __( 'Enter a url here for the above text to point to.  If you left the above field blank, the link feature will be disabled.', 'essentials' ),
        'std'         => '#',
        'type'        => 'text',
        'section'     => 'setup',
        'rows'        => '',
        'post_type'   => '',
        'taxonomy'    => '',
        'class'       => ''
      )
    )
  );
  
  /* allow settings to be filtered before saving */
  $custom_settings = apply_filters( 'option_tree_settings_args', $custom_settings );
  
  /* settings are not the same update the DB */
  if ( $saved_settings !== $custom_settings ) {
    update_option( 'option_tree_settings', $custom_settings ); 
  }
  
}