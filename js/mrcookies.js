/**
 * Default configuration
 * @type Object
 */
var MRCOOKIES_CONFIG_DEFAULT = {
    cookie_name: 'MRCOOKIES_ACCEPT_NAVIGATION',
    cookie_life: 365,
    legal_notice: false,
    domain: true,
    i18n: {
        notice: 'This website uses own and/or third parties cookies to: analyze, personalize content and/or advertising. If you continue browsing, we consider that you accept the use.',
        link: 'More information.',
        close: 'Close'
    }
};

/**
 * Configuration
 * @type Object
 */
var MRCOOKIES_CONFIGS = {};

/**
 * Cookies are accepted
 * @type Boolean
 */
var MRCOOKIES_ACCEPTED = false;

/**
 * Storage
 * @type Object
 */
var MRCOOKIES_STORAGE;
try {
    if (localStorage.getItem) {
        MRCOOKIES_STORAGE = localStorage;
    }
} catch(e) {
    MRCOOKIES_STORAGE = {};
}

/**
 * Get domain name
 * @returns {String}
 */
function MRCOOKIES_GetDomainName()
{
    return window.location.hostname;
}

function MRCOOKIES_MergeObjects(a, b)
{
    var i;
    for (i in b)
    {
        if (typeof a[i] === 'undefined')
        {
            a[i] = b[i];
        }
        else if (typeof a[i] === 'object' || typeof a[i] === 'array')
        {
            a[i] = MRCOOKIES_MergeObjects(a[i], b[i]);
        }
        else
        {
            a[i] = b[i];
        }
    }
    return a;
}

function MRCOOKIES_LoadConfig()
{
    if (typeof MRCOOKIES === 'undefined')
    {
        MRCOOKIES_CONFIGS = MRCOOKIES_CONFIG_DEFAULT;
    } else {
        MRCOOKIES_CONFIGS = MRCOOKIES_MergeObjects(MRCOOKIES_CONFIG_DEFAULT, MRCOOKIES);
    }
    
    var useDomain = MRCOOKIES_CONFIGS.domain;
    if (useDomain && useDomain !== '')
    {
        var domain = MRCOOKIES_GetDomainName();
        MRCOOKIES_CONFIGS.cookie_name += '_' + domain;
    }
    console.log(MRCOOKIES_CONFIGS.cookie_name);
    
    if (!MRCOOKIES_CONFIGS.i18n)
    {
        MRCOOKIES_CONFIGS.i18n = MRCOOKIES_CONFIG_DEFAULT.i18n;
    }
    var key;
    for (key in MRCOOKIES_CONFIG_DEFAULT.i18n) {
        if (typeof MRCOOKIES_CONFIGS.i18n[key] === 'undefined') {
            MRCOOKIES_CONFIGS.i18n[key] = MRCOOKIES_CONFIG_DEFAULT.i18n[key];
        }
    }
}

function MRCOOKIES_GetConfig(name, default_value)
{
    return (typeof MRCOOKIES_CONFIGS[name] === 'undefined') ? default_value : MRCOOKIES_CONFIGS[name];
}

function MRCOOKIES_GetText(name)
{
    return (typeof MRCOOKIES_CONFIGS.i18n[name] === 'undefined') ? MRCOOKIES_CONFIG_DEFAULT.i18n[name] : MRCOOKIES_CONFIGS.i18n[name];
}

/**
 * Return a cookie
 * @param {String} c_name Cookie name
 * @param {Mixed} c_default Cookie default value
 * @returns {Mixed} Value of cookie
 */
function MRCOOKIES_GetCookie(c_name, c_default)
{
    var i, x, y, allCookies = document.cookie.split(';');
    for (i=0; i<allCookies.length; i++)
    {
        x = allCookies[i].substr(0,allCookies[i].indexOf('='));
        y = allCookies[i].substr(allCookies[i].indexOf('=') + 1);
        x = x.replace(/^\s+|\s+$/g, '');
        if (x === c_name)
        {
         return unescape(y);
        }
    }
    if (typeof MRCOOKIES_STORAGE[c_name] !== 'undefined')
    {
        return MRCOOKIES_STORAGE[c_name];
    }
    return c_default;
}

/**
 * Set a cookie
 * @param {String} c_name Cookie name
 * @param {String} c_value Cookie value
 * @param {Int} c_life_in_days Cookie life time (In days)
 * @returns {Boolean}
 */
function MRCOOKIES_SetCookie(c_name, c_value, c_life_in_days)
{
	var c_expiration = new Date();
	c_expiration.setDate(c_expiration.getDate() + c_life_in_days);
	var c_value = escape(c_value) + ((c_life_in_days === null) ? '' : '; expires=' + c_expiration.toUTCString());
	document.cookie = c_name + '=' + c_value;
    MRCOOKIES_STORAGE[c_name] = c_value;
    return true;
}

/**
 * Show alert of cookies usage
 * @returns {Boolean}
 */
function MRCOOKIES_ShowAlert()
{   
    var A_wrapper = document.createElement('div');
    A_wrapper.id = 'mrcookies-wrapper';
    var A_container = document.createElement('div');
    A_container.id = 'mrcookies-container';
    var A_text = document.createTextNode(MRCOOKIES_GetText('notice') + ' ');
    A_container.appendChild(A_text);
    
    var urlNotice = MRCOOKIES_GetConfig('legal_notice', false);
    if (urlNotice && urlNotice !== '')
    {
        var A_link = document.createElement('a');
        var A_linkText = document.createTextNode(MRCOOKIES_GetText('link'));
        A_link.appendChild(A_linkText);
        A_link.href = urlNotice;
        A_container.appendChild(A_link);
    }
    
    var A_closeLink = document.createElement('a');
    var A_closeLinkText = document.createTextNode(MRCOOKIES_GetText('close'));
    A_closeLink.appendChild(A_closeLinkText);
    A_closeLink.href = 'javascript:MRCOOKIES_CloseAlert();';
    A_closeLink.title = MRCOOKIES_GetText('close');
    A_closeLink.id = 'mrcookies-close';
    A_container.appendChild(A_closeLink);
    
    A_wrapper.appendChild(A_container);
    
    var body = document.getElementsByTagName('body')[0];
    body.insertBefore(A_wrapper, body.firstChild);
    
    return true;
}

function MRCOOKIES_CloseAlert()
{
    var now = new Date();
    MRCOOKIES_SetCookie(MRCOOKIES_GetConfig('cookie_name', ''), now.getTime(), MRCOOKIES_GetConfig('cookie_life', ''));
    document.getElementById('mrcookies-wrapper').style.display = 'none';
}


(function(){
    MRCOOKIES_LoadConfig();
    var cookie = MRCOOKIES_GetCookie(MRCOOKIES_GetConfig('cookie_name', ''), false);
    if (!cookie)
    {
        MRCOOKIES_ShowAlert();
    }
})();

