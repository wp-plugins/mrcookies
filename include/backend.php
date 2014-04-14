<?php

/**** SETUP ADMIN ACTIONS ****/
function mrcookies_register_settings()
{
    // Styles / Scripts
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('mrcookies-admin', MRCOOKIES_URL . '/js/mrcookies-admin.js', array('wp-color-picker', 'jquery'));
    
    // Settings
    register_setting('mrcookies-options-group', 'mrcookies_cookie_name');
    register_setting('mrcookies-options-group', 'mrcookies_cookie_lifetime');
    register_setting('mrcookies-options-group', 'mrcookies_legal_notice');
    register_setting('mrcookies-options-group', 'mrcookies_use_domain');
    
    register_setting('mrcookies-options-group', 'mrcookies_style_type');
    register_setting('mrcookies-options-group', 'mrcookies_style_text_color');
    register_setting('mrcookies-options-group', 'mrcookies_style_link_color');
    register_setting('mrcookies-options-group', 'mrcookies_style_background_color');
    register_setting('mrcookies-options-group', 'mrcookies_style_border_color');
    register_setting('mrcookies-options-group', 'mrcookies_style_custom_css');
}
add_action('admin_init', 'mrcookies_register_settings');

/**** MENUS / LINKS ****/
function mrcookies_admin_menu() {
    add_submenu_page('options-general.php', 'Mr Cookies', 'Mr Cookies', 'manage_options', 'mr-cookies', 'mrcookies_admin_options_page');
}
add_action('admin_menu', 'mrcookies_admin_menu');

function mrcookies_plugin_settings_link($links)
{
    array_unshift($links, '<a href="' . get_admin_url() . 'options-general.php?page=mr-cookies">' . mrcookies_text('Settings') . '</a>');
    return $links;
}
add_filter('plugin_action_links_' . MRCOOKIES_PLUGIN_BASENAME, 'mrcookies_plugin_settings_link');

//mrcookies_text('Mr Cookies')

/**** PAGES ****/
function mrcookies_admin_options_page()
{
    include MRCOOKIES_DIR_ADMIN . '/options-page.php';
}


