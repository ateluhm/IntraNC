<?php
/*
 * Plugin Name: IntraNC
 * Plugin URI: https://github.com/ateluhm/IntraNC
 * Description: A plugin that helps users login to their Nextcloud account using their WordPress credentials.
 * Author: George Varga
 * Author URI: https://github.com/georgeuhm
 * Version: 1.4
 */

// Define plugin constants
define( 'INTRANC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'INTRANC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include the settings page
require_once INTRANC_PLUGIN_DIR . 'inc/settings-page.php';

// Register the shortcode for the plugin link
add_shortcode( 'inchost_do', 'inchost_do_shortcode' );

// Shortcode function to display the plugin link
function inchost_do_shortcode() {
  // Get the plugin options
  $options = get_option( 'intranc_options' );

  // Check if the user is logged in to WordPress
  if ( is_user_logged_in() ) {
    // Get the Nextcloud preferred landing page link
    $nextcloud_landing_page = $options['nextcloud_landing_page'];
    return '<a href="' . $nextcloud_landing_page . '" target="_blank">' . $options['shortcode_text'] . '</a>';
  } else {
    // Get the WordPress login link
    $wordpress_login = $options['wordpress_login'];
    return '<a href="' . $wordpress_login . '">' . $options['shortcode_text'] . '</a>';
  }
}

// Function to verify if the login was successful in Nextcloud
function intranc_verify_login() {
  // Get the plugin options
  $options = get_option( 'intranc_options' );

  // Check if the user has entered their WordPress credentials
  if ( isset( $_POST['wordpress_username'] ) && isset( $_POST['wordpress_password'] ) ) {
    // Get the entered WordPress credentials
    $wordpress_username = sanitize_text_field( $_POST['wordpress_username'] );
    $wordpress_password = sanitize_text_field( $_POST['wordpress_password'] );

    // Check if the entered WordPress credentials match with the user's credentials
    $user = wp_authenticate( $wordpress_username, $wordpress_password );
    if ( is_wp_error( $user ) ) {
      // Show error message if the entered credentials are incorrect
      echo '<p>Error: Incorrect WordPress credentials. Please try again.</p>';
    } else {
      // Redirect to the Nextcloud login link if the entered credentials are correct
      wp_redirect( $options['nextcloud_login'] );
      exit;
    }
  }

  // Show the form to enter WordPress credentials
  echo '<form action="" method="post">';
  echo '<label for="wordpress_username">WordPress Username:</label>';
  echo '<input type="text" id="wordpress_username" name="wordpress_username" required>';
  echo '<label for="wordpress_password">WordPress Password:</label>';
  echo '<input type="password" id="wordpress_password" name="wordpress_password" required>';
  echo '<input type="submit" value="Login">';
  echo '</form>';
}

// Action to add the plugin settings page
add_action( 'admin_menu', 'intranc_add_admin_menu' );

// Function to add the plugin settings page
function intranc_add_admin_menu() {
  add_options_page( 'IntraNC Settings', 'IntraNC', 'manage_options', 'intranc', 'intranc_options_page' );
}

// Function to register plugin settings
add_action( 'admin_init', 'intranc_settings' );

// Function to register plugin settings
function intranc_settings() {
  register_setting( 'intranc_options_group', 'intranc_options', 'intranc_options_validate' );
}

// Function to validate plugin settings
function intranc_options_validate( $input ) {
  $new_input = array();
  if ( isset( $input['nextcloud_login'] ) )
    $new_input['nextcloud_login'] = esc_url_raw( $input['nextcloud_login'] );
  if ( isset( $input['nextcloud_landing_page'] ) )
    $new_input['nextcloud_landing_page'] = esc_url_raw( $input['nextcloud_landing_page'] );
  if ( isset( $input['wordpress_login'] ) )
    $new_input['wordpress_login'] = esc_url_raw( $input['wordpress_login'] );
  if ( isset( $input['shortcode_text'] ) )
    $new_input['shortcode_text'] = sanitize_text_field( $input['shortcode_text'] );
  return $new_input;
}
