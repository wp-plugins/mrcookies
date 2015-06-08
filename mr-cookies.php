<?php
/*
Plugin Name: Mr Cookies
Description: MrCookies plugin adapts your Wordpress to satisfy the European cookies laws.
Version: 1.5.2
Author: Artic studio
Author URI: http://www.articstudio.com/
Wordpress version supported: 3.0 and above
*/

/**** CONSTANTS ****/
define( 'MRCOOKIES_I18N', 'mrcookies' );
define( 'MRCOOKIES_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'MRCOOKIES_DIR', plugin_dir_path( __FILE__ ) );
define( 'MRCOOKIES_DIR_INCLUDE', MRCOOKIES_DIR . 'include' );
define( 'MRCOOKIES_DIR_ADMIN', MRCOOKIES_DIR . 'admin' );
define( 'MRCOOKIES_DIR_JS', MRCOOKIES_DIR . 'js' );
define( 'MRCOOKIES_DIR_CSS', MRCOOKIES_DIR . 'css' );
define( 'MRCOOKIES_DIR_LANGUAGES', MRCOOKIES_DIR . 'languages' );
define( 'MRCOOKIES_URL', plugin_dir_url( __FILE__ ) );
define( 'MRCOOKIES_STYLE_DEFAULT', 1 );
define( 'MRCOOKIES_STYLE_CUSTOM', 2 );

/**** REQUIRED LIBRARIES ****/
require MRCOOKIES_DIR_INCLUDE . '/functions.php';

/**** INITIALIZATION ****/
function mrcookies_init()
{
    //require MRCOOKIES_DIR_INCLUDE . '/ajax.php';
    if (is_admin())
    {
        require MRCOOKIES_DIR_INCLUDE . '/backend.php';
    }
    else
    {
        require MRCOOKIES_DIR_INCLUDE . '/frontend.php';
    }
}
add_action( 'init', 'mrcookies_init' );

/**** SETUP ****/
function mrcookies_setup()
{
    // Languages
    //load_plugin_textdomain(MRCOOKIES_I18N, false, MRCOOKIES_DIR_LANGUAGES);
    load_textdomain(MRCOOKIES_I18N, MRCOOKIES_DIR_LANGUAGES . '/mrcookies-' . get_locale() . '.mo');
    
}
add_action( 'plugins_loaded', 'mrcookies_setup' );

/**** ACTIVATION ****/
function mrcookies_checkMU_activation()
{
    global $wpdb;
    if (mrcookies_use_network())
    {
		$blog_list = get_blog_list(0, 'all');
		foreach ($blog_list as $blog)
        {
			switch_to_blog($blog['blog_id']);
            if (!mrcookies_get_option('mrcookies_installed'))
            {
                mrcookies_save_default_options();
            }
		}
		switch_to_blog($wpdb->blogid);
	}
    else
    {
        if (!mrcookies_get_option('mrcookies_installed'))
        {
            mrcookies_save_default_options();
        }
	}
}
register_activation_hook( __FILE__, 'mrcookies_checkMU_activation' );

/**** DEACTIVATION ****/
function mrcookies_checkMU_deactivation()
{
    //mrcookies_checkMU_uninstall();
}
register_deactivation_hook( __FILE__, 'mrcookies_checkMU_deactivation' );

/**** UNINSTALL ****/
function mrcookies_checkMU_uninstall()
{
	global $wpdb;
    if (mrcookies_use_network())
    {
		$blog_list = get_blog_list(0, 'all');
		foreach ($blog_list as $blog)
        {
			switch_to_blog($blog['blog_id']);
			mrcookies_remove_options();
		}
		switch_to_blog($wpdb->blogid);
	}
    else
    {
		mrcookies_remove_options();
	}
}
register_uninstall_hook( __FILE__, 'mrcookies_checkMU_uninstall' );

/**** NEW BLOG ****/
function mrcookies_new_blog($blog_id, $user_id, $domain, $path, $site_id, $meta)
{
    global $wpdb;
	if (is_plugin_active_for_network(MRCOOKIES_PLUGIN_BASENAME))
    {
		$old_blog = $wpdb->blogid;
		switch_to_blog($blog_id);
		mrcookies_save_default_options();
		switch_to_blog($old_blog);
	}
}
add_action('wpmu_new_blog', 'mrcookies_new_blog', 10, 6);
