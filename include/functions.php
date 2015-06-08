<?php

/**** NETWORK ****/
/**
 * Check WP uses multisite/network
 * @return Boolean
 */
function mrcookies_use_network()
{
    return is_multisite();
}

/**** OPTIONS ****/
/**
 * Retrieve WP option
 * @param String $option Option name
 * @param Mixed $default Default value if this options not exists
 * @param Boolean $use_cache Usage of the WP cache (Only in multisite)
 * @return Mixed
 */
function mrcookies_get_option($option, $default=false, $use_cache=true)
{
    return mrcookies_use_network() ? get_site_option($option, $default, $use_cache) : get_option($option, $default);
}

/**
 * Save (Insert / Update) WP option
 * @param String $option Option name
 * @param Mixed $value Option value
 * @return Boolean
 */
function mrcookies_save_option($option, $value)
{
    if (mrcookies_use_network())
    {
        return add_site_option($option, $value) ? true : update_site_option($option, $value);
    }
    return add_option($option, $value) ? true : update_option($option, $value);
}

/**
 * Delete WP option
 * @param String $option Option name
 * @return Boolean
 */
function mrcookies_delete_option($option)
{
    return mrcookies_use_network() ? delete_option($option) : delete_site_option($option);
}

/**
 * Retrieve Mr Cookies default options
 * @global Array $__mrcookies_defaults Default values
 * @param Boolean $load_custom Load default custom CSS of style.css
 * @return Boolean
 */
function mrcookies_get_default_options($load_custom = false)
{
    $__mrcookies_defaults = array(
        'mrcookies_installed' => 1,
        'mrcookies_cookie_name' => 'MRCOOKIES_ACCEPT_NAVIGATION',
        'mrcookies_cookie_lifetime' => 365,
        'mrcookies_legal_notice_type' => 1,
        'mrcookies_legal_notice' => 0,
        'mrcookies_legal_notice_external' => '',
        'mrcookies_use_domain' => false,
        'mrcookies_style_type' => 1,
        'mrcookies_style_text_color' => '#cccccc',
        'mrcookies_style_link_color' => '#b69b4d',
        'mrcookies_style_background_color' => '#343434',
        'mrcookies_style_border_color' => '#b69b4d',
        'mrcookies_style_custom_css' => ''
    );
    if (empty($__mrcookies_defaults['mrcookies_style_custom_css']) || $load_custom)
    {
        $__mrcookies_defaults['mrcookies_style_custom_css'] = file_get_contents(MRCOOKIES_DIR_CSS . '/style.css');
		$__mrcookies_defaults['mrcookies_style_custom_css'] = str_replace('../img/close.png', MRCOOKIES_URL . 'img/close.png', $__mrcookies_defaults['mrcookies_style_custom_css']);
    }
    return $__mrcookies_defaults;
}

/**
 * Save Mr Cookies default options
 */
function mrcookies_save_default_options()
{
    $options = mrcookies_get_default_options(true);
    foreach ($options AS $option_name => $option_value)
    {
        mrcookies_save_option($option_name, $option_value);
    }
}

/**
 * Remove Mr Cookies options
 */
function mrcookies_remove_options()
{
    $options = mrcookies_get_default_options();
    foreach ($options AS $option_name => $option_value)
    {
        mrcookies_delete_option($option_name);
    }
}

/**** I18N ****/
/**
 * Retrieve translated string with gettext context of Mr Cookies
 * @param String $text Text to translate
 * @param String $domain Domain to retrieve the translated text
 * @return String
 */
function mrcookies_text($text, $domain = false)
{
    return __($text, $domain ? $domain : MRCOOKIES_I18N);
}


