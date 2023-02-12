<?php
/**
 * Plugin Name: IntraNC
 * Plugin URI: https://github.com/georgeuhm/IntraNC
 * Description: Enables users to login to their Nextcloud account using their WordPress login credentials.
 * Author: georgeuhm
 * Author URI: https://github.com/georgeuhm
 * Version: 1.0.0
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: intranc
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class IntraNC {

	/**
	 * The Nextcloud login URL.
	 *
	 * @var string
	 */
	private $nextcloud_login_url = '';

	/**
	 * The Nextcloud preferred landing page URL.
	 *
	 * @var string
	 */
	private $nextcloud_landing_page_url = '';

	/**
	 * The WordPress login URL.
	 *
	 * @var string
	 */
	private $wordpress_login_url = '';

	/**
	 * The text for the shortcode link.
	 *
	 * @var string
	 */
	private $shortcode_link_text = '';

	/**
	 * IntraNC constructor.
	 */
	public function __construct() {
		$this->init();
		$this->add_actions();
	}

	/**
	 * Initializes the plugin variables.
	 */
	private function init() {
		$options = get_option( 'intranc_options' );
		$this->nextcloud_login_url        = ! empty( $options['nextcloud_login_url'] ) ? $options['nextcloud_login_url'] : '';
		$this->nextcloud_landing_page_url = ! empty( $options['nextcloud_landing_page_url'] ) ? $options['nextcloud_landing_page_url'] : '';
		$this->wordpress_login_url        = ! empty( $options['wordpress_login_url'] ) ? $options['wordpress_login_url'] : '';
		$this->shortcode_link_text        = ! empty( $options['shortcode_link_text'] ) ? $options['shortcode_link_text'] : '';
	}

	/**
	 * Adds plugin actions.
	 */
	private function add_actions() {
		add_action( 'init', array( $this, 'register_shortcodes' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Registers the [inchost_do] shortcode.
	 */
	public function register_shortcodes() {
		add_shortcode( 'inchost_do', array( $this, 'shortcode_inchost_do' ) );
	}

	/**
	 * Adds the options page to the WordPress admin.
   */

   // Create shortcode
add_shortcode( 'inchost_do', 'inchost_display_link' );

// Function to display link based on user login status
function inchost_display_link() {
    if ( is_user_logged_in() ) {
        $nextcloud_preferred_landing_page = get_option( 'nextcloud_preferred_landing_page', 'https://cloud.example.com/index.php/apps/files' );
        $shortcode_text = get_option( 'shortcode_text', 'Cloud Link' );
        return '<a href="' . esc_url( $nextcloud_preferred_landing_page ) . '" target="_blank">' . esc_html( $shortcode_text ) . '</a>';
    } else {
        $wordpress_login_page = get_option( 'wordpress_login_page', 'https://example.com/wp-login.php' );
        return '<a href="' . esc_url( $wordpress_login_page ) . '">Login to access ' . esc_html( get_option( 'shortcode_text', 'Cloud Link' ) ) . '</a>';
    }
}

// Function to verify if the login was successful in Nextcloud
function inchost_verify_nextcloud_login( $username, $password ) {
    $nextcloud_login_link = get_option( 'nextcloud_login_link', 'https://cloud.example.com/index.php/login' );

    // Use cURL library to post data to the Nextcloud login form
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $nextcloud_login_link );
    curl_setopt( $ch, CURLOPT_POST, 1 );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, "user=$username&password=$password" );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
    curl_setopt( $ch, CURLOPT_COOKIEJAR, 'cookie.txt' );
    curl_setopt( $ch, CURLOPT_COOKIEFILE, 'cookie.txt' );
    curl_exec( $ch );
    $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
    curl_close( $ch );

    // If HTTP code is 200, login was successful
    if ( $http_code == 200 ) {
        return true;
    } else {
        return false;
    }
}
