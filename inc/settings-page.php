<?php
// Function to display plugin settings page
function intranc_options_page() {
?>
  <div class="wrap">
    <h2>IntraNC Settings</h2>
    <form method="post" action="options.php">
    <?php settings_fields( 'intranc_options_group' ); ?>
    <?php $intranc_options = get_option( 'intranc_options' ); ?>
      <table class="form-table">
        <tr valign="top">
          <th scope="row">Nextcloud Login Link:</th>
          <td><input type="text" name="intranc_options[nextcloud_login]" value="<?php echo $intranc_options['nextcloud_login']; ?>" required /></td>
        </tr>
        <tr valign="top">
          <th scope="row">Nextcloud Preferred Landing Page Link:</th>
          <td><input type="text" name="intranc_options[nextcloud_landing_page]" value="<?php echo $intranc_options['nextcloud_landing_page']; ?>" required /></td>
        </tr>
        <tr valign="top">
          <th scope="row">WordPress Login Link:</th>
          <td><input type="text" name="intranc_options[wordpress_login]" value="<?php echo $intranc_options['wordpress_login']; ?>" required /></td>
        </tr>
        <tr valign="top">
          <th scope="row">Displayed Text for Shortcode Link:</th>
          <td><input type="text" name="intranc_options[shortcode_text]" value="<?php echo $intranc_options['shortcode_text']; ?>" required /></td>
        </tr>
      </table>
      <p class="submit">
        <input type="submit" class="button-primary" value="Save Changes" />
      </p>
    </form>
  </div>
<?php
}
