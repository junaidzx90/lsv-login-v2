<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://example.com
 * @since      1.0.0
 *
 * @package    Lsv_Login
 * @subpackage Lsv_Login/admin/partials
 */
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="table-container">
    <table id="table_id" class="compact stripe hover" style="width:100%">
    <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Login Date</th>
            <th>Total Attendance</th>
            <th>Country</th>
        </tr>
    </thead>
    <tbody>
        <?php
        global $wpdb;
        $logs = $wpdb->get_results("SELECT u.*,l.* FROM {$wpdb->prefix}lsv_user u, {$wpdb->prefix}lsv_logs l WHERE u.ID = l.user_id ORDER BY l.logindate DESC");
        if(!empty($logs)){
            foreach($logs as $log){
                ?>
                <tr>
                    <td><?php echo _e($log->firstname); ?></td>
                    <td><?php echo _e($log->lastname); ?></td>
                    <td><?php echo _e($log->email); ?></td>
                    <td><?php echo _e($log->phone); ?></td>
                    <td><?php echo _e($log->logindate); ?></td>
                    <td><?php echo _e($log->watching_num); ?></td>
                    <td><?php echo _e($log->country); ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Login Date</th>
            <th>Total Logins</th>
            <th>Country</th>
        </tr>
    </tfoot>
    </table>
</div>