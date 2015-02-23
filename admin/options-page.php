<?php

$submited = false;

if (isset($_POST['submit']))
{
    $submited = 1;
    $nonce = isset($_POST['_wpnonce']) ? $_POST['_wpnonce'] : '';
    if (wp_verify_nonce($nonce, 'mrcookies-options-group-options'))
    {
        $reset_settings = (bool)(isset($_POST['mrcookies_reset_settings']) ? $_POST['mrcookies_reset_settings'] : false);
        if ($reset_settings)
        {
            mrcookies_save_default_options();
            $submited = 3;
        }
        else
        {
            $default_options = mrcookies_get_default_options(true);

            mrcookies_save_option('mrcookies_cookie_name', isset($_POST['mrcookies_cookie_name']) ? $_POST['mrcookies_cookie_name'] : $default_options['mrcookies_cookie_name']);
            mrcookies_save_option('mrcookies_cookie_lifetime', (int)(isset($_POST['mrcookies_cookie_lifetime']) ? $_POST['mrcookies_cookie_lifetime'] : $default_options['mrcookies_cookie_lifetime']));
            mrcookies_save_option('mrcookies_legal_notice_type', (int)(isset($_POST['mrcookies_legal_notice_type']) ? $_POST['mrcookies_legal_notice_type'] : $default_options['mrcookies_legal_notice_type']));
            mrcookies_save_option('mrcookies_legal_notice', (int)(isset($_POST['mrcookies_legal_notice']) ? $_POST['mrcookies_legal_notice'] : $default_options['mrcookies_legal_notice']));
            mrcookies_save_option('mrcookies_legal_notice_external', isset($_POST['mrcookies_legal_notice_external']) ? $_POST['mrcookies_legal_notice_external'] : $default_options['mrcookies_legal_notice_external']);
            mrcookies_save_option('mrcookies_use_domain', (bool)(isset($_POST['mrcookies_use_domain']) ? $_POST['mrcookies_use_domain'] : $default_options['mrcookies_use_domain']));
            mrcookies_save_option('mrcookies_style_type', (int)(isset($_POST['mrcookies_style_type']) ? $_POST['mrcookies_style_type'] : $default_options['mrcookies_style_type']));
            mrcookies_save_option('mrcookies_style_text_color', isset($_POST['mrcookies_style_text_color']) ? $_POST['mrcookies_style_text_color'] : $default_options['mrcookies_style_text_color']);
            mrcookies_save_option('mrcookies_style_link_color', isset($_POST['mrcookies_style_link_color']) ? $_POST['mrcookies_style_link_color'] : $default_options['mrcookies_style_link_color']);
            mrcookies_save_option('mrcookies_style_background_color', isset($_POST['mrcookies_style_background_color']) ? $_POST['mrcookies_style_background_color'] : $default_options['mrcookies_style_background_color']);
            mrcookies_save_option('mrcookies_style_border_color', isset($_POST['mrcookies_style_border_color']) ? $_POST['mrcookies_style_border_color'] : $default_options['mrcookies_style_border_color']);
            mrcookies_save_option('mrcookies_style_custom_css', isset($_POST['mrcookies_style_custom_css']) ? stripslashes($_POST['mrcookies_style_custom_css']) : $default_options['mrcookies_cookie_name']);

            $submited = 2;
        }
    }
}

?>

<h1><?php echo mrcookies_text('Mr Cookies'); ?></h1>

<?php if ($submited): ?>
<?php if ($submited === 3): ?>
<div class="updated">
    <p><?php echo mrcookies_text('Mr Cookies settings has been reset successfully.'); ?></p>
</div>
<?php elseif ($submited === 2): ?>
<div class="updated">
    <p><?php echo mrcookies_text('Mr Cookies settings has been saved successfully.'); ?></p>
</div>
<?php else: ?>
<div class="error">
    <p><?php echo mrcookies_text('There was an error saving Mr Cookies settings.'); ?></p>
</div>
<?php endif; ?>
<?php endif; ?>

<div class="wrap">
    <form method="post" action="<?php echo admin_url('options-general.php?page=mr-cookies'); ?>">
        <?php settings_fields('mrcookies-options-group'); ?>
        <?php do_settings_sections('mrcookies-options-group'); ?>
        
        <table class="form-table">
            
            <tr valign="top">
                <th scope="row"><?php echo mrcookies_text('Cookie name'); ?></th>
                <td>
                    <input type="text" name="mrcookies_cookie_name" id="mrcookies_cookie_name" value="<?php echo esc_attr(mrcookies_get_option('mrcookies_cookie_name')); ?>" class="regular-text" />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php echo mrcookies_text('Cookie lifetime'); ?></th>
                <td>
                    <input type="text" name="mrcookies_cookie_lifetime" id="mrcookies_cookie_lifetime" value="<?php echo esc_attr(mrcookies_get_option('mrcookies_cookie_lifetime')); ?>" class="regular-text" />
                    <small><?php echo mrcookies_text('Days'); ?></small>
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php echo mrcookies_text('Legal notice type'); ?></th>
                <td>
                    <?php
                    $mrcookies_legal_notice_type = mrcookies_get_option('mrcookies_legal_notice_type', 1);
                    ?>
                    <select name="mrcookies_legal_notice_type" id="mrcookies_legal_notice_type">
                        <option value="1" <?php selected($mrcookies_legal_notice_type, 1); ?>><?php echo mrcookies_text('Page'); ?></option>
                        <option value="2" <?php selected($mrcookies_legal_notice_type, 2); ?>><?php echo mrcookies_text('External'); ?></option>
                    </select>
                </td>
            </tr>
            
            <tr valign="top" id="mrcookies_legal_notice_wrapper" style="<?php echo $mrcookies_legal_notice_type==1 ? '' : 'display:none;'; ?>">
                <th scope="row"><?php echo mrcookies_text('Legal notice page'); ?></th>
                <td>
                    <?php
                    $mrcookies_legal_notice = mrcookies_get_option('mrcookies_legal_notice');
                    $pages = get_pages(array());
                    ?>
                    <select name="mrcookies_legal_notice" id="mrcookies_legal_notice">
                        <option value="0"></option>
                        <?php foreach ($pages AS $page): ?>
                        <option value="<?php echo $page->ID; ?>" <?php selected($mrcookies_legal_notice, $page->ID); ?>><?php echo $page->post_title; ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            
            <tr valign="top" id="mrcookies_legal_notice_external_wrapper" style="<?php echo $mrcookies_legal_notice_type==2 ? '' : 'display:none;'; ?>">
                <th scope="row"><?php echo mrcookies_text('External link'); ?></th>
                <td>
                    <input type="text" name="mrcookies_legal_notice_external" id="mrcookies_legal_notice_external" value="<?php echo esc_attr(mrcookies_get_option('mrcookies_legal_notice_external')); ?>" class="regular-text" />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php echo mrcookies_text('Use domain'); ?></th>
                <td>
                    <input type="checkbox" name="mrcookies_use_domain" id="mrcookies_use_domain" value="1" <?php checked(mrcookies_get_option('mrcookies_use_domain')); ?> /> <small><?php echo mrcookies_text('Detects the domain of request to save the user confirmation.'); ?></small>
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php echo mrcookies_text('Style type'); ?></th>
                <td>
                    <?php $mrcookies_style_type = mrcookies_get_option('mrcookies_style_type'); ?>
                    <select name="mrcookies_style_type" id="mrcookies_style_type">
                        <option value="<?php echo MRCOOKIES_STYLE_DEFAULT; ?>" <?php selected($mrcookies_style_type, MRCOOKIES_STYLE_DEFAULT); ?>><?php echo mrcookies_text('Mr Cookies style'); ?></option>
                        <option value="<?php echo MRCOOKIES_STYLE_CUSTOM; ?>" <?php selected($mrcookies_style_type, MRCOOKIES_STYLE_CUSTOM); ?>><?php echo mrcookies_text('Custom CSS'); ?></option>
                    </select>
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php echo mrcookies_text('Text color'); ?></th>
                <td>
                    <input type="text" name="mrcookies_style_text_color" id="mrcookies_style_text_color" value="<?php echo esc_attr(mrcookies_get_option('mrcookies_style_text_color')); ?>" class="regular-text" mrcookies-color-picker />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php echo mrcookies_text('Link color'); ?></th>
                <td>
                    <input type="text" name="mrcookies_style_link_color" id="mrcookies_style_link_color" value="<?php echo esc_attr(mrcookies_get_option('mrcookies_style_link_color')); ?>" class="regular-text" mrcookies-color-picker />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php echo mrcookies_text('Background color'); ?></th>
                <td>
                    <input type="text" name="mrcookies_style_background_color" id="mrcookies_style_background_color" value="<?php echo esc_attr(mrcookies_get_option('mrcookies_style_background_color')); ?>" class="regular-text" mrcookies-color-picker />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php echo mrcookies_text('Border color'); ?></th>
                <td>
                    <input type="text" name="mrcookies_style_border_color" id="mrcookies_style_border_color" value="<?php echo esc_attr(mrcookies_get_option('mrcookies_style_border_color')); ?>" class="regular-text" mrcookies-color-picker />
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php echo mrcookies_text('Custom CSS'); ?></th>
                <td>
                    <textarea name="mrcookies_style_custom_css" rows="10" cols="50" id="mrcookies_style_custom_css" class="large-text"><?php echo mrcookies_get_option('mrcookies_style_custom_css'); ?></textarea>
                </td>
            </tr>
            
            <tr valign="top">
                <th scope="row"><?php echo mrcookies_text('Reset settings'); ?></th>
                <td>
                    <input type="checkbox" name="mrcookies_reset_settings" id="mrcookies_reset_settings" value="1" /> <small><?php echo mrcookies_text('Check to reset all options.'); ?></small>
                </td>
            </tr>
            
        </table>
        
        <?php submit_button(); ?>
    </form>
</div>