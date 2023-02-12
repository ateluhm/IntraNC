<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function inchost_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    if ( isset( $_POST['inchost_settings_nonce'] ) && wp_verify_nonce( $_POST['inchost_settings_nonce'], 'inchost_save_settings' ) ) {
        update_option( 'nextcloud_login_link', sanitize_text_field( $_POST['nextcloud_login_link'] ) );
        update_option( 'nextcloud_preferred_landing_page', sanitize_text_field( $_POST['nextcloud_preferred_landing_page'] ) );
        update_option( 'wordpress_login_page', sanitize_text_field( $_POST['wordpress_login_page'] ) );
        update_option( 'shortcode_text', sanitize_text_field( $_POST['shortcode_text'] ) );
        echo '<div class="notice notice-success is-dismissible">
            <p>Settings saved.</p>
        </div>';
    }
    ?>

    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="" method="post">
            <?php wp_nonce_field( 'inchost_save_settings', 'inchost_settings_nonce' ); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Nextcloud Login Link</th>
                    <td><input type="text" name="nextcloud_login_link" value="<?php echo esc_url( get_option( 'nextcloud_login_link', 'https://cloud.example.com/index.php/login' ) ); ?>"></td>
                </tr>
                <tr>
                    <th scope="row">Nextcloud Preferred Landing Page Link</th>
                    <td><input type="text" name="nextcloud_preferred_landing_page" value="<?php echo esc_url( get_option( 'nextcloud_preferred_landing_page', 'https://cloud.example.com/index.php/apps/files' ) ); ?>"></td>
                </tr>
                <tr>
                    <th scope="row">WordPress Login Page</th>
                    <td><input type="text" name="wordpress_login_page" value="<?php echo esc_url( get_option( 'wordpress_login_page', 'https://example.com/wp-login.php' ) ); ?>"></td>
                </tr>
                <tr>
                    <th scope="row">Shortcode Text</th>
                    <td><input type="text" name="shortcode_text" value="<?php echo esc_html( get_option( 'shortcode_text', 'Cloud Link' ) ); ?>"></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
