<?php
if(is_user_logged_in(  )){
    if(!current_user_can( 'administrator' )){
        wp_safe_redirect( home_url('/') );
    }
}
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Lsv_Login
 * @subpackage Lsv_Login/public/partials
 */
require_once LSV_PATH."public/class-lsv-login-public.php";
class Login_Page_View extends Lsv_Login_Public{
    public $get_post_slug;

    function __construct($page = ''){
        $this->matchfield_login_error = $this->get_post_slug($page);
    }
}
$public_ins = new Login_Page_View();
$public_ins->matchfield_login_error;

?>
<div class="wrapper" style="background-image: linear-gradient(45deg, #292929, #f19d4947),url(<?php echo get_option('lsvbackground_img'); ?>)">
  <div class="container">
    <div class="login-form">
        <h2>Login</h2>
        <form>
            <p>
                <label for="email">Your email</label>
                <input id="email" type="email" placeholder="Email" required>
            </p>
            <p>
                <label for="Participants">Participants number</label>
                <input id="participants" type="number" class="participants" placeholder="Number of people watching with you." required>
            </p>
            <p class="signinbtn">
                <input id="signinbtn" class="btn" type="submit" value="Sing In" />
            </p>
            <p>
                <a href="<?php echo home_url($public_ins->get_post_slug(get_option( "lsvregister_page", true ))); ?>">Create an account.</a>
            </p>
        </form>
        <!-- login end -->
    </div>
  </div>
</div>
