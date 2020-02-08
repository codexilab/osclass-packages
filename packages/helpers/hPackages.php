<?php
/*
 * Copyright 2019 - 2020 CodexiLab
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
 * Helpers
 * @author CodexiLab
 */

/**
 * Get the package by Id.
 *
 * @param string $id
 * @return string
 */
if (!function_exists('get_package_by_id')) {
    function get_package_by_id($id = null) {
    	if (View::newInstance()->_exists('packageById')) {
    		return View::newInstance()->_get("packageById");
    	} elseif ($id) {
    		return Packages::newInstance()->getPackageById($id);
    	}
    }
}

/**
 * Get default package Id.
 *
 * @return int
 */
if (!function_exists('get_default_package_id')) {
    function get_default_package_id() {
        return osc_get_preference('default_package', 'packages');
    }
}

/**
 * Get default package Id.
 *
 * @return int
 */
if (!function_exists('get_default_company_package_id')) {
    function get_default_company_package_id() {
        return osc_get_preference('default_company_package', 'packages');
    }
}


/**
 * Get all the dates about assigned package.
 *
 * @param string $userId
 * @return string
 */
if (!function_exists('get_package_assigned')) {
    function get_package_assigned($userId) {
        return Packages::newInstance()->getAssigned($userId);
    }
}


/**
 * Get the package name.
 *
 * @param int $packageId
 * @return string
 */
if (!function_exists('get_package_name')) {
    function get_package_name($packageId) {
        $package = Packages::newInstance()->getPackageById($packageId);
        return $package['s_name'];
    }
}

/**
 * Get packages by user type.
 *
 * @param string $value
 * @return int
 */
if (!function_exists('get_packages_by_user_type')) {
    function get_packages_by_user_type($value) {
        return Packages::newInstance()->getPackagesByUserType($value);
    }
}

/**
 * Get a options list about the packages that it can assigned the user passing her Id like parameter.
 *
 * @param string $userId
 */
function get_packages_htmloptions($userId) {
    if (get_user_type($userId)) {
        $packages = Packages::newInstance()->getPackagesByUserType(1);
        if ($packages) {
            foreach ($packages as $package) {
                echo '<option value="'.$package['pk_i_id'].'">'.$package['s_name'].'</option>';
            }
        }
    } else {
        $packages = Packages::newInstance()->getPackagesByUserType(0);
        if ($packages) {
            foreach ($packages as $package) {
                echo '<option value="'.$package['pk_i_id'].'">'.$package['s_name'].'</option>';
            }
        }
    }
}

/**
 * Get the number of free publications about of package.
 *
 * @param string $packageId
 * @return int
 */
if (!function_exists('get_package_free_items')) {
    function get_package_free_items($packageId) {
        $package = Packages::newInstance()->getPackageById($packageId);
        return (int) isset($package['i_free_items']) ? $package['i_free_items'] : 0;
    }
}

/**
 * Get the price of package.
 *
 * @param string $packageId
 * @return int
 */
function get_package_price($packageId) {
    $package = Packages::newInstance()->getPackageById($packageId);
    return $package['i_price'];
}


/**
 * Set url to "Choose" button for select package.
 *
 * @param string $packageId
 * @return int
 */
function choose_package_url($packageId) {
    $kwords = get_url_tags();
    $rwords = content_url_tags($packageId);
    $url = osc_get_preference('choose_package_url', 'packages');
    $url = str_ireplace($kwords, $rwords, $url);
    return setURL($url);
}

function choose_package_show($packageAssignedId = null) {
    switch (osc_get_preference('choose_package_show', 'packages')) {
        case '3':
            // Show only upgrade packages (by price)
            $packages = get_packages_by_user_type(osc_logged_user_type());
            $packagesOnSale = array();
            if ($packages) {
                foreach ($packages as $package) {
                    if ($package['i_price'] > get_package_price($packageAssignedId)) {
                        $packagesOnSale[] = $package;
                    }
                    
                }
            }
            return $packagesOnSale;
            break;

        case '2':
            // Show all packages (include free)
            return get_packages_by_user_type(osc_logged_user_type());
            break;

        case '1':
            // Do not show free packages
            $packages = get_packages_by_user_type(osc_logged_user_type());
            $packagesOnSale = array();
            if ($packages) {
                foreach ($packages as $package) {
                    if ($package['i_price'] > 0) {
                        $packagesOnSale[] = $package;
                    }
                    
                }
            }
            return $packagesOnSale;
            break;
        
        default:
            // Do not show packages
            return array();
            break;
    }
}

/**
 * Get information about current package in the logged user.
 *
 * @return array
 */
function get_package_info_current() {
    $currentPackage = new CurrentPackage();
    $currentPackage->packageAssigned();

    $current_packages = array($currentPackage->getInfo());
    $current_packages = osc_apply_filter("package_info_current_filter", $current_packages);

    $i = 0;
    foreach ($current_packages as $current_package) {
        $i++;
        if ($i == 1) {
            return $current_package;
        }
        break;
        return false;
    }
    return false;
}

/**
 * Check if the assigned package of current user logged.
 *
 * Publish free: true.
 * Pay: false.
 *
 * @return boolean true Publish free.
 * @return boolean false Charge the value of publication (Pay).
 */
function check_package_assignment() {
    $package = Session::newInstance()->_get('package_info_current');
    if ($package) {
        if ($package['defeated'] == true || $package['in_use'] == false) {
            return false;
        }
        return true;
    }
    return false;
}