<?php
// Add the IntraNC settings page to the WordPress administration panel
add_action('admin_menu', 'intra_nc_add_settings_page');
function intra_nc_add_settings_page() {
  add_options_page(
    'IntraNC Settings', // Page title
    'IntraNC', // Menu title
    'manage_options', // Capability
    'intra-nc-settings', // Menu slug
    'intra_nc_settings_page' // Callback function
  );
}

// The IntraNC settings page callback function
function intra_nc_settings_page() {
  // Check if the user has the necessary capability
  if (!current_user_can('manage_options')) {
    wp_die('You do not have sufficient permissions to access this page.');
  }

  // Get the Nextcloud and WordPress login links
  $nc_login_link = get_option('intra_nc_login_link', 'https://cloud.example.com/index.php/login');
  $nc_landing_link = get_option('intra_nc_landing_link', 'https://cloud.example.com/index.php/apps/files');
  $wp_login_link = get_option('intra_nc_wp_login_link', 'https://example.com/wp-login.php');
  $link_text = get_option('intra_nc_link_text', 'Cloud Link');

  // Check if the form has been submitted
  if (isset($_POST['intra_nc_settings_submit'])) {
    // Update the Nextcloud and WordPress login links
    update_option('intra_nc_login_link', sanitize_text_field($_POST['intra_nc_login_link']));
    update_option('intra_nc_landing_link', sanitize_text_field($_POST['intra_nc_landing_link']));
    update_option('intra_nc_wp_login_link', sanitize_text_field($_POST['intra_nc_wp_login_link']));
    update_option('intra_nc_link_text', sanitize_text_field($_POST['intra_nc_link_text']));

    // Display a success message
    echo '<div class="notice notice-success is-dismissible">Settings updated successfully.</div>';

    // Get the updated Nextcloud and WordPress login links
    $nc_login_link = get_option('intra_nc_login_link', 'https://cloud.example.com/index.php/login');
    $nc_landing_link = get_option('intra_nc_landing_link', 'https://cloud.example.com/index.php/apps/files');
    $wp_login_link = get_option('intra_nc_wp_login_link', 'https://example.com/wp-login.php');
    $link_text = get_option('intra_nc_link_text', 'Cloud Link');
  }
  ?>
  <div class="wrap">
    <h1>IntraNC Settings</h1>
    <form method="post">
      <table class="form-table">
        <tbody>
          <tr>
            <th scope="row">
              <label for="intra_nc_login_link">Nextcloud Login Link:</label>
            </th>
            <td>
              <input type="text" name="intra_nc_login_link" id="intra_nc_login_link" value="<?php echo esc_url($nc_login_link); ?>" class="regular-text">
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="intra_nc_landing_link">Nextcloud Prefered Landing Page Link:</label>
            </th>
            <td>
              <input type="text" name="intra_nc_landing_link" id="intra_nc_landing_link" value="<?php echo esc_url($nc_landing_link); ?>" class="regular-text">
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="intra_nc_wp_login_link">WordPress Login Link:</label>
            </th>
            <td>
              <input type="text" name="intra_nc_wp_login_link" id="intra_nc_wp_login_link" value="<?php echo esc_url($wp_login_link); ?>" class="regular-text">
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="intra_nc_link_text">Displayed Text for the Shortcode Link:</label>
            </th>
            <td>
              <input type="text" name="intra_nc_link_text" id="intra_nc_link_text" value="<?php echo esc_attr($link_text); ?>" class="regular-text">
            </td>
          </tr>
        </tbody>
      </table>
      <p class="submit">
        <input type="submit" name="intra_nc_settings_submit" id="intra_nc_settings_submit" class="button button-primary" value="Save Changes">
      </p>
    </form>
  </div>
  <?php
}
