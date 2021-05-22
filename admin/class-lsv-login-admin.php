<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Lsv_Login
 * @subpackage Lsv_Login/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Lsv_Login
 * @subpackage Lsv_Login/admin
 * @author     Benakhigbe <benakhigbe.contact@gmail.com>
 */
class Lsv_Login_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'datatables', plugin_dir_url( __FILE__ ) . 'css/dataTable.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/lsv-login-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'dataTables', plugin_dir_url( __FILE__ ) . 'js/dataTable.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/lsv-login-admin.js', array( 'jquery' ), $this->version, false );

	}

	// Admin menu page
	public function lsv_register_menupage(){
		// Register Menu
		add_menu_page( 'LSV Plugin', 'LSV Plugin', 'manage_options', 'lsvplugin', [$this,'lsvplugin_menupage_display'], 'dashicons-xing', 45 );
		add_submenu_page( 'lsvplugin', 'Settings', 'Settings', 'manage_options', 'lsvplugin', [$this, 'lsvplugin_menupage_display'] );
		add_submenu_page( 'lsvplugin', 'Logs', 'Logs', 'manage_options', 'lsv-logs', [$this, 'lsv_logstable'] );
		add_settings_section('lsvplugin_settings', '', '', 'lsvplugin_settings_page');
		
		// Login page
        add_settings_field('lsvlogin_page', 'Login page <span class="page">[lsv_login]</span>', array($this, 'lsvlogin_page_cb'), 'lsvplugin_settings_page', 'lsvplugin_settings');
        register_setting('lsvplugin_settings', 'lsvlogin_page');

		// Registration page
        add_settings_field('lsvregister_page', 'Registration page <span class="page">[lsv_registration]</span>', array($this, 'lsvregister_page_cb'), 'lsvplugin_settings_page', 'lsvplugin_settings');
        register_setting('lsvplugin_settings', 'lsvregister_page');

		// Logo Url
        add_settings_field('my_logo_url', 'Logo URL', array($this, 'my_logo_url_cb'), 'lsvplugin_settings_page', 'lsvplugin_settings');
        register_setting('lsvplugin_settings', 'my_logo_url');

		// Logo Url
        add_settings_field('lsvredirect_after_login', 'Redirect after login', array($this, 'lsvredirect_after_login_cb'), 'lsvplugin_settings_page', 'lsvplugin_settings');
        register_setting('lsvplugin_settings', 'lsvredirect_after_login');

		// Background Image
        add_settings_field('lsvbackground_img', 'Background Image', array($this, 'lsvbackground_img_cb'), 'lsvplugin_settings_page', 'lsvplugin_settings');
        register_setting('lsvplugin_settings', 'lsvbackground_img');

		// Background Image
        add_settings_field('lsvregistration_img', 'Registration form image', array($this, 'lsvregistration_img_cb'), 'lsvplugin_settings_page', 'lsvplugin_settings');
        register_setting('lsvplugin_settings', 'lsvregistration_img');

		// Background Image
        add_settings_field('lsvregister_info_txt', 'Registration form info text', array($this, 'lsvregister_info_txt_cb'), 'lsvplugin_settings_page', 'lsvplugin_settings');
        register_setting('lsvplugin_settings', 'lsvregister_info_txt');

	}

	function lsvplugin_menupage_display(){
		echo '<h1>LSV Plugin setting</h1>';
		echo '<hr>';

		echo '<form action="options.php" method="post" id="er_settings">';
		echo '<table>';
		settings_fields( 'lsvplugin_settings' );
		do_settings_fields( 'lsvplugin_settings_page', 'lsvplugin_settings' );
		echo '</table>';
		submit_button();
		echo '</form>';
	}

	function lsv_logstable(){
		require_once plugin_dir_path( __FILE__ )."partials/lsv-login-admin-display.php";
	}

	// Login page
    public function lsvlogin_page_cb()
    {
        global $wp_query;
        $args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'name' => 'lsvlogin_page',
            'class' => 'widefat',
            'selected' => get_option('lsvlogin_page'),
            'show_option_none' => '',
            'show_option_no_change' => 'Select Page',
            'option_none_value' => '',
        );
        wp_dropdown_pages($args);
        echo '<br>';
    }
	// Registration page
    public function lsvregister_page_cb()
    {
        global $wp_query;
        $args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'name' => 'lsvregister_page',
            'class' => 'widefat',
            'selected' => get_option('lsvregister_page'),
            'show_option_none' => '',
            'show_option_no_change' => 'Select Page',
            'option_none_value' => '',
        );
        wp_dropdown_pages($args);
        echo '<br>';
    }

	// Background Image
	function my_logo_url_cb(){
		echo '<input type="url" class="widefat" name="my_logo_url" value="'.get_option('my_logo_url').'" placeholder="Logo url">';
	}
	// Background Image
	function lsvbackground_img_cb(){
		echo '<input type="url" class="widefat" name="lsvbackground_img" value="'.get_option('lsvbackground_img').'" placeholder="Image url">';
	}
	// Registration Image
	function lsvregistration_img_cb(){
		echo '<input type="url" class="widefat" name="lsvregistration_img" value="'.get_option('lsvregistration_img').'" placeholder="Image url">';
	}
	// Registration Image
	function lsvregister_info_txt_cb(){
		echo '<textarea placeholder="Long texts" name="lsvregister_info_txt" id="lsvregister_info_txt" class="widefat">'.get_option('lsvregister_info_txt').'</textarea>';
	}
	// Redirect after login
	function lsvredirect_after_login_cb(){
		echo '<input type="url" class="widefat" name="lsvredirect_after_login" value="'.get_option('lsvredirect_after_login').'" placeholder="Redirect url">';
	}

}
