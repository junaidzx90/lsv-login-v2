<?php

/**
 * Fired during plugin activation
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Lsv_Login
 * @subpackage Lsv_Login/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Lsv_Login
 * @subpackage Lsv_Login/includes
 * @author     Benakhigbe <benakhigbe.contact@gmail.com>
 */
class Lsv_Login_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;

		$lsv_logs = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}lsv_user ( `ID` INT NOT NULL AUTO_INCREMENT, 
		`user_id` INT NOT NULL ,
		`firstname` VARCHAR(55) NOT NULL, 
		`lastname` VARCHAR(55) NOT NULL, 
		`email` VARCHAR(100) NOT NULL,
		`phone` INT NOT NULL,
		`watching_num` INT NOT NULL,
		`country` VARCHAR(55) NOT NULL,
		PRIMARY KEY (`ID`)) ENGINE = InnoDB";
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($lsv_logs);
	}

}