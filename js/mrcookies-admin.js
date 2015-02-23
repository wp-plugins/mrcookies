jQuery(document).ready(function() {
    jQuery('[mrcookies-color-picker]').wpColorPicker();
    
    jQuery('#mrcookies_legal_notice_type').change(function(){
        var type = jQuery(this).val();
        if (type == 1)
        {
            jQuery('#mrcookies_legal_notice_wrapper').show();
            jQuery('#mrcookies_legal_notice_external_wrapper').hide();
        }
        else if (type == 2)
        {
            jQuery('#mrcookies_legal_notice_external_wrapper').show();
            jQuery('#mrcookies_legal_notice_wrapper').hide();
        }
    });
});