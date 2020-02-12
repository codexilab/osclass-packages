<?php if ( ! defined('ABS_PATH')) exit('ABS_PATH is not loaded. Direct access is not allowed.');

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
 * Custom Controller for Manage Users
 */
class CCustomAdminUsers extends AdminSecBaseModel
{
	//Business Layer...
    public function doModel()
    {
		switch (Params::getParam('action')) {
    		case 'assign_package':
    			osc_csrf_check();
				if (Params::getParam('packageId')!='') {
			        // Package data
			        $i = 0;
			        $packageId  = Params::getParam('packageId');
			        $userId     = Params::getParam('id');

			        if (!is_array($userId)) {
			            osc_add_flash_error_message(__('Select user.', 'packages'), 'admin');
			        } else {
			            foreach ($userId as $id) {
			                // Check if package and user type at the same to continue...
			                if (get_package_type($packageId) == get_user_type($id)) {
			                	// Remove current package assigned, if have it!
				                if (Packages::newInstance()->getAssigned($id)) {
				                    Packages::newInstance()->removePackageAssigned($id);
				                }
			                	if (Packages::newInstance()->assignPackage($packageId, $id)) $i++;
			                }
			            }

			            if ($i == 0) {
			                osc_add_flash_error_message(__('Any package could not be assigned.', 'packages'), 'admin');
			            } else {
			                osc_add_flash_ok_message(sprintf(__('%s package(s) has been assigned correctly.', 'packages'), $i), 'admin');  
			            } 
			        }
			    } else {
			        osc_add_flash_error_message(__('Select package.', 'packages'), 'admin');
			    }
			    $this->redirectTo(osc_admin_base_url(true) . '?page=users');
			    break;

			case 'remove_package':
				osc_csrf_check();
				$i = 0;
			    $userId = Params::getParam('id');

			    if (!is_array($userId)) {
			        osc_add_flash_error_message(__('Select assigment.', 'packages'), 'admin');
			    } else {
			        foreach ($userId as $id) {
			            if (Packages::newInstance()->removePackageAssigned($id)) $i++;
			        }
			        if ($i == 0) {
			            osc_add_flash_error_message(__('Any package have been removed.', 'packages'), 'admin');
			        } else {
			            osc_add_flash_ok_message(sprintf(__('%s package(s) have been removed.', 'packages'), $i), 'admin');
			        }
			    }
			    $this->redirectTo(osc_admin_base_url(true) . '?page=users');
				break;
		}
    }
}