<?php
/**
 * Plugin Name: WP CTA
 * Plugin URI: https://bitbucket.org/progeekdeveloper/
 * Description: Add call to action button.
 * Version: 1.0
 * Author: Procoder
 * Author URI: http://URI_Of_The_Plugin_Author
 * License: GPL2
 */
 
/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
// Start writing code after this line!
global $wpdb;
define('WP_CTA_URL',plugins_url('',__FILE__));
define('WP_CTA_DIR',plugin_dir_path(__FILE__));
define('WP_CTA_TBL',$wpdb->prefix .'wp_cta');
function wp_cta_activate(){
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();    
    $table_name = WP_CTA_TBL;
    $sql = "CREATE TABLE IF NOT EXISTS `$table_name`(
        `cta_id` int(9) NOT NULL AUTO_INCREMENT,
        `cta_name` varchar(100) NOT NULL,
        `cta_description` text NOT NULL,
        `cta_btn_text` varchar(100) NOT NULL, 
        `cta_btn_css` text NOT NULL, 
        `cta_type` varchar(20) NOT NULL,
        `cta_update_date` datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
register_activation_hook( __FILE__, 'wp_cta_activate' );
function wp_cta_deactivated(){}
register_deactivation_hook( __FILE__, "wp_cta_deactivated");

/** Step 2 (from text above). */
add_action( 'admin_menu', 'wp_cta_admin_menu' );

function wp_cta_admin_menu() {
    add_menu_page('WP CTA', 'WP CTA', 'manage_options', 'wp-cta-plugin', 'wp_cta_page','dashicons-share-alt');
}


function wp_cta_page() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    if($_REQUEST['action']=='delete' || $_REQUEST['action']=='add' || $_REQUEST['action']=='edit'){
        require_once(WP_CTA_DIR.'/wp-cta_add.php');
    }else{
        require_once(WP_CTA_DIR.'/wp-cta_listing.php');
    }	
}

function cta_enqueue($hook) {
    wp_enqueue_style('cta_admin_style', WP_CTA_URL.'/assest/css/admin_style.css' );
    wp_enqueue_script('cta_colorpicker', WP_CTA_URL.'/assest/js/jscolor.js',array('jquery') );
}
add_action( 'admin_enqueue_scripts', 'cta_enqueue' );