<?php
/*
Plugin Name: Empty Title & Category Checker
Plugin URI: http://wordpress.org/extend/plugins/empty-title-category-checker/
Description: Checks if title is empty or if no category or category "no category" is checked then cancel publish or update
Version: 1.0.1
Author: Dmitry Fatakov
License: GPL2
Copyright: 2013
Text Domain: ech
Domain Path: /lang
Based on: Confirm Publishing Actions (http://wordpress.org/extend/plugins/confirm-publishing-actions/)
*/

/*  This program is free software; you can redistribute it and/or modify
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

/**
 * Security
 */
if ( ! defined( 'ABSPATH' ) )
    exit; 

if ( ! class_exists( 'ECH_Actions' ) )
{
/**
 * ECH_Actions class
 */
class ECH_Actions
{
    var $version = '1.0.0';
    var $plugin_dir = '';
    var $plugin_dir_url = '';
    
    function ECH_Actions()
    {
        $this->__construct();
    }
    function __construct()
    {
        $this->plugin_dir = trailingslashit( dirname( plugin_basename( __FILE__ ) ) );
        $this->plugin_dir_url = trailingslashit( plugins_url( dirname( plugin_basename( __FILE__ ) ) ) );
        
        if ( ! is_admin() )
            return;
        
        add_action( 'admin_init',               array( &$this, 'admin_init' ) );
        add_action( 'admin_enqueue_scripts',    array( &$this, 'admin_enqueue_scripts' ) );
        add_filter( 'plugin_row_meta',          array( &$this, 'plugin_row_meta' ), 10, 2 );
    }
    function admin_init()
    {
        load_plugin_textdomain( 'ech', false, $this->plugin_dir . 'lang/' );
        
        do_action( 'ech_admin_init' );
    }
    public function admin_enqueue_scripts( $hook )
    {
        $hooks = array( 'index.php', 'post.php', 'post-new.php', 'edit.php' );
        
        if( ! in_array( $hook, $hooks ) )
            return;
        
        wp_enqueue_script( 'ech', $this->plugin_dir_url . '/js/ech.js', array( 'jquery' ), $this->version, true );
        
        if( 'index.php' == $hook ) {
            $t = __( 'Post', 'ech' );
        } else {
            global $post;
            $type = get_post_type_object( get_post_type( $post ) );
            $t = $type->name;
        }
        
        $category_is_empty = __( 'Category is empty...', 'ech' );
        $title_is_empty = __( 'Title is empty...', 'ech' );

        $cpa_l10n_data = array( 
             'category_is_empty'   => $category_is_empty
            ,'title_is_empty'   => $title_is_empty
			,'post_type' => $t
        );
        
        wp_localize_script( 'ech', 'cpa_l10n_obj', $cpa_l10n_data );
        
        do_action( 'ech_admin_enqueue_scripts', $hook );
    }
    function plugin_row_meta( $links, $file )
    {
        $plugin = plugin_basename( __FILE__ );
        
        if ( $plugin === $file )
        {
            $links[] = sprintf( __( '<a href="%1$s">Donate</a>', 'ech' ),
		           esc_url( 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2JMPQXGL4NS2N' ) );
        }
        
        return $links;
    }
}

new ECH_Actions;

}