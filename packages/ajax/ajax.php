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

define('IS_AJAX', true);

class CPackagesAdminAjax extends AdminSecBaseModel
{
	//Business Layer...
    public function doModel()
    {
        switch (Params::getParam("route")) {
            case 'assign_package_iframe':
                $userId = (Params::getParam("user")) ? Params::getParam("user") : 0;
                $this->_exportVariableToView('userId', $userId);
                $this->doView('admin/assign_package_iframe.php');
                break;

            case 'package_assigned_iframe':
                $userId = (Params::getParam("user")) ? Params::getParam("user") : 0;
                $assigment = Packages::newInstance()->getAssigned($userId);
                $packageAssigned = Packages::newInstance()->getPackageById($assigment['fk_i_package_id']);
                $this->_exportVariableToView('assigment', $assigment);
                $this->_exportVariableToView('packageAssigned', $packageAssigned);
                $this->doView('admin/package_assigned_iframe.php');
                break;
            
            default:
                echo __('no action defined');
                break;
        }
    }

    //hopefully generic...
    function doView($file)
    {
        include PACKAGES_PATH. 'views/'.$file;
    }
}