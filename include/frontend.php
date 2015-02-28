<?php

/* SCRIPTS / STYLES */
function mrcookies_enqueue_scripts()
{
    // Styles
    $style_type = mrcookies_get_option('mrcookies_style_type');
    if ($style_type != MRCOOKIES_STYLE_CUSTOM)
    {
        wp_enqueue_style('mrcookies-style', MRCOOKIES_URL . 'css/style.min.css', false, '1.0');
    }
    
    // Scripts
    wp_enqueue_script('mrcookies', MRCOOKIES_URL . 'js/mrcookies.min.js', array(), '1.1', true);
    
    $legal_notice_type = mrcookies_get_option('mrcookies_legal_notice_type', 1);
    if ($legal_notice_type == 1)
    {
        $legal_notice = mrcookies_get_option('mrcookies_legal_notice');
        $legal_notice = $legal_notice > 0 ? get_permalink($legal_notice) : false;
    }
    else if ($legal_notice_type == 2)
    {
        $legal_notice = esc_url(mrcookies_get_option('mrcookies_legal_notice_external', ''));
    }
    else
    {
        $legal_notice = false;
    }
    
    wp_localize_script('mrcookies', 'MRCOOKIES', array(  
        'cookie_name' => mrcookies_get_option('mrcookies_cookie_name'),
        'cookie_life' => mrcookies_get_option('mrcookies_cookie_lifetime'),
        'legal_notice' => $legal_notice,
        'domain' => mrcookies_get_option('mrcookies_use_domain'),
        'i18n' => array(
            'notice' => mrcookies_text('This website uses own and/or third parties cookies to: analyze, personalize content and/or advertising. If you continue browsing, we consider that you accept the use.'),
            'link' => mrcookies_text('More information'),
            'close' => mrcookies_text('Close')
        )
    ));
}
add_action('wp_enqueue_scripts', 'mrcookies_enqueue_scripts');

function mrcookies_print_styles()
{
    $style_type = mrcookies_get_option('mrcookies_style_type');
    if ($style_type == MRCOOKIES_STYLE_CUSTOM)
    {
        ?>
        <style type="text/css">
            <?php echo mrcookies_get_option('mrcookies_style_custom_css'); ?>;
        </style>
        <?php
    }
    else
    {
        ?>
        <style type="text/css">
            #mrcookies-wrapper {
                color: <?php echo mrcookies_get_option('mrcookies_style_text_color'); ?> !important;
                background-color:<?php echo mrcookies_get_option('mrcookies_style_background_color'); ?> !important;
                border-color: <?php echo mrcookies_get_option('mrcookies_style_border_color'); ?> !important;
            }
            #mrcookies-wrapper #mrcookies-container a {
                color: <?php echo mrcookies_get_option('mrcookies_style_link_color'); ?>;
            }
        </style>
        <?php
    }
}
add_action('wp_print_styles', 'mrcookies_print_styles');




