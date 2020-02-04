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
 
/*
Plugin Name: Promotional packages
Plugin URI: https://github.com/codexilab/osclass-packages
Description: Promotional packages system
Version: 1.1
Author: CodexiLab
Author URI: https://github.com/codexilab
Short Name: packages
Plugin update URI: https://github.com/codexilab/osclass-packages
*/

	// Paths
	define('PACKAGES_FOLDER', 'packages/');
	define('PACKAGES_PATH', osc_plugins_path().PACKAGES_FOLDER);

	
	// Prepare model, controllers and helpers
	require_once PACKAGES_PATH . "oc-load.php";

	
	// Routes
	osc_add_route('packages-admin', PACKAGES_FOLDER.'admin/packages', PACKAGES_FOLDER.'admin/packages', PACKAGES_FOLDER.'views/admin/packages.php');
	osc_add_route('packages-admin-users', PACKAGES_FOLDER.'admin/users', PACKAGES_FOLDER.'admin/users', PACKAGES_FOLDER.'views/admin/users.php');
	osc_add_route('packages-admin-settings', PACKAGES_FOLDER.'admin/settings', PACKAGES_FOLDER.'admin/settings', PACKAGES_FOLDER.'views/admin/settings.php');

	
	/**
	 * Headers in the admin panel
	 */
	osc_add_hook('admin_menu_init', function() {
	    osc_add_admin_submenu_divider(
	        "plugins", __("Packages", 'packages'), "packages", "administrator"
	    );

	    osc_add_admin_submenu_page(
	        "plugins", __("Manage packages", 'packages'), osc_route_admin_url("packages-admin"), "packages-admin", "administrator"
	    );

	    // For extensions menues
	    osc_run_hook('packages_admin_menu_init');

	    osc_add_admin_submenu_page(
	        "plugins", __("Settings", 'packages'), osc_route_admin_url("packages-admin-settings"), "packages-admin-settings", "administrator"
	    );
	});


	/**
	 * Load the controllers, depend of url route
	 */
	function packages_admin_controllers() {
		switch (Params::getParam("route")) {
			case 'packages-admin':
				$filter = function($string) {
	                return __("Packages", 'packages');
	            };

	            // Page title (in <head />)
	            osc_add_filter("admin_title", $filter, 10);

	            // Page title (in <h1 />)
	            osc_add_filter("custom_plugin_title", $filter);

	            $do = new CAdminPackages();
	            $do->doModel();
				break;

			case 'packages-admin-settings':
				$filter = function($string) {
	                return __("Settings - Packages", 'packages');
	            };

	            // Page title (in <head />)
	            osc_add_filter("admin_title", $filter, 10);

	            // Page title (in <h1 />)
	            osc_add_filter("custom_plugin_title", $filter);

	            $do = new CAdminPackagesSettings();
	            $do->doModel();
				break;
		}
	}
	osc_add_hook("renderplugin_controller", "packages_admin_controllers");


	/**
	 * Customs for the UsersDataTable (Manage users):
	 */

	// Custom controller for new actions in Manage users
	function custom_actions_manage_users() {
		if (Params::getParam("page") == "users") {
			$do = new CCustomAdminUsers();
	        $do->doModel();
		}
	}
	osc_add_hook("before_admin_html", "custom_actions_manage_users");

	/**
	 * To allocate packages massively from 'Mange users', uncomment the line 79 of index.php file of plugin.
	 * And the following code osc_run_hook('extension_user_bulk'); paste it in oc-admin/themes/modern/users/index.php since line 279:
	 */
	function extend_bulk_options_manage_users() {
		include "parts/admin/user_bulk_select.php";
	}
	osc_add_hook("extension_user_bulk", "extend_bulk_options_manage_users");
	/* Use osc_run_hook('extension_user_bulk'); */

	function custom_user_bulk_options($bulk_options) {
		//$bulk_options[] = array('value' => 'assign_package', 'data-dialog-content' => sprintf(__('Are you sure you want to %s(s)?'), strtolower(__('Assign package'))), 'label' => __('Assign package'));
		$bulk_options[] = array('value' => 'remove_package', 'data-dialog-content' => sprintf(__("Are you sure you want to %s assigned(s)?", 'packages'), strtolower(__("Remove package", 'packages'))), 'label' => __("Remove package", 'packages'));
		return $bulk_options;
	}
	osc_add_filter("user_bulk_filter", 'custom_user_bulk_options');

	// Custom more options for UsersDataTable (Manage users)
	function custom_user_add_more_action ($options_more, $aRow) {
		$options_more[] = '<a href="#" onclick="assign_package_dialog('.$aRow['pk_i_id'].');return false;">'.__("Assign package", 'packages').'</a>';
		if (get_package_assigned($aRow['pk_i_id'])) {
			$options_more[] = '<a href="#" onclick="remove_package_dialog('.$aRow['pk_i_id'].');return false;">'.__("Remove package", 'packages').'</a>';
		}
		return $options_more;
	}
	osc_add_filter('more_actions_manage_users', 'custom_user_add_more_action');

	// Add column 'Package' in UsersDataTable (Manage users)
	function columns_users_table_header($table) {
		$table->addColumn("package", __("Package", 'packages'));
	}
	osc_add_hook("admin_users_table", "columns_users_table_header");

	// Content of column 'Package' in UsersDataTable (Manage users)
	function columns_users_row($row, $aRow) {
		$package = get_package_assigned($aRow['pk_i_id']);
		$row['package'] = (!$package) ? __("Unassigned", 'packages') : '<a href="#" onclick="package_assigned_dialog('.$aRow['pk_i_id'].');return false;">'.get_package_name($package['fk_i_package_id']).'</a>';
		return $row;
	}
	osc_add_filter('users_processing_row', 'columns_users_row');

	// Add custom CSS Styles in oc-admin
	function packages_custom_css_admin() {
		if (Params::getParam('page') == "users") {
			osc_enqueue_style('packageBox', osc_base_url() . 'oc-content/plugins/'. PACKAGES_FOLDER. 'assets/css/admin/packagebox-button.css');
		}
	}
	osc_add_hook('init_admin', 'packages_custom_css_admin');

	function custom_content_manage_users() {
		if (Params::getParam("page") == "users") {
			include PACKAGES_PATH . 'parts/admin/content_manage_users.php';
		}
	}
	osc_add_hook('after_show_pagination_admin', 'custom_content_manage_users');


	/**
	 * The content of this function it will show by ajax request on this url:
	 * <?php echo osc_base_url(); ?>index.php?page=ajax&action=runhook&hook=packages_admin_ajax
	 */
	function packages_admin_ajax() {
		$do = new CPackagesAdminAjax();
	    $do->doModel();
	}
	osc_add_hook("ajax_packages_admin_ajax", "packages_admin_ajax");


	// When a user is registered, it assign the default package (if exists)
    function packages_default_assignment($userId) {
    	// If the registration come from a User
        if (get_default_package_id() > 0 && !get_user_type($userId)) {
            Packages::newInstance()->assignPackage(get_default_package_id(), $userId);
        }

        // If the registration come from a Company
        if (get_default_company_package_id() > 0 && get_user_type($userId)) {
            Packages::newInstance()->assignPackage(get_default_company_package_id(), $userId);
        }
    }
    osc_add_hook('user_register_completed', 'packages_default_assignment');


    /**
	 * Hook to show Package information in profile theme
	 * So you can use osc_run_hook('packages_profile_info'); and comment the filter
	 */
	function packages_profile_info($options = null) {
		if($modules == null) {
	        $modules = array();
	        $modules[] = osc_run_hook('before_packages_profile_info');
	        include PACKAGES_PATH . 'parts/user/packages_profile_info.php';
	        $modules[] = osc_run_hook('after_packages_profile_info');
	    }
	    $modules = osc_apply_filter('packages_modules_filter', $modules);
		
		// Show from user menu
		if (osc_get_preference('packages_profile_info', 'packages')) {
			$options[] = $modules;
			return $options;
		} else {
			$modules;
		}
	}
	// Show from user menu
	if (osc_get_preference('packages_profile_info', 'packages')) {
		osc_add_filter('user_menu_filter', 'packages_profile_info');
	} else {
		osc_add_hook('packages_profile_info', 'packages_profile_info');
	}

    // CSS Style for packages_profile_info()
    function packages_profile_info_style() {
		osc_enqueue_style('packagesInfo', osc_base_url().'oc-content/plugins/'.PACKAGES_FOLDER.'assets/css/user/packages-profile-info.css');
		osc_enqueue_style('modalDialog', osc_base_url().'oc-content/plugins/'.PACKAGES_FOLDER.'assets/css/user/modal-dialog.css');
		osc_enqueue_style('flexPricing', osc_base_url().'oc-content/plugins/'.PACKAGES_FOLDER.'assets/css/user/flex-pricing.css');
	}
	osc_add_hook('header', 'packages_profile_info_style');

	
	/**
     * When the user account change of type, it assign default package or it delete the current;
     * depending if did make the change from "Company" to "User" or vice versa.
     */
    function packages_before_update_profile() {
        if (osc_logged_user_type()) {
            /**
             * If a user is of type "Company" and when the field 'b_company' change to 0 ("user")
             * will delete the current package and all it related with that assignment and it will assign the default package.
             */
            if (!Params::getParam('b_company')) {

                // Deleting of about of current assignment
                $items = Item::newInstance()->findByUserID(osc_logged_user_id());
                if ($items) {
                    foreach ($items as $item) {
                        Packages::newInstance()->delItemRelationByItemId($item['pk_i_id']);
                    }
                }
                Packages::newInstance()->deleteAll(osc_logged_user_id());
            }
        } else {
            /**
             * If a user is type "user" and when the field 'b_company' change to 1 ("company")
             * it will delete the default package and all it related with that assignment.
             */
            if (Params::getParam('b_company')) {

                // Deleting of all about the current assignment
                $items = Item::newInstance()->findByUserID(osc_logged_user_id());
                if ($items) {
                    foreach ($items as $item) {
                        Packages::newInstance()->delItemRelationByItemId($item['pk_i_id']);
                    }
                }
                Packages::newInstance()->deleteAll(osc_logged_user_id());
            }
        }
    }
    osc_add_hook('pre_user_post', 'packages_before_update_profile');

    // When the an account is deleted, it will delete all trail of activity with some package
    function packages_when_delete_user($id) {
        $items = Item::newInstance()->findByUserID($id);
        if ($items) {
            foreach ($items as $item) {
                Packages::newInstance()->delItemRelationByItemId($item['pk_i_id']);
            }
        }
        Packages::newInstance()->deleteAll($id);
    }
    osc_add_hook('delete_user', 'packages_when_delete_user');

    // It does not allow to use the publication form if the user's current package: it is sold out or expired
    function packages_item_before_post() {
    	$package = get_package_info_current();
    	Session::newInstance()->_drop('package_info_current');
    	Session::newInstance()->_set('package_info_current', $package);

    	// Situation 1
    	if (!payment_method_enabled()) {
    		// Situation 1.1
    		if (!$package) {
    			osc_redirect_to(osc_user_list_items_url());

    		// Situation 1.2
    		} elseif($package['in_use'] == false || $package['defeated'] == true) {
    			osc_redirect_to(osc_user_list_items_url());
    		}
    	}

    	// Situation 2
    	if (payment_method_enabled() && !pay_per_post()) {
    		// Situation 2.1
    		if (!$package) {
    			osc_redirect_to(osc_user_list_items_url());

    		// Situation 2.2
    		} elseif($package['in_use'] == false || $package['defeated'] == true) {
    			osc_redirect_to(osc_user_list_items_url());
    		}
    	}
    	
    }
    osc_add_hook('post_item', 'packages_item_before_post');

    // It does not allow that it keep active a item if the package is defeated or expired
    function packages_item_after_activate($id) {
        $package = get_package_info_current();

        // Inherited or Assigned package:
        if (!$package['in_use'] || $package['defeated'] == true) {
            $mItems = new ItemActions(false);
            $mItems->deactivate($id);
            osc_add_flash_error_message(__("You cannot activate publications", 'packages'));
        }
    }
    osc_add_hook('activate_item', 'packages_item_after_activate');

    // It does not allow edit a item if this is not active
    function packages_item_before_edit($item) {
        if (!$item['b_active'] || $item['dt_expiration'] < date('Y-m-d H:i:s')) {
            osc_redirect_to(osc_user_list_items_url());
        }
    }
    osc_add_hook('before_item_edit', 'packages_item_before_edit');

    /**
     * In the dashboard will deploy a modal windows with a diferente message
     * about of the current status from package depending if is inherited or assigned.
     */
    function packages_profile_message() {
        if (get_current_url() == osc_user_list_items_url()) {

        	$package = get_package_info_current();
            
            /**
             * Assigned package:
             */

            // If the package is empty
            if ($package && $package['status'] == 'assigned' && $package['defeated'] == true) {
                
                osc_add_flash_error_message(__("Your package is defeated", 'packages'));

            }

            // If the package is not expired but is defeated
            if ($package && $package['status'] == 'assigned' && $package['defeated'] == false && $package['in_use'] == false) {
                
                osc_add_flash_error_message(__("You have used all the package that has assigned ¡upgrade!", 'packages'));
            
            }
            
        }
    }
    osc_add_hook('header', 'packages_profile_message');

    function packages_item_form_post($item) {
        $package = get_package_info_current();
        if ($package && check_package_assignment() == true) {
            Packages::newInstance()->addItemRelation($item['pk_i_id'], $package['assignment_id']);
        }
    }
    osc_add_hook('posted_item', 'packages_item_form_post');

    function packages_delete_item($itemID) {
        Packages::newInstance()->delItemRelationByItemId($itemID);
    }
    osc_add_hook('delete_item', 'packages_delete_item');


    // Delete current package info session
    function package_info_current_drop() {
    	Session::newInstance()->_drop('package_info_current');
    }
    osc_add_hook('logout_user', 'package_info_current_drop');


	/**
	 * 'Configure' link
	 */
	function packages_configure_admin_link() {
		osc_redirect_to(osc_route_admin_url('packages-admin-settings'));
	}

	// Show 'Configure' link at plugins table
	osc_add_hook(osc_plugin_path(__FILE__)."_configure", 'packages_configure_admin_link');


	/**
	 * Call uninstallation method from model (model/Packages.php)
	 */
	function packages_uninstall() {
		Packages::newInstance()->uninstall();
	}

	// Show an Uninstall link at plugins table
	osc_add_hook(osc_plugin_path(__FILE__) . '_uninstall', 'packages_uninstall');


	/**
	 * Call the process of installation method 
	 */
	function packages_install() {
		Packages::newInstance()->install();
	}

	// Register plugin's installation
	osc_register_plugin(osc_plugin_path(__FILE__), 'packages_install');
