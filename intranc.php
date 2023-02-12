<?php
/*
 * Plugin Name: IntraNC
 * Plugin URI: https://github.com/georgeuhm/IntraNC
 * Description: A plugin to enable users to login to their Nextcloud account using their WordPress login credentials.
 * Author: georgeuhm
 * Version: 1.0.0
 * Author URI: https://github.com/georgeuhm
 * License: GPLv2 or later
 */

// Define plugin constants
define('INTRA_NC_DIR', plugin_dir_path(__FILE__));
define('INTRA_NC_URL', plugin_dir_url(__FILE__));

// Load the settings page
require_once INTRA_NC_DIR . '/inc/settings-page.php';

// Add the shortcode
add_shortcode('inchost_do', 'intra_nc_shortcode');
function intra_nc_shortcode() {
  // Get the Nextcloud and WordPress login links
  $nc_login_link = get_option('intra_nc_login_link', 'https://cloud.example.com/index.php/login');
  $nc_landing_link = get_option('intra_nc_landing_link', 'https://cloud.example.com/index.php/apps/files');
  $wp_login_link = get_option('intra_nc_wp_login_link', 'https://example.com/wp-login.php');
  $link_text = get_option('intra_nc_link_text', 'Cloud Link');

  // Check if the user is logged in
  if (is_user_logged_in()) {
    // Return the Nextcloud landing link with the link text
    return '<a href="' . $nc_landing_link . '" target="_blank">' . $link_text . '</a>';
  } else {
    // Return the WordPress login link with the link text
    return '<a href="' . $wp_login_link . '">' . $link_text . '</a>';
  }
}
