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

		if(get_option( 'lsvredirect_after_login' )){
			// headerr shortcode
			add_shortcode( 'lsv_locked_page', [$this,'lsv_locked_page_design'] );
		}
	}

	// Denied access without logon
	function restrict_targeted_page(){
		$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

		if($actual_link === home_url( $this->get_post_slug(get_option( 'lsvredirect_after_login' )) )){
			if(!isset($_SESSION['lsvuid'])){
				wp_safe_redirect( home_url( '/404' ) );
			}
		}
	}

	// LSV Logout
	function lsv_logout(){
		if(isset($_GET['logout'])){
			if($_GET['logout'] == 'true'){
				unset($_SESSION['lsvuid']);
				session_destroy();
				wp_safe_redirect( home_url($this->get_post_slug(get_option( "lsvlogin_page" ))) );
			}
		}
	}

	// Make username view
	function lsv_locked_page_design(){
		if(isset($_SESSION['lsvuid'])){
			ob_start();
			global $wpdb,$post;
			$user_id = isset($_SESSION['lsvuid'])?$_SESSION['lsvuid']:0;
			$getuserdata = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}lsv_users WHERE ID = $user_id");

			$output = '';

			$output .= '<section>';
			$output .= '<div class="navabr">';
			$output .= '<div class="navcontent">';
			$output .= '<div class="lsvuser">';
			$output .= '<h5 class="name">'.__(ucfirst($getuserdata->firstname).' '.ucfirst($getuserdata->lastname), 'lsv-plugin' ).'</h5>';
			$output .= '</div>';
			$output .= '<div class="lsvlogout"><p><a href="?logout=true">Log out</a></p></div>';
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</section>';
			echo $output;
			return ob_get_clean();
		}else{
			wp_safe_redirect( home_url($this->get_post_slug(get_option( "lsvlogin_page" ))) );
		}
	}
	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if(is_page($this->get_post_slug(get_option( "lsvlogin_page")))){
			wp_enqueue_style( $this->plugin_name.'_login', plugin_dir_url( __FILE__ ) . 'css/lsv-login-display.css', array(), microtime(), 'all' );
		}
		if(is_page($this->get_post_slug(get_option( "lsvregister_page")))){
			wp_enqueue_style( $this->plugin_name.'_register', plugin_dir_url( __FILE__ ) . 'css/lsv-register-display.css', array(), microtime(), 'all' );
		}
		
		if(is_page($this->get_post_slug(get_option( 'lsvredirect_after_login' )))){
			wp_enqueue_style( 'target-page'.'_register', plugin_dir_url( __FILE__ ) . 'css/target-page.css', array(), microtime(), 'all' );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if(is_page($this->get_post_slug(get_option( "lsvlogin_page")))){
			wp_enqueue_script( $this->plugin_name.'_login', plugin_dir_url( __FILE__ ) . 'js/lsv-login-public.js', array( 'jquery' ), microtime(), false );
			wp_localize_script($this->plugin_name.'_login', "public_ajax_requ", array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('ajax-nonce'),
			));
		}
		if(is_page($this->get_post_slug(get_option( "lsvregister_page")))){
			wp_enqueue_script( $this->plugin_name.'_register', plugin_dir_url( __FILE__ ) . 'js/lsv-register-display.js', array( 'jquery' ), microtime(), false );
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
				global $wpdb;
				$email = sanitize_email( $_POST['email'] );
				$myaccess = $wpdb->get_var("SELECT email FROM {$wpdb->prefix}lsv_users WHERE email = '$email'");
				if($myaccess){
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

			$getuserdata = $wpdb->get_var("SELECT email FROM {$wpdb->prefix}lsv_users WHERE email = '$email'");
            if( $getuserdata ){
                echo 'User Exist';
                die;
            }

			$wpdb->insert($wpdb->prefix.'lsv_users', 
				array(
					'firstname'    =>  $firstname,
					'lastname'    =>  $lastname,
					'email'     =>  $email,
					'phone'     =>  $phone,
					'country'          => $country,
				),
				array('%s','%s','%s','%d','%s')
			);
			
			if(is_wp_error($wpdb)){
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

		global $wpdb;

		if(isset($_POST['email']) && isset($_POST['participants'])){
			$email = sanitize_email( $_POST['email'] );
			$participants = intval( $_POST['participants'] );
			$myaccess = $wpdb->get_var("SELECT ID FROM {$wpdb->prefix}lsv_users WHERE email = '$email'");

			if ( $myaccess ) {

				$logsadd = $wpdb->insert($wpdb->prefix.'lsv__logs',
					array(
						'user_id' => $myaccess,
						'watching_num' => $participants,
						'logindate' => date('d-m-y'),
					),array('%d','%d','%s')
				);
				
				if($logsadd){
					$_SESSION['lsvuid'] = $myaccess;
					if(isset($_SESSION['lsvuid'])){
						echo json_encode(array("success" => home_url($this->get_post_slug(get_option( "lsvredirect_after_login" ))) ));
						die;
					}
				}
				echo json_encode(array("error" => "Something was wrong!"));
				die;
			}else{
				echo json_encode(array("error" => "Unknown Details. Use your correct email address to be able to login in."));
				die;
			}
			die;
		}
		die;
	}
}
