<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Lsv_Login
 * @subpackage Lsv_Login/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Lsv_Login
 * @subpackage Lsv_Login/public
 * @author     Benakhigbe <benakhigbe.contact@gmail.com>
 */
class Lsv_Login_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		if(get_option( "lsvlogin_page") !== "" && get_option( "lsvregister_page") !== ""){
			if(get_option( "lsvlogin_page") !== "-1" && get_option( "lsvregister_page") !== "-1"){
				// Login Template
				add_shortcode( 'lsv_login', [$this,'lsv_login_template_display'] );
				// Register Template
				add_shortcode( 'lsv_registration', [$this,'lsv_registration_template_display'] );
			}
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if(is_page($this->get_post_slug(get_option( "lsvlogin_page"))) || is_page( 'post' )){
			wp_enqueue_style( $this->plugin_name.'_login', plugin_dir_url( __FILE__ ) . 'css/lsv-login-display.css', array(), $this->version, 'all' );
		}
		if(is_page($this->get_post_slug(get_option( "lsvregister_page"))) || is_page( 'post' )){
			wp_enqueue_style( $this->plugin_name.'_register', plugin_dir_url( __FILE__ ) . 'css/lsv-register-display.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if(is_page($this->get_post_slug(get_option( "lsvlogin_page"))) || is_page( 'post' )){
			wp_enqueue_script( $this->plugin_name.'_login', plugin_dir_url( __FILE__ ) . 'js/lsv-login-public.js', array( 'jquery' ), $this->version, false );
			wp_localize_script($this->plugin_name.'_login', "public_ajax_requ", array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('ajax-nonce'),
			));
		}
		if(is_page($this->get_post_slug(get_option( "lsvregister_page"))) || is_page( 'post' )){
			wp_enqueue_script( $this->plugin_name.'_register', plugin_dir_url( __FILE__ ) . 'js/lsv-register-display.js', array( 'jquery' ), $this->version, false );
			wp_localize_script($this->plugin_name.'_register', "public_ajax_requ", array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('ajax-nonce'),
			));
		}
	}

	/**
     * Get post slug by id
     */
    public function get_post_slug($post_id)
    {
        global $wpdb;
		if(!empty($post_id)){
			if ($slug = $wpdb->get_var("SELECT post_name FROM {$wpdb->prefix}posts WHERE ID = $post_id")) {
				return $slug;
			} else {
				return '';
			}
		}
    }

	// LSV Login template
	function lsv_login_template_display(){
		ob_start();
		require_once plugin_dir_path( __FILE__ )."partials/lsv-login-display.php";
		return ob_get_clean();
		exit;
	}
	// LSV Registration template
	function lsv_registration_template_display(){
		ob_start();
		require_once plugin_dir_path( __FILE__ )."partials/lsv-register-display.php";
		return ob_get_clean();
		exit;
	}

	// Email check
	function lsv_email_check(){
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
			die ( 'Hey! What are you doing?');
		}

		if(isset($_POST['email'])){
			if(!empty($_POST['email'])){
				$email = sanitize_email( $_POST['email'] );
				if(get_user_by('email', $email )){
					echo json_encode(array("exist" => 'exist'));
					die;
				}else{
					echo json_encode(array("notexist" => 'notexist'));
					die;
				}
				die;
			}
			die;
		}
		die;
	}

	// Registration process
	function lsv_registration_process(){
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
			die ( 'Hey! What are you doing?');
		}

		global $wpdb;

		if(isset($_POST['data'])){
			$firstname = sanitize_text_field($_POST['data']['firstname']);
            $lastname = sanitize_text_field($_POST['data']['lastname']);
            $email = sanitize_email($_POST['data']['email']);
            $phone = intval($_POST['data']['phone']);
            $country = sanitize_text_field($_POST['data']['country']);

			$getuserdata = $wpdb->get_var("SELECT email FROM {$wpdb->prefix}lsv_user WHERE email = '$email'");
            if( $getuserdata ){
                echo 'User Exist';
                die;
            }

			$insert = insert($wpdb->prefix.'lsv_user', 
				array(
					'firstname'    =>  $firstname,
					'lastname'    =>  $lastname,
					'email'     =>  $email,
					'phone'     =>  $password,
					'country'          => 'subscriber',
				),
				array('%s','%s','%s','%d','%s')
			);
			
			if(is_wp_error($insert)){
				echo json_encode(array("error" => 'error'));
				die;
			}

			echo json_encode(array("success" => home_url($this->get_post_slug(get_option( "lsvlogin_page" ))) ));
			die;
		}
		die;
	}

	// Login requests
	function lsv_login_requests(){
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
			die ( 'Hey! What are you doing?');
		}

		if(isset($_POST['email']) && isset($_POST['password'])){
			global $wpdb;
			$email = sanitize_email( $_POST['email'] );
			$password = sanitize_text_field( $_POST['password'] );

			if ( $user = get_user_by('email', $email ) ) {
				// check the user's login with their password.
				if ( wp_check_password( $password, $user->user_pass, $user->ID ) ) {
					wp_clear_auth_cookie();
					wp_set_current_user($user->ID);
					wp_set_auth_cookie($user->ID);

					// Storing logs
					$logtbl = $wpdb->prefix.'lsv_logs';
					$wpdb->insert($logtbl,array(
						'user_id'	=> $user->ID,
						'firstname'	=> get_user_meta( $user->ID, 'first_name', true),
						'lastname'	=> get_user_meta( $user->ID, 'last_name', true),
						'phone'	=> get_user_meta( $user->ID, 'phone', true),
						'email'	=> $email,
						'country'	=> get_user_meta( $user->ID, 'country', true),
						'logindate'	=> date('d-m-y'),
					),array(
						'%d','%s','%s','%d','%s','%s','%s'
					));

					echo json_encode(array("success" => get_option( "lsvredirect_after_login" )));
					die;
				}else{
					echo json_encode(array("error" => "Incorrect password!"));
					die;
				}
			}else{
				echo json_encode(array("error" => "Incorrect email!"));
				die;
			}
			die;
		}
		die;
	}
}
