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
 * Controller Packages system plugin
 */
class CAdminPackages extends AdminSecBaseModel
{

    //Business Layer...
    public function doModel()
    {
        $packageById = Packages::newInstance()->getPackageById(Params::getParam('package'));

        switch (Params::getParam('plugin_action')) {
            case 'set':
                $packageId  = ($packageById) ? $packageById['pk_i_id'] : 0;
                $freeItems  = (Params::getParam('i_free_items') == '') ? 0 : Params::getParam('i_free_items');
                $price      = (Params::getParam('i_price') == '') ? 0 : Params::getParam('i_price');

                // Form validation
                if (!is_numeric($freeItems) || !is_numeric($price)) {
                    osc_add_flash_error_message(__('Free listings and Price are numeric fields.', 'packages'), 'admin');

                } elseif (!Params::getParam('s_name') || !Params::getParam('i_free_items')) {
                    osc_add_flash_error_message(__('The Name and Free listings can not by empty.', 'packages'), 'admin');

                } else {
                    $data = array(
                        'pk_i_id'           => $packageId,
                        's_name'            => Params::getParam('s_name'),
                        'b_company'         => Params::getParam('b_company'),
                        'i_free_items'      => $freeItems,
                        's_pay_frequency'   => Params::getParam('s_pay_frequency'),
                        'b_active'          => Params::getParam('b_active'),
                        'i_price'           => $price
                    );
                    
                    // Create package
                    if (!$packageId) {
                        $data['dt_date'] = todaydate();
                        Packages::newInstance()->setPackage($data);
                        osc_add_flash_ok_message(__('A new package has been added correctly.', 'packages'), 'admin');

                    // Update package, if exist the package
                    } else {
                        $data['dt_update'] = todaydate();
                        Packages::newInstance()->setPackage($data);
                        osc_add_flash_ok_message(__('The package has been updated correctly.', 'packages'), 'admin');
                    }
                    
                }
                ob_get_clean();
                $this->redirectTo($_SERVER['HTTP_REFERER']);
                break;

            case 'delete':
                $i = 0;
                $packagesId = Params::getParam('id');

                if (!is_array($packagesId)) {
                    osc_add_flash_error_message(__('Select package.', 'packages'), 'admin');
                } else {
                    foreach ($packagesId as $id) {
                        if (Packages::newInstance()->deletePackage($id)) $i++;
                    }
                    if ($i == 0) {
                        osc_add_flash_error_message(__('No package have been deleted.', 'packages'), 'admin');
                    } else {
                        osc_add_flash_ok_message(sprintf(__('%s package(s) have been deleted.', 'packages'), $i), 'admin');
                    }
                }
                ob_get_clean();
                $this->redirectTo($_SERVER['HTTP_REFERER']);
                break;

            case 'activate':
                $i = 0;
                $packagesId = Params::getParam('id');

                if (!is_array($packagesId)) {
                    osc_add_flash_error_message(__('Select package.', 'packages'), 'admin');
                } else {
                    foreach ($packagesId as $id) {
                        $data = array(
                            'pk_i_id'   => $id,
                            'dt_update' => todaydate(),
                            'b_active'  => 1
                        );
                        if (Packages::newInstance()->setPackage($data)) $i++;
                    }
                    if ($i == 0) {
                        osc_add_flash_error_message(__('No packages have been activated.', 'packages'), 'admin');
                    } else {
                        osc_add_flash_ok_message(sprintf(__('%s package(s) have been activated.', 'packages'), $i), 'admin');
                    }
                }
                ob_get_clean();
                $this->redirectTo($_SERVER['HTTP_REFERER']);
                break;

            case 'deactivate':
                $i = 0;
                $packagesId = Params::getParam('id');

                if (!is_array($packagesId)) {
                    osc_add_flash_error_message(__('Select package.', 'packages'), 'admin');
                } else {
                    foreach ($packagesId as $id) {
                        $data = array(
                            'pk_i_id'   => $id,
                            'dt_update' => todaydate(),
                            'b_active'  => 0
                        );
                        if (Packages::newInstance()->setPackage($data)) $i++;
                    }
                    if ($i == 0) {
                        osc_add_flash_error_message(__('No package have been deactivated.', 'packages'), 'admin');
                    } else {
                        osc_add_flash_ok_message(sprintf(__('%s package(s) have been deactivated.', 'packages'), $i), 'admin');
                    }
                }
                ob_get_clean();
                $this->redirectTo($_SERVER['HTTP_REFERER']);
                break;

            case 'set_default':
                $package = get_package_by_id(Params::getParam('id'));
                if ($package && $package['b_company']) {
                    osc_set_preference('default_company_package', Params::getParam('id'), 'packages', 'STRING');
                } else {
                    osc_set_preference('default_package', Params::getParam('id'), 'packages', 'STRING');
                }

                osc_add_flash_ok_message(__('Default package selected.', 'packages'), 'admin');
                ob_get_clean();
                $this->redirectTo(osc_route_admin_url('packages-admin'));
                break;

            case 'unset_default':
                $package = get_package_by_id(Params::getParam('id'));
                if ($package && $package['b_company']) {
                    osc_set_preference('default_company_package', 0, 'packages', 'STRING');
                } else {
                    osc_set_preference('default_package', 0, 'packages', 'STRING');
                }

                osc_add_flash_ok_message(__('Default package disabled.', 'packages'), 'admin');
                ob_get_clean();
                $this->redirectTo(osc_route_admin_url('packages-admin'));
                break;

            case 'upload_packages':
                osc_csrf_check();
                $path = PACKAGES_PATH;

                if(!is_writeable($path)) {
                    @chmod($path, 0777);
                }

                $file = Params::getFiles('file');

                if (!XMLValidator::isXMLFileValid($file['tmp_name'])) {
                    osc_add_flash_error_message(__('Is not valid XML file.', 'packages'), 'admin');
                } else {
                    if ($file['error'] == UPLOAD_ERR_OK) {
                        if (move_uploaded_file($file['tmp_name'], $path . 'packages.xml')) {
                            osc_add_flash_ok_message(__('The XML file has been uploaded.', 'packages'), 'admin');
                        } else {
                            osc_add_flash_error_message(sprintf(__('An error has occurred to upload file, please try again. (%s)', 'packages'), '1'), 'admin');
                        }
                    } else {
                        osc_add_flash_error_message(sprintf(__('An error has occurred to upload file, please try again. (%s)', 'packages'), '2'), 'admin');
                    }
                }
                ob_get_clean();
                $this->redirectTo(osc_route_admin_url('packages-admin'));
                break;

            case 'delete_packages':
                osc_csrf_check();
                $file = PACKAGES_PATH . 'packages.xml';
                $deleted = false;
                if (file_exists($file)) {
                    if (@unlink($file)) $deleted = true;
                }       
                if ($deleted) {
                    osc_add_flash_ok_message(__('The XML file has been deleted.', 'packages'), 'admin');
                } else {
                    osc_add_flash_error_message(__('The XML file could not be removed.', 'packages'), 'admin');
                }
                ob_get_clean();
                $this->redirectTo(osc_route_admin_url('packages-admin'));
                break;

            case 'import_packages':
                osc_csrf_check();
                $file = PACKAGES_PATH . 'packages.xml';
                if (file_exists($file)) {
                    osc_add_flash_info_message(Packages::newInstance()->importXML($file), 'admin');
                } else {
                    osc_add_flash_error_message(__('The XML file not exists.', 'packages'), 'admin');
                }
                ob_get_clean();
                $this->redirectTo(osc_route_admin_url('packages-admin'));
                break;

            case 'export_packages':
                osc_csrf_check();
                $file = PACKAGES_PATH . 'packages.xml';
                osc_add_flash_info_message(Packages::newInstance()->exportXML($file), 'admin');
                ob_get_clean();
                $this->redirectTo(osc_route_admin_url('packages-admin'));
                break;

            case 'download_packages':
                osc_csrf_check();

                $file = 'packages.xml';
                $path = PACKAGES_PATH;

                $filepath = $path.$file;

                // Validate if is a .xml file
                if (preg_match('/^.*\.(xml)$/i', $file) && (file_exists($filepath))) {
                    header('Content-Type: application/xml');
                    header('Content-Disposition: attachment; filename="'.$file.'"');
                    readfile($filepath);
                    exit;
                } else {
                    ob_get_clean();
                    $this->redirectTo(osc_route_admin_url('packages-admin'));
                }
                break;
            
            default:
                $this->_exportVariableToView('packageById', $packageById);

                // DataTable
                require_once PACKAGES_PATH . 'classes/datatables/PackagesDataTable.php';

                if( Params::getParam('iDisplayLength') != '' ) {
                    Cookie::newInstance()->push('listing_iDisplayLength', Params::getParam('iDisplayLength'));
                    Cookie::newInstance()->set();
                } else {
                    // Set a default value if it's set in the cookie
                    $listing_iDisplayLength = (int) Cookie::newInstance()->get_value('listing_iDisplayLength');
                    if ($listing_iDisplayLength == 0) $listing_iDisplayLength = 10;
                    Params::setParam('iDisplayLength', $listing_iDisplayLength );
                }
                $this->_exportVariableToView('iDisplayLength', Params::getParam('iDisplayLength'));

                // Table header order by related
                if( Params::getParam('sort') == '') {
                    Params::setParam('sort', 'date');
                }
                if( Params::getParam('direction') == '') {
                    Params::setParam('direction', 'desc');
                }

                $page  = (int)Params::getParam('iPage');
                if($page==0) { $page = 1; };
                Params::setParam('iPage', $page);

                $params = Params::getParamsAsArray();

                $packagesDataTable = new PackagesDataTable();
                $packagesDataTable->table($params);
                $aData = $packagesDataTable->getData();

                if(count($aData['aRows']) == 0 && $page!=1) {
                    $total = (int)$aData['iTotalDisplayRecords'];
                    $maxPage = ceil( $total / (int)$aData['iDisplayLength'] );

                    $url = osc_admin_base_url(true).'?'.$_SERVER['QUERY_STRING'];

                    if($maxPage==0) {
                        $url = preg_replace('/&iPage=(\d)+/', '&iPage=1', $url);
                        ob_get_clean();
                        $this->redirectTo($url);
                    }

                    if($page > $maxPage) {
                        $url = preg_replace('/&iPage=(\d)+/', '&iPage='.$maxPage, $url);
                        ob_get_clean();
                        $this->redirectTo($url);
                    }
                }

                $this->_exportVariableToView('aData', $aData);

                $bulk_options = array(
                    array('value' => '', 'data-dialog-content' => '', 'label' => __('Bulk actions')),
                    array('value' => 'activate', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected packages?', 'packages'), strtolower(__('Activate'))), 'label' => __('Activate')),
                    array('value' => 'deactivate', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected packages?', 'packages'), strtolower(__('Deactivate'))), 'label' => __('Deactivate')),
                    array('value' => 'delete', 'data-dialog-content' => sprintf(__('Are you sure you want to %s the selected packages?', 'packages'), strtolower(__('Delete'))), 'label' => __('Delete'))
                );

                $bulk_options = osc_apply_filter('package_bulk_filter', $bulk_options);
                $this->_exportVariableToView('bulk_options', $bulk_options);
                break;
        }
    }
    
}