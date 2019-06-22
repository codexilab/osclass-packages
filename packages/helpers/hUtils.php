<?php
/*
 * Copyright 2019 CodexiLab
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
 
/**
 * Utils general helpers 
 * @author CodexiLab
 */

/**
 * Get the current date or the sum depending of her parameters.
 *
 * - Example of use 1: echo todaydate(); Show the current date.
 * - Example of use 2: echo todaydate(3, 'days'); Show the current date but sum 3 days.
 * - Example of use 3: echo todaydate(3, 'years'); Show the current date but sum 3 years.
 *
 * @param string $time The default value is H:i:s but can change during the estatement of function.
 * @param int $num It defined null at the start as it can be empty, on the contrary must have a integer numeric value.
 * @param string $ymd It defined null at the start as ir can be empty, on the contrary so it specific 'days', 'years' or 'month'.
 * @return string $date Return the current in the H:i:s format.
 * @return string $dateplus Return the current date added.
 */
if (!function_exists('todaydate')) {
    function todaydate($num = null, $ymd = null, $time = 'H:i:s') {
        $date = date('Y-m-d '.$time);

        if ($num && $ymd) {
            $dateplus = strtotime('+'.$num.' '.$ymd, strtotime($date));
            $dateplus = date('Y-m-d H:i:s', $dateplus);
            return $dateplus;
        } else {
            return $date;
        }
    }
}

/**
 * Check expiration betwen two dates.
 *
 * @return true If is valid.
 * @return false If is not valid.
 */
if (!function_exists('check_date_interval')) {
    function check_date_interval($fromDate, $sinceDate) {
        if (todaydate() >= $fromDate && todaydate() <= $sinceDate) {
            return false;
        } else {
            return true;
        }
    }
}

/**
 * Compare two variables, if they are equals return the html 'selected' atribute.
 *
 * @return string
 */
if (!function_exists('get_html_selected')) {
    function get_html_selected($a, $b) {
        return ($a == $b) ? 'selected="selected"' : '';
    }
}

/**
 * Compare two variables, if they are equals return the html 'checked' atribute.
 *
 * @return string
 */
if (!function_exists('get_html_checked')) {
    function get_html_checked($a, $b) {
        return ($a == $b) ? 'checked="true"' : '';
    }
}

/**
 * Get Id of user.
 *
 * @return int
 */
if (!function_exists('get_user_id')) {
    function get_user_id() {
        if (osc_user_id()) {
            return osc_user_id();
        } else {
            return Params::getParam('id');
        }
    }
}

/**
 * Get the user name by Id.
 *
 * @param int $id
 * @return string
 */
 if (!function_exists('get_user_name')) {
    function get_user_name($id) {
        $user = User::newInstance()->findByPrimaryKey($id);
        return isset($user['s_name']) ? $user['s_name'] : 0;
    }
}

/**
 * Get the type account of user.
 *
 * @param string $id
 * @return boolean If is true, is 'company' type account on the contrary if false the account type is 'user'.
 */
if (!function_exists('get_user_type')) {
    function get_user_type($id = null) {
        if ($id == null) {
            return osc_user_is_company();
        } else {
            $user = User::newInstance()->findByPrimaryKey($id);
            return (bool) $user['b_company'];
        }
    }
}

if (!function_exists('osc_logged_user_type')) {
    function osc_logged_user_type() {
        return get_user_type(osc_logged_user_id());
    }
}

/**
 * Get the current URL.
 *
 * If the current URL detected it have https, it will add the https:// to the URL.
 *
 * @return string
 */
if (!function_exists('get_current_url')) {
    function get_current_url() {
        return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }
}

/**
 * Make a text string be a valid URI address (include mailto).
 * 
 * - Example of use 1: echo setURL("name@email.com"); Show mailto:name@email.com
 * - Example of use 2: echo setURL("mailto:name@email.com"); Show mailto:name@email.com
 * - Example of use 3: echo setURL("websitename.com"); Show http://websitename.com
 * - Example of use 4: echo setURL("http://websitename.com"); Show http://websitename.com
 * - Example of use 5: echo setURL("https://websitename.com"); Show https://websitename.com
 *
 * @param string $url
 * @return string
 */
if (!function_exists('setURL')) {
    function setURL($url) {
        $allowed = ['mailto'];
        $parsed = parse_url($url);
        if (in_array($parsed['scheme'], $allowed)) {
            return $url;

        } elseif (preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/', $url)) {
        return 'mailto:'.$url;

        // wthout localhost  '/^(http|https):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i'
        // with localhost    '/^(http|https):\/\/+(localhost|[A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i'
        } elseif (preg_match('/^((http|https):\/\/?)[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|\/?))/i', $url)) {
            return $url;

        } else {
            return 'http://'.$url;
        }
    }
}

/**
 * Get pay frequency.
 *
 * @param string $userId
 */
function get_pay_frequency($var) {
    switch ($var) {
        case 'month':
            return __("Monthly", 'packages');
            break;

        case 'quarterly':
            return __("Quarterly", 'packages');
            break;

        case 'year':
            return __("Yearly", 'packages');
            break;
    }
}

function get_url_tags() {
    return array('{PACKAGE_ID}', '{OSC_CSRF_TOKEN_URL}', '{GET_CURRENT_URL}', '{OSC_BASE_URL}', '{OSC_CONTACT_URL}', '{OSC_REGISTER_ACCOUNT_URL}', '{OSC_USER_LOGIN_URL}', '{OSC_CHANGE_USER_EMAIL_URL}', '{OSC_RECOVER_USER_PASSWORD_URL}', '{OSC_ITEM_POST_URL}', '{OSC_USER_LIST_ITEMS_URL}', '{OSC_USER_DASHBOARD_URL}', '{OSC_USER_PROFILE_URL}', '{OSC_CHANGE_USERNAME_URL}', '{OSC_CHANGE_USER_PASSWORD_URL}', '{OSC_ITEM_POST_URL_IN_CATEGORY}', '{OSC_STATIC_PAGE_URL}', '{OSC_USER_ALERTS_URL}', '{OSC_USER_LOGOUT_URL}');
}
function content_url_tags($packageId) {
    return array($packageId, osc_csrf_token_url(), get_current_url(), osc_base_url(), osc_contact_url(), osc_register_account_url(), osc_user_login_url(), osc_change_user_email_url(), osc_recover_user_password_url(), osc_item_post_url(), osc_user_list_items_url(), osc_user_dashboard_url(), osc_user_profile_url(), osc_change_user_username_url(), osc_change_user_password_url(), osc_item_post_url_in_category(), osc_static_page_url(), osc_user_alerts_url(), osc_user_logout_url());
}

/**
 * Empty a file.
 *
 * @param string $file
 */
function packages_empty_file($file) {
    $result = false;
    if (!is_writable($file)) @chmod($file, 0777);
    $f = @fopen($file, "r+");
    if ($f !== false) {
        if (ftruncate($f, 0)) $result = true;
        fclose($f);
    }
    return $result;
}

/**
 * Get source file of a file (parsed with htmlspecialchars).
 *
 * @param string $file
 */
function packages_source_file($file) {
    $source = "";
    if (file_exists($file) && !is_dir($file)) {
        $source = htmlspecialchars(file_get_contents($file));
    }
    return $source;
}

/**
 * Format a file size information from bytes (default) to:
 *
 * - Kilobytes (kB)
 * - Megabytes (Mb)
 * - Gigabytes (GB)
 * - Terabytes (TB)
 *
 * @param int $bytes
 * @param int $precision default value 1
 */
function formatBytes($bytes, $precision = 1) {
    $base       = log($bytes, 1024);
    $suffixes   = array('bytes', 'kB', 'Mb', 'GB', 'TB');
    $units      = round(pow(1024, $base - floor($base)), $precision);
    $format     = $suffixes[floor($base)];
    if ($units.' '.$format == 'NAN bytes') {
        return '0 '.$format;
    }
    return $units.' '.$format;
}

/**
 * Get file size of a file.
 *
 * @param string $path
 * @param string $fileName
 */
function packages_get_filesize($path, $fileName) {
    $file = $path.$fileName;
    if (file_exists($file) && !is_dir($file)) {
        $filesize = filesize($file);
        return formatBytes($filesize);
    }
    return 0;
}