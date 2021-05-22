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
            <th>Total Logins</th>
            <th>Country</th>
        </tr>
    </thead>
    <tbody>
        <?php
        global $wpdb;
        $logs = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}lsv_logs GROUP BY user_id ORDER BY ID DESC");
        if(!empty($logs)){
            foreach($logs as $log){
                ?>
                <tr>
                    <td><?php echo _e($log->firstname); ?></td>
                    <td><?php echo _e($log->lastname); ?></td>
                    <td><?php echo _e($log->email); ?></td>
                    <td><?php echo _e($log->phone); ?></td>
                    <td><?php echo _e($log->logindate); ?></td>
                    <td><?php 
                        echo $wpdb->query("SELECT * FROM {$wpdb->prefix}lsv_logs WHERE user_id = {$log->user_id} AND logindate LIKE '%{$log->logindate}'");
                    ?></td>
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