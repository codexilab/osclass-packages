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
 * Controller Settings Packages system plugin
 */
class CAdminPackagesSettings extends AdminSecBaseModel
{

    //Business Layer...
    public function doModel()
    {

        switch (Params::getParam('plugin_action')) {
            case 'done':
                if (Params::getParam('packages_profile_info') != osc_get_preference('packages_profile_info', 'packages')) {
                    osc_set_preference('packages_profile_info', Params::getParam('packages_profile_info'), 'packages', 'BOOLEAN');    
                }
                
                if (Params::getParam('choose_package_url') != osc_get_preference('choose_package_url', 'packages')) {
                    osc_set_preference('choose_package_url', Params::getParam('choose_package_url'), 'packages', 'STRING');
                }

                if (Params::getParam('choose_package_show') != osc_get_preference('choose_package_show', 'packages')) {
                    osc_set_preference('choose_package_show', Params::getParam('choose_package_show'), 'packages', 'STRING');
                }
            
                osc_run_hook('admin_packages_settings_done');
                osc_add_flash_ok_message(__('The plugin is now configured', 'packages'), 'admin');
                ob_get_clean();
                $this->redirectTo(osc_route_admin_url('packages-admin-settings'));
                break;
            
            default:
                osc_run_hook('admin_packages_settings');
                break;
        }
    }
    
}